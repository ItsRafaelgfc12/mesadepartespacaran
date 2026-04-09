<?php
require_once __DIR__ . "/../librerias/conexion.php";

class ExpedienteModelo {

    public function crearExpediente($post, $files, $id_usuario) {
        global $conn;
        $conn->begin_transaction();
        
        try {
            // ==========================================
            // 1. CREAR EL EXPEDIENTE PRINCIPAL
            // ==========================================
            $codigo = trim($post['codigo_expediente']);
            $asunto = trim($post['asunto']);
            $tipo = $post['tipo']; // privado, publico o compartido

            $stmtExp = $conn->prepare("INSERT INTO expediente (codigo_expediente, asunto, tipo, id_usuario_responsable, estado) VALUES (?, ?, ?, ?, 'activo')");
            $stmtExp->bind_param("sssi", $codigo, $asunto, $tipo, $id_usuario);
            
            // Si el código ya existe, MySQL lanzará un error que atrapará el catch
            if (!$stmtExp->execute()) {
                if ($conn->errno == 1062) { // 1062 es código de entrada duplicada en MySQL
                    throw new Exception("El código de expediente '$codigo' ya está en uso. Por favor, asigne uno diferente.");
                }
                throw new Exception("Error al crear la cabecera del expediente: " . $stmtExp->error);
            }
            $id_expediente = $conn->insert_id;

            // ==========================================
            // 2. REGISTRAR HISTORIAL DE CREACIÓN
            // ==========================================
            $stmtHist = $conn->prepare("INSERT INTO expediente_historial (id_expediente, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'creado', 'Apertura inicial del expediente')");
            $stmtHist->bind_param("ii", $id_expediente, $id_usuario);
            $stmtHist->execute();

            // ==========================================
            // 3. ASIGNAR ACCESOS (Si es Compartido)
            // ==========================================
            if ($tipo === 'compartido' && isset($post['tipo_acceso']) && is_array($post['tipo_acceso'])) {
                $stmtAcc = $conn->prepare("INSERT INTO expediente_acceso (id_expediente, tipo_acceso, id_referencia, permiso) VALUES (?, ?, ?, ?)");
                $stmtHistAcc = $conn->prepare("INSERT INTO expediente_historial (id_expediente, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'acceso_asignado', ?)");

                for ($i = 0; $i < count($post['tipo_acceso']); $i++) {
                    $t_acceso = $post['tipo_acceso'][$i];
                    $id_ref = $post['id_referencia'][$i];
                    $permiso = $post['permiso'][$i];

                    if (!empty($t_acceso) && !empty($id_ref)) {
                        $stmtAcc->bind_param("isis", $id_expediente, $t_acceso, $id_ref, $permiso);
                        $stmtAcc->execute();

                        // Registrar en historial a quién se le dio permiso
                        $obs = "Se otorgó permiso de '$permiso' a nivel de '$t_acceso'.";
                        $stmtHistAcc->bind_param("iis", $id_expediente, $id_usuario, $obs);
                        $stmtHistAcc->execute();
                    }
                }
            }

            // ==========================================
            // 4. SUBIR DOCUMENTO INICIAL Y SU VERSIÓN (Opcional)
            // ==========================================
            if (!empty(trim($post['nombre_documento'])) && isset($files['archivo_version']) && $files['archivo_version']['error'] === UPLOAD_ERR_OK) {
                
                // 4.1 Crear el Documento Lógico
                $nom_doc = trim($post['nombre_documento']);
                $stmtDoc = $conn->prepare("INSERT INTO expediente_documento (id_expediente, nombre, version_actual) VALUES (?, ?, 1)");
                $stmtDoc->bind_param("is", $id_expediente, $nom_doc);
                $stmtDoc->execute();
                $id_documento = $conn->insert_id;

                // 4.2 Validar extensión permitida
                $ext = strtolower(pathinfo($files['archivo_version']['name'], PATHINFO_EXTENSION));
                $permitidas = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', '7z'];
                
                if (!in_array($ext, $permitidas)) {
                    throw new Exception("La extensión del documento inicial no está permitida.");
                }

                // 4.3 Mover archivo físico
                $dir_base = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "expedientes" . DIRECTORY_SEPARATOR;
                if (!file_exists($dir_base)) {
                    mkdir($dir_base, 0777, true);
                }

                $peso = $files['archivo_version']['size'];
                $nombre_original = $files['archivo_version']['name'];
                $nuevo_nombre = "EXP_" . $id_expediente . "_DOC_" . $id_documento . "_V1_" . time() . "." . $ext;
                $ruta_destino_fisica = $dir_base . $nuevo_nombre;
                
                if (move_uploaded_file($files['archivo_version']['tmp_name'], $ruta_destino_fisica)) {
                    $ruta_bd = "uploads/expedientes/" . $nuevo_nombre;
                    $comentario = !empty(trim($post['comentario_version'])) ? trim($post['comentario_version']) : "Versión Inicial (V1)";

                    // 4.4 Guardar la Versión 1 en la BD
                    $stmtVer = $conn->prepare("INSERT INTO expediente_version (id_documento, version, ruta_archivo, nombre_original, extension, peso, id_usuario, comentario) VALUES (?, 1, ?, ?, ?, ?, ?, ?)");
                    $stmtVer->bind_param("issssis", $id_documento, $ruta_bd, $nombre_original, $ext, $peso, $id_usuario, $comentario);
                    $stmtVer->execute();

                    // 4.5 Registrar en el historial general del expediente
                    $obs_doc = "Documento inicial agregado: '$nom_doc' (V1)";
                    $stmtHistDoc = $conn->prepare("INSERT INTO expediente_historial (id_expediente, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'modificado', ?)");
                    $stmtHistDoc->bind_param("iis", $id_expediente, $id_usuario, $obs_doc);
                    $stmtHistDoc->execute();

                } else {
                    throw new Exception("No se pudo subir el archivo inicial al servidor.");
                }
            }

            // ==========================================
            // CONFIRMAR TRANSACCIÓN
            // ==========================================
            $conn->commit();
            return ["status" => "ok", "mensaje" => "Expediente '$codigo' creado exitosamente."];

        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }
    public function listarMisExpedientes($id_usuario, $id_rol, $cargos_ids, $areas_ids) {
        global $conn;
        
        $sql = "SELECT DISTINCT e.id_expediente, e.codigo_expediente, e.asunto, e.tipo, e.estado, 
                       DATE_FORMAT(e.fecha_creacion, '%d/%m/%Y %H:%i') as fecha_creacion,
                       e.id_usuario_responsable,
                       /* Averiguamos qué nivel de permiso tiene este usuario sobre el expediente */
                       CASE 
                           WHEN e.id_usuario_responsable = ? THEN 'propietario'
                           ELSE (
                               SELECT permiso FROM expediente_acceso a 
                               WHERE a.id_expediente = e.id_expediente 
                               AND (
                                   (a.tipo_acceso = 'usuario' AND a.id_referencia = ?) OR
                                   (a.tipo_acceso = 'rol' AND a.id_referencia = ?) OR
                                   (a.tipo_acceso = 'area' AND FIND_IN_SET(a.id_referencia, ?) > 0) OR
                                   (a.tipo_acceso = 'cargo' AND FIND_IN_SET(a.id_referencia, ?) > 0)
                               )
                               /* Si pertenece a 2 grupos con permisos distintos, priorizamos el más alto */
                               ORDER BY CASE permiso WHEN 'administrador' THEN 1 WHEN 'edicion' THEN 2 ELSE 3 END
                               LIMIT 1
                           )
                       END as mi_permiso
                FROM expediente e
                WHERE e.id_usuario_responsable = ? 
                   OR e.id_expediente IN (
                       /* Subconsulta: ¿Aparezco en la tabla de accesos con alguno de mis sombreros? */
                       SELECT id_expediente FROM expediente_acceso 
                       WHERE (tipo_acceso = 'usuario' AND id_referencia = ?) 
                          OR (tipo_acceso = 'rol' AND id_referencia = ?)
                          OR (tipo_acceso = 'area' AND FIND_IN_SET(id_referencia, ?) > 0)
                          OR (tipo_acceso = 'cargo' AND FIND_IN_SET(id_referencia, ?) > 0)
                   )
                ORDER BY e.id_expediente DESC";
                
        $stmt = $conn->prepare($sql);
        // Pasamos las variables 2 veces (una para el SELECT de permisos y otra para el WHERE general)
        $stmt->bind_param("iiissiiiss", $id_usuario, $id_usuario, $id_rol, $areas_ids, $cargos_ids, $id_usuario, $id_usuario, $id_rol, $areas_ids, $cargos_ids);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    // ==========================================
    // DETALLES E HISTORIAL DEL EXPEDIENTE
    // ==========================================
    public function obtenerDetallesExpediente($id_expediente) {
        global $conn;

        // 1. Obtener documentos y sus versiones
        $sqlVer = "SELECT v.id_version, d.id_documento, d.nombre as nombre_documento, v.version, 
                          v.ruta_archivo, v.comentario, 
                          DATE_FORMAT(v.fecha_subida, '%d/%m/%Y %H:%i') as fecha_subida,
                          CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario) as subido_por
                   FROM expediente_version v
                   INNER JOIN expediente_documento d ON v.id_documento = d.id_documento
                   LEFT JOIN usuario u ON v.id_usuario = u.id_usuario
                   WHERE d.id_expediente = ?
                   ORDER BY d.id_documento ASC, v.version DESC";
        $stmtV = $conn->prepare($sqlVer);
        $stmtV->bind_param("i", $id_expediente);
        $stmtV->execute();
        $versiones = $stmtV->get_result()->fetch_all(MYSQLI_ASSOC);

        // 2. Obtener Historial de Auditoría
        $sqlHist = "SELECT h.tipo_evento, h.observacion, 
                           DATE_FORMAT(h.fecha, '%d/%m/%Y %H:%i') as fecha, 
                           IFNULL(u.nombres_usuario, 'Sistema') as nombres_usuario
                    FROM expediente_historial h
                    LEFT JOIN usuario u ON h.id_usuario = u.id_usuario
                    WHERE h.id_expediente = ?
                    ORDER BY h.fecha DESC";
        $stmtH = $conn->prepare($sqlHist);
        $stmtH->bind_param("i", $id_expediente);
        $stmtH->execute();
        $historial = $stmtH->get_result()->fetch_all(MYSQLI_ASSOC);

        return ["versiones" => $versiones, "historial" => $historial];
    }

    // ==========================================
    // SUBIR NUEVA VERSIÓN O DOCUMENTO
    // ==========================================
    public function subirVersionExpediente($post, $archivo, $id_usuario) {
        global $conn;
        $conn->begin_transaction();
        try {
            $id_expediente = $post['id_expediente'];
            $id_doc_select = $post['id_documento']; // Puede ser un ID numérico o la palabra "nuevo"
            $comentario = trim($post['comentario']);

            // 1. Validar extensión
            $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $permitidas = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', '7z'];
            if (!in_array($ext, $permitidas)) throw new Exception("Formato de archivo no permitido.");

            // 2. ¿Es un documento nuevo o una versión de uno existente?
            if ($id_doc_select === 'nuevo') {
                $nombre_nuevo = trim($post['nuevo_nombre']);
                if (empty($nombre_nuevo)) throw new Exception("Debe ingresar un nombre para el nuevo documento.");
                
                // Creamos el documento lógico
                $stmtD = $conn->prepare("INSERT INTO expediente_documento (id_expediente, nombre, version_actual) VALUES (?, ?, 1)");
                $stmtD->bind_param("is", $id_expediente, $nombre_nuevo);
                $stmtD->execute();
                
                $id_doc_final = $conn->insert_id;
                $version_num = 1;
                $obs_historial = "Se agregó un nuevo documento: '$nombre_nuevo' (V1)";
            } else {
                $id_doc_final = (int)$id_doc_select;
                
                // Incrementamos la versión del documento existente
                $conn->query("UPDATE expediente_documento SET version_actual = version_actual + 1 WHERE id_documento = $id_doc_final");
                
                // Obtenemos qué versión es ahora
                $res = $conn->query("SELECT nombre, version_actual FROM expediente_documento WHERE id_documento = $id_doc_final");
                $row = $res->fetch_assoc();
                $version_num = $row['version_actual'];
                $obs_historial = "Se subió la Versión $version_num del documento: '{$row['nombre']}'";
            }

            // 3. Subir el archivo físico
            $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "expedientes" . DIRECTORY_SEPARATOR;
            if (!file_exists($dir)) mkdir($dir, 0777, true);

            $nombre_archivo = "EXP_{$id_expediente}_DOC_{$id_doc_final}_V{$version_num}_" . time() . "." . $ext;
            
            if (move_uploaded_file($archivo['tmp_name'], $dir . $nombre_archivo)) {
                $ruta_bd = "uploads/expedientes/" . $nombre_archivo;
                
                // Guardar Versión
                $stmtV = $conn->prepare("INSERT INTO expediente_version (id_documento, version, ruta_archivo, nombre_original, extension, peso, id_usuario, comentario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $peso = $archivo['size'];
                $nombre_orig = $archivo['name'];
                $stmtV->bind_param("iisssiis", $id_doc_final, $version_num, $ruta_bd, $nombre_orig, $ext, $peso, $id_usuario, $comentario);
                $stmtV->execute();

                // Guardar Historial
                $stmtH = $conn->prepare("INSERT INTO expediente_historial (id_expediente, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'modificado', ?)");
                $stmtH->bind_param("iis", $id_expediente, $id_usuario, $obs_historial);
                $stmtH->execute();

                $conn->commit();
                return ["status" => "ok", "mensaje" => "Documento subido correctamente."];
            } else {
                throw new Exception("Error al mover el archivo al servidor.");
            }
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    // ==========================================
    // ADMINISTRAR ACCESOS
    // ==========================================
    public function obtenerAccesosExpediente($id_expediente) {
        global $conn;
        $sql = "SELECT a.id_acceso, a.tipo_acceso, a.permiso, DATE_FORMAT(a.fecha_asignacion, '%d/%m/%Y') as fecha_asignacion,
                       CASE 
                            WHEN a.tipo_acceso = 'usuario' THEN (SELECT CONCAT(nombres_usuario, ' ', apellidos_usuario) FROM usuario WHERE id_usuario = a.id_referencia)
                            WHEN a.tipo_acceso = 'area' THEN (SELECT nombre_area FROM area WHERE id_area = a.id_referencia)
                            WHEN a.tipo_acceso = 'cargo' THEN (SELECT cargo FROM cargo WHERE id_cargo = a.id_referencia)
                            WHEN a.tipo_acceso = 'rol' THEN (SELECT rol FROM rol WHERE id_rol = a.id_referencia)
                       END as nombre_destino
                FROM expediente_acceso a
                WHERE a.id_expediente = ?
                ORDER BY a.fecha_asignacion DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_expediente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarAccesoExpediente($post, $id_usuario) {
        global $conn;
        $conn->begin_transaction();
        try {
            $id_exp = $post['id_expediente'];
            $tipo = $post['tipo_acceso'];
            $ref = $post['id_referencia'];
            $permiso = $post['permiso'];

            // Evitar duplicados (MySQL también lanzará error por el UNIQUE KEY)
            $stmt = $conn->prepare("INSERT INTO expediente_acceso (id_expediente, tipo_acceso, id_referencia, permiso) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isis", $id_exp, $tipo, $ref, $permiso);
            if (!$stmt->execute()) throw new Exception("Esta persona o área ya tiene un acceso asignado.");

            $obs = "Se otorgó nuevo permiso de '$permiso' a nivel de '$tipo'.";
            $stmtH = $conn->prepare("INSERT INTO expediente_historial (id_expediente, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'acceso_asignado', ?)");
            $stmtH->bind_param("iis", $id_exp, $id_usuario, $obs);
            $stmtH->execute();

            $conn->commit();
            return ["status" => "ok"];
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    public function revocarAccesoExpediente($id_acceso, $id_expediente, $id_usuario) {
        global $conn;
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("DELETE FROM expediente_acceso WHERE id_acceso = ? AND id_expediente = ?");
            $stmt->bind_param("ii", $id_acceso, $id_expediente);
            $stmt->execute();

            $stmtH = $conn->prepare("INSERT INTO expediente_historial (id_expediente, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'acceso_revocado', 'Se revocó un permiso de acceso manual.')");
            $stmtH->bind_param("ii", $id_expediente, $id_usuario);
            $stmtH->execute();

            $conn->commit();
            return ["status" => "ok"];
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => "No se pudo revocar el acceso."];
        }
    }
    // ==========================================
    // SOLICITUDES DE ACCESO
    // ==========================================
    public function listarSolicitudesAcceso($id_expediente) {
        global $conn;
        $sql = "SELECT s.id_solicitud, s.mensaje, DATE_FORMAT(s.fecha_solicitud, '%d/%m/%Y %H:%i') as fecha_solicitud, 
                       CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario) as nombres_usuario, u.id_usuario
                FROM expediente_solicitud s
                INNER JOIN usuario u ON s.id_usuario_solicitante = u.id_usuario
                WHERE s.id_expediente = ? AND s.estado = 'pendiente'
                ORDER BY s.fecha_solicitud ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_expediente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function procesarSolicitudAcceso($id_solicitud, $id_expediente, $estado_nuevo, $id_usuario_admin) {
        global $conn;
        $conn->begin_transaction();
        try {
            // 1. Cambiamos el estado de la solicitud
            $stmt1 = $conn->prepare("UPDATE expediente_solicitud SET estado = ? WHERE id_solicitud = ?");
            $stmt1->bind_param("si", $estado_nuevo, $id_solicitud);
            $stmt1->execute();

            // 2. Si se aprobó, insertamos el permiso automáticamente (Lectura por defecto)
            if ($estado_nuevo === 'aprobado') {
                // Obtenemos qué usuario la solicitó
                $res = $conn->query("SELECT id_usuario_solicitante FROM expediente_solicitud WHERE id_solicitud = $id_solicitud");
                $id_solicitante = $res->fetch_assoc()['id_usuario_solicitante'];

                $stmt2 = $conn->prepare("INSERT INTO expediente_acceso (id_expediente, tipo_acceso, id_referencia, permiso) VALUES (?, 'usuario', ?, 'lectura')");
                $stmt2->bind_param("ii", $id_expediente, $id_solicitante);
                
                // Ignoramos error si ya tiene acceso por otro lado
                try { $stmt2->execute(); } catch (Exception $e) {}

                $obs = "Se aprobó la solicitud de acceso. Se otorgó permiso de lectura.";
            } else {
                $obs = "Se rechazó la solicitud de acceso.";
            }

            // 3. Historial
            $stmtHist = $conn->prepare("INSERT INTO expediente_historial (id_expediente, id_usuario, tipo_evento, observacion) VALUES (?, ?, ?, ?)");
            $tipo_ev = $estado_nuevo === 'aprobado' ? 'aprobado' : 'rechazado';
            $stmtHist->bind_param("iiss", $id_expediente, $id_usuario_admin, $tipo_ev, $obs);
            $stmtHist->execute();

            $conn->commit();
            $msg = $estado_nuevo === 'aprobado' ? 'Solicitud aprobada correctamente.' : 'Solicitud rechazada.';
            return ["status" => "ok", "mensaje" => $msg];

        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }
    // ==========================================
    // EXPEDIENTES PÚBLICOS Y SOLICITUDES
    // ==========================================
    public function listarPublicos() {
        global $conn;
        // Traemos todos los públicos, incluyendo el nombre de quién lo creó
        $sql = "SELECT e.id_expediente, e.codigo_expediente, e.asunto, e.estado, 
                       DATE_FORMAT(e.fecha_creacion, '%d/%m/%Y') as fecha_creacion,
                       CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario) as responsable
                FROM expediente e
                LEFT JOIN usuario u ON e.id_usuario_responsable = u.id_usuario
                WHERE e.tipo = 'publico'
                ORDER BY e.id_expediente DESC";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function enviarSolicitudAcceso($id_expediente, $id_usuario, $mensaje) {
        global $conn;
        
        // 1. Evitar solicitudes duplicadas
        $check = $conn->prepare("SELECT id_solicitud FROM expediente_solicitud WHERE id_expediente = ? AND id_usuario_solicitante = ? AND estado = 'pendiente'");
        $check->bind_param("ii", $id_expediente, $id_usuario);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            return ["status" => "error", "mensaje" => "Ya tienes una solicitud de acceso en espera para este expediente."];
        }

        // 2. Insertar nueva solicitud
        $stmt = $conn->prepare("INSERT INTO expediente_solicitud (id_expediente, id_usuario_solicitante, mensaje, estado) VALUES (?, ?, ?, 'pendiente')");
        $stmt->bind_param("iis", $id_expediente, $id_usuario, trim($mensaje));
        
        if ($stmt->execute()) {
            return ["status" => "ok", "mensaje" => "Tu solicitud ha sido enviada al responsable del expediente."];
        } else {
            return ["status" => "error", "mensaje" => "No se pudo enviar la solicitud."];
        }
    }
}
?>
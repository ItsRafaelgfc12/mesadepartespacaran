<?php
require_once __DIR__ . "/../librerias/conexion.php";

class DocumentoModelo {

    /**
     * Lista documentos pendientes para el usuario, filtrando por sus "sombreros"
     * y excluyendo los que ya han sido tomados por otro compañero (id_usuario_receptor IS NULL).
     */
    public function listarRecibidos($id_usuario, $cargos_ids, $areas_ids, $id_rol, $prog_ids) {
        global $conn;
        
        $sql = "SELECT d.id_documento, d.codigo_documento, d.asunto, d.fecha_emision, 
                       IFNULL(CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario), 'Sistema / Sin Remitente') as remitente, 
                       der.id_derivacion, der.tipo_destino, der.id_destino,
                       /* IDENTIFICAR LA VÍA EXACTA */
                       CASE 
                            WHEN der.tipo_destino = 'usuario' THEN 'Personal'
                            WHEN der.tipo_destino = 'area' THEN (SELECT nombre_area FROM area WHERE id_area = der.id_destino)
                            WHEN der.tipo_destino = 'cargo' THEN (SELECT cargo FROM cargo WHERE id_cargo = der.id_destino)
                            WHEN der.tipo_destino = 'rol' THEN (SELECT rol FROM rol WHERE id_rol = der.id_destino)
                            WHEN der.tipo_destino = 'programa' THEN (SELECT programa_estudio FROM programa_estudio WHERE id_programa_estudio = der.id_destino)
                            ELSE der.tipo_destino
                       END as via_exacta,
                       (SELECT ruta_archivo FROM documento_adjuntos WHERE id_documento = d.id_documento ORDER BY id_adjunto DESC LIMIT 1) as ultimo_archivo
                FROM documento d
                INNER JOIN documento_derivacion der ON d.id_documento = der.id_documento
                LEFT JOIN usuario u ON d.id_usuario_emisor = u.id_usuario
                WHERE der.estado = 'pendiente' 
                AND der.id_usuario_receptor IS NULL /* Nadie del área lo ha tomado aún */
                AND (
                    (der.tipo_destino = 'usuario'  AND der.id_destino = ?) 
                    OR (der.tipo_destino = 'cargo' AND FIND_IN_SET(der.id_destino, ?) > 0)
                    OR (der.tipo_destino = 'area'  AND FIND_IN_SET(der.id_destino, ?) > 0)
                    OR (der.tipo_destino = 'rol'   AND der.id_destino = ?)
                    OR (der.tipo_destino = 'programa' AND FIND_IN_SET(der.id_destino, ?) > 0)
                )
                ORDER BY d.id_documento DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issis", $id_usuario, $cargos_ids, $areas_ids, $id_rol, $prog_ids);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Registra la atención de un documento y lo asigna al usuario que lo toma.
     */
    public function atenderDocumento($id_doc, $id_derivacion, $mensaje, $id_usuario) {
        global $conn;
        $conn->begin_transaction();
        try {
            // Check de concurrencia: ¿Ya lo tomó alguien más?
            $check = $conn->prepare("SELECT id_usuario_receptor FROM documento_derivacion WHERE id_derivacion = ?");
            $check->bind_param("i", $id_derivacion);
            $check->execute();
            $res = $check->get_result()->fetch_assoc();
            
            if ($res['id_usuario_receptor'] != null && $res['id_usuario_receptor'] != $id_usuario) {
                throw new Exception("Este documento ya fue tomado por otro compañero del área.");
            }

            // Actualizamos derivación con el usuario receptor
            $stmt_der = $conn->prepare("UPDATE documento_derivacion SET estado = 'atendido', id_usuario_receptor = ? WHERE id_derivacion = ?");
            $stmt_der->bind_param("ii", $id_usuario, $id_derivacion);
            $stmt_der->execute();

            // Marcamos el documento general en proceso
            $stmt1 = $conn->prepare("UPDATE documento SET estado = 'en_proceso' WHERE id_documento = ?");
            $stmt1->bind_param("i", $id_doc);
            $stmt1->execute();

            // Insertamos en historial
            $stmt2 = $conn->prepare("INSERT INTO documento_historial (id_documento, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'atendido', ?)");
            $stmt2->bind_param("iis", $id_doc, $id_usuario, $mensaje);
            $stmt2->execute();

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Documento asignado y registrado."];
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    public function derivarDocumento($id_doc, $id_der_actual, $tipo_destino, $id_destino, $obs, $id_user_sesion, $archivo_anexo = null) {
        global $conn;
        $conn->begin_transaction();
        try {
            $stmt1 = $conn->prepare("UPDATE documento_derivacion SET estado = 'derivado' WHERE id_derivacion = ?");
            $stmt1->bind_param("i", $id_der_actual);
            $stmt1->execute();

            $stmt2 = $conn->prepare("INSERT INTO documento_derivacion (id_documento, tipo_destino, id_destino, estado, fecha_envio) VALUES (?, ?, ?, 'pendiente', NOW())");
            $stmt2->bind_param("isi", $id_doc, $tipo_destino, $id_destino);
            $stmt2->execute();

            if ($archivo_anexo !== null && $archivo_anexo['error'] === UPLOAD_ERR_OK) {
                $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "anexos" . DIRECTORY_SEPARATOR;
                if (!file_exists($dir)) mkdir($dir, 0777, true);

                $nombre_original = $archivo_anexo['name'];
                $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
                $nuevo_nombre = "anexo_" . time() . "_" . rand(1000, 9999) . "." . $ext;
                
                if (move_uploaded_file($archivo_anexo['tmp_name'], $dir . $nuevo_nombre)) {
                    $ruta_bd = "uploads/anexos/" . $nuevo_nombre;
                    $stmtAdj = $conn->prepare("INSERT INTO documento_adjuntos (id_documento, nombre, tipo, ruta_archivo, nombre_original, extension, peso) VALUES (?, ?, 'anexo', ?, ?, ?, ?)");
                    $stmtAdj->bind_param("issssi", $id_doc, $nuevo_nombre, $ruta_bd, $nombre_original, $ext, $archivo_anexo['size']);
                    $stmtAdj->execute();
                }
            }

            $stmt3 = $conn->prepare("INSERT INTO documento_historial (id_documento, id_usuario, tipo_evento, tipo_destino, id_destino, observacion) VALUES (?, ?, 'derivado', ?, ?, ?)");
            $stmt3->bind_param("iisis", $id_doc, $id_user_sesion, $tipo_destino, $id_destino, $obs);
            $stmt3->execute();

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Documento derivado correctamente"];
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    public function archivarDocumento($id_doc, $mensaje, $id_user_sesion, $archivo_final = null) {
        global $conn;
        $conn->begin_transaction();
        try {
            $ruta_bd = null;
            if ($archivo_final !== null && $archivo_final['error'] === UPLOAD_ERR_OK) {
                $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "finales" . DIRECTORY_SEPARATOR;
                if (!file_exists($dir)) mkdir($dir, 0777, true);

                $ext = strtolower(pathinfo($archivo_final['name'], PATHINFO_EXTENSION));
                $nombre = "FINAL_" . $id_doc . "_" . time() . "." . $ext;
                if (move_uploaded_file($archivo_final['tmp_name'], $dir . $nombre)) {
                    $ruta_bd = "uploads/finales/" . $nombre;
                }
            }

            $stmt1 = $conn->prepare("INSERT INTO documento_archivo (id_documento, fecha_archivado, mensaje, id_usuario, ruta_archivo_final) VALUES (?, NOW(), ?, ?, ?)");
            $stmt1->bind_param("isis", $id_doc, $mensaje, $id_user_sesion, $ruta_bd);
            $stmt1->execute();

            $conn->query("UPDATE documento SET estado = 'archivado' WHERE id_documento = $id_doc");
            $conn->query("UPDATE documento_derivacion SET estado = 'finalizado' WHERE id_documento = $id_doc AND estado != 'finalizado'");

            $stmtHist = $conn->prepare("INSERT INTO documento_historial (id_documento, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'archivado', ?)");
            $obs = "ARCHIVO FINAL: " . $mensaje;
            $stmtHist->bind_param("iis", $id_doc, $id_user_sesion, $obs);
            $stmtHist->execute();

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Documento archivado."];
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    public function listarArchivados() {
        global $conn;
        $sql = "SELECT d.id_documento, d.codigo_documento, d.asunto, a.fecha_archivado, a.mensaje, a.ruta_archivo_final,
                       IFNULL(CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario), 'Sistema') as archivado_por
                FROM documento d
                INNER JOIN documento_archivo a ON d.id_documento = a.id_documento
                LEFT JOIN usuario u ON a.id_usuario = u.id_usuario
                ORDER BY a.fecha_archivado DESC";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function listarAtendidos($id_usuario) {
        global $conn;
        $sql = "SELECT d.id_documento, d.codigo_documento, d.asunto, d.fecha_emision, 
                       IFNULL(CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario), 'Sistema') as remitente,
                       der.id_derivacion, der.tipo_destino,
                       CASE 
                            WHEN der.tipo_destino = 'usuario' THEN 'Personal'
                            WHEN der.tipo_destino = 'area' THEN (SELECT nombre_area FROM area WHERE id_area = der.id_destino)
                            WHEN der.tipo_destino = 'cargo' THEN (SELECT cargo FROM cargo WHERE id_cargo = der.id_destino)
                            WHEN der.tipo_destino = 'rol' THEN (SELECT rol FROM rol WHERE id_rol = der.id_destino)
                            WHEN der.tipo_destino = 'programa' THEN (SELECT programa_estudio FROM programa_estudio WHERE id_programa_estudio = der.id_destino)
                            ELSE der.tipo_destino
                       END as via_exacta,
                       (SELECT ruta_archivo FROM documento_adjuntos WHERE id_documento = d.id_documento ORDER BY id_adjunto DESC LIMIT 1) as ultimo_archivo
                FROM documento d
                INNER JOIN documento_derivacion der ON d.id_documento = der.id_documento
                LEFT JOIN usuario u ON d.id_usuario_emisor = u.id_usuario
                WHERE der.id_usuario_receptor = ? 
                AND der.estado = 'atendido'
                AND d.id_documento NOT IN (SELECT id_documento FROM documento_archivo)
                ORDER BY der.fecha_envio DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerSeguimiento($id_doc) {
        global $conn;
        $stmt = $conn->prepare("SELECT h.tipo_evento, h.observacion, h.fecha, u.nombres_usuario, a.ruta_archivo_final as archivo_historial
                                FROM documento_historial h
                                LEFT JOIN usuario u ON u.id_usuario = h.id_usuario
                                LEFT JOIN documento_archivo a ON (h.id_documento = a.id_documento AND h.tipo_evento = 'archivado')
                                WHERE h.id_documento = ? ORDER BY h.fecha ASC");
        $stmt->bind_param("i", $id_doc);
        $stmt->execute();
        $hist = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt2 = $conn->prepare("SELECT d.tipo_destino, d.estado, d.fecha_envio,
                                    CASE 
                                        WHEN d.tipo_destino = 'usuario' THEN us.nombres_usuario
                                        WHEN d.tipo_destino = 'area' THEN ar.nombre_area
                                        WHEN d.tipo_destino = 'cargo' THEN c.cargo
                                        WHEN d.tipo_destino = 'rol' THEN r.rol
                                        WHEN d.tipo_destino = 'programa' THEN p.programa_estudio
                                        ELSE 'Desconocido'
                                    END AS destino_nombre,
                                    (SELECT ruta_archivo FROM documento_adjuntos WHERE id_documento = d.id_documento 
                                     AND ABS(TIMESTAMPDIFF(SECOND, fecha_subida, d.fecha_envio)) < 10 LIMIT 1) as archivo_adjunto
                                 FROM documento_derivacion d
                                 LEFT JOIN usuario us ON (d.tipo_destino = 'usuario' AND d.id_destino = us.id_usuario)
                                 LEFT JOIN area ar ON (d.tipo_destino = 'area' AND d.id_destino = ar.id_area)
                                 LEFT JOIN cargo c ON (d.tipo_destino = 'cargo' AND d.id_destino = c.id_cargo)
                                 LEFT JOIN rol r ON (d.tipo_destino = 'rol' AND d.id_destino = r.id_rol)
                                 LEFT JOIN programa_estudio p ON (d.tipo_destino = 'programa' AND d.id_destino = p.id_programa_estudio)
                                 WHERE d.id_documento = ? ORDER BY d.fecha_envio ASC");
        $stmt2->bind_param("i", $id_doc);
        $stmt2->execute();
        $der = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

        return ["historial" => $hist, "derivaciones" => $der];
    }

    public function registrarDocumentoInterno($post, $files, $id_emisor) {
        global $conn;
        $conn->begin_transaction();
        try {
            // Nota: Si ya implementaste el select de tipo de documento en la vista, 
            // puedes cambiar estas 3 líneas por: $id_tipo = $post['id_tipo'];
            $resTipo = $conn->query("SELECT id_tipo FROM tipo_documento WHERE id_tipo > 1 LIMIT 1");
            $rowTipo = $resTipo->fetch_assoc();
            $id_tipo = $rowTipo['id_tipo'];

            $stmt = $conn->prepare("INSERT INTO documento (id_tipo, codigo_documento, asunto, descripcion, fecha_emision, id_usuario_emisor, estado) VALUES (?, ?, ?, ?, NOW(), ?, 'enviado')");
            $stmt->bind_param("isssi", $id_tipo, $post['codigo_documento'], $post['asunto'], $post['descripcion'], $id_emisor);
            $stmt->execute();
            $id_doc = $conn->insert_id;

            if (isset($files['url_doc']) && $files['url_doc']['error'] === UPLOAD_ERR_OK) {
                // 1. Obtenemos la extensión en minúsculas
                $ext = strtolower(pathinfo($files['url_doc']['name'], PATHINFO_EXTENSION));
                
                // 🔥 2. Filtro de seguridad: Solo permitimos estas extensiones
                $permitidas = ['pdf', 'zip', 'rar', '7z'];
                if (!in_array($ext, $permitidas)) {
                    throw new Exception("Formato no permitido. Solo se aceptan archivos PDF, ZIP, RAR o 7Z.");
                }

                $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "internos" . DIRECTORY_SEPARATOR;
                if (!file_exists($dir)) mkdir($dir, 0777, true);
                
                $nombre = "INT_" . $id_doc . "_" . time() . "." . $ext;
                
                if (move_uploaded_file($files['url_doc']['tmp_name'], $dir . $nombre)) {
                    $ruta_bd = "uploads/internos/" . $nombre;
                    
                    // 🔥 3. Definimos dinámicamente si es PDF o Comprimido
                    $tipo_archivo = ($ext === 'pdf') ? 'pdf' : 'comprimido';

                    // Modificamos el INSERT para usar la variable $tipo_archivo
                    $stmtAdj = $conn->prepare("INSERT INTO documento_adjuntos (id_documento, nombre, tipo, ruta_archivo, nombre_original, extension, peso) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmtAdj->bind_param("isssssi", $id_doc, $nombre, $tipo_archivo, $ruta_bd, $files['url_doc']['name'], $ext, $files['url_doc']['size']);
                    $stmtAdj->execute();
                } else {
                    throw new Exception("Error al mover el archivo al servidor. Verifique los permisos.");
                }
            }

            $stmtDer = $conn->prepare("INSERT INTO documento_derivacion (id_documento, tipo_destino, id_destino, estado, fecha_envio) VALUES (?, ?, ?, 'pendiente', NOW())");
            $stmtDer->bind_param("isi", $id_doc, $post['tipo_envio'], $post['id_destino']);
            $stmtDer->execute();

            $stmtHist = $conn->prepare("INSERT INTO documento_historial (id_documento, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'creado', 'Doc interno enviado')");
            $stmtHist->bind_param("ii", $id_doc, $id_emisor);
            $stmtHist->execute();

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Documento enviado."];
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    public function listarHistorialEnviados($id_usuario) {
        global $conn;
        $sql = "SELECT d.id_documento, d.codigo_documento, d.asunto, d.fecha_emision, d.estado,
                (SELECT ruta_archivo FROM documento_adjuntos WHERE id_documento = d.id_documento ORDER BY id_adjunto ASC LIMIT 1) as ruta_archivo,
                (SELECT 
                    CASE 
                        WHEN der.tipo_destino = 'usuario' THEN u.nombres_usuario
                        WHEN der.tipo_destino = 'area' THEN ar.nombre_area
                        WHEN der.tipo_destino = 'cargo' THEN c.cargo
                        WHEN der.tipo_destino = 'rol' THEN r.rol
                        WHEN der.tipo_destino = 'programa' THEN p.programa_estudio
                        ELSE 'Desconocido'
                    END
                 FROM documento_derivacion der
                 LEFT JOIN usuario u ON (der.tipo_destino = 'usuario' AND der.id_destino = u.id_usuario)
                 LEFT JOIN area ar ON (der.tipo_destino = 'area' AND der.id_destino = ar.id_area)
                 LEFT JOIN cargo c ON (der.tipo_destino = 'cargo' AND der.id_destino = c.id_cargo)
                 LEFT JOIN rol r ON (der.tipo_destino = 'rol' AND der.id_destino = r.id_rol)
                 LEFT JOIN programa_estudio p ON (der.tipo_destino = 'programa' AND der.id_destino = p.id_programa_estudio)
                 WHERE der.id_documento = d.id_documento ORDER BY der.id_derivacion DESC LIMIT 1) as destino_actual
                FROM documento d WHERE d.id_usuario_emisor = ? ORDER BY d.id_documento DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    /**
     * Libera un documento para que vuelva a estar disponible para el área/cargo.
     */
    public function liberarDocumento($id_doc, $id_derivacion, $id_usuario) {
        global $conn;
        $conn->begin_transaction();
        try {
            // 1. Ponemos la derivación en pendiente y quitamos al receptor
            $stmt1 = $conn->prepare("UPDATE documento_derivacion SET estado = 'pendiente', id_usuario_receptor = NULL WHERE id_derivacion = ?");
            $stmt1->bind_param("i", $id_derivacion);
            $stmt1->execute();

            // 2. El documento vuelve a estado 'enviado' para que aparezca como nuevo en Recibidos
            $stmt2 = $conn->prepare("UPDATE documento SET estado = 'enviado' WHERE id_documento = ?");
            $stmt2->bind_param("i", $id_doc);
            $stmt2->execute();

            // 3. Dejamos constancia en el historial
            $stmt3 = $conn->prepare("INSERT INTO documento_historial (id_documento, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'derivado', 'Documento liberado: El usuario lo devuelve a la bandeja de entrada para que otro compañero pueda atenderlo.')");
            $stmt3->bind_param("ii", $id_doc, $id_usuario);
            $stmt3->execute();

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Documento liberado correctamente."];
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

}
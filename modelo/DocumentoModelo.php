<?php
require_once __DIR__ . "/../librerias/conexion.php";

class DocumentoModelo {

    public function listarRecibidos($id_usuario, $cargos_ids, $areas_ids, $id_rol, $prog_ids) {
        global $conn;
        
        $sql = "SELECT d.id_documento, d.codigo_documento, d.asunto, d.fecha_emision, 
                       IFNULL(CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario), 'Sistema / Sin Remitente') as remitente, 
                       der.id_derivacion,
                       der.tipo_destino,
                       (SELECT ruta_archivo FROM documento_adjuntos WHERE id_documento = d.id_documento ORDER BY id_adjunto DESC LIMIT 1) as ultimo_archivo,
                       (SELECT tipo FROM documento_adjuntos WHERE id_documento = d.id_documento ORDER BY id_adjunto DESC LIMIT 1) as tipo_archivo
                FROM documento d
                INNER JOIN documento_derivacion der ON d.id_documento = der.id_documento
                LEFT JOIN usuario u ON d.id_usuario_emisor = u.id_usuario
                WHERE der.estado = 'pendiente' 
                AND (
                    (der.tipo_destino = 'usuario'  AND der.id_destino = ?) 
                    OR 
                    (der.tipo_destino = 'cargo'    AND FIND_IN_SET(der.id_destino, ?) > 0)
                    OR
                    (der.tipo_destino = 'area'     AND FIND_IN_SET(der.id_destino, ?) > 0)
                    OR
                    (der.tipo_destino = 'rol'      AND der.id_destino = ?)
                    OR
                    (der.tipo_destino = 'programa' AND FIND_IN_SET(der.id_destino, ?) > 0)
                )
                ORDER BY d.id_documento DESC";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) return [];
        
        $stmt->bind_param("issis", $id_usuario, $cargos_ids, $areas_ids, $id_rol, $prog_ids);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function derivarDocumento($id_doc, $id_der_actual, $tipo_destino, $id_destino, $obs, $id_user_sesion, $archivo_anexo = null) {
        global $conn;
        $conn->begin_transaction();

        try {
            $stmt1 = $conn->prepare("UPDATE documento_derivacion SET estado = 'derivado' WHERE id_derivacion = ?");
            $stmt1->bind_param("i", $id_der_actual);
            $stmt1->execute();

            $stmt2 = $conn->prepare("INSERT INTO documento_derivacion (id_documento, tipo_destino, id_destino, estado) VALUES (?, ?, ?, 'pendiente')");
            $stmt2->bind_param("isi", $id_doc, $tipo_destino, $id_destino);
            $stmt2->execute();

            if ($archivo_anexo !== null && $archivo_anexo['error'] === UPLOAD_ERR_OK) {
                $directorio_destino = __DIR__ . "/../uploads/anexos/";
                if (!file_exists($directorio_destino)) mkdir($directorio_destino, 0777, true);

                $nombre_original = $archivo_anexo['name'];
                $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
                $nuevo_nombre = "anexo_" . time() . "_" . rand(1000, 9999) . "." . $extension;
                
                if (move_uploaded_file($archivo_anexo['tmp_name'], $directorio_destino . $nuevo_nombre)) {
                    $ruta_bd = "uploads/anexos/" . $nuevo_nombre;
                    $stmtAdj = $conn->prepare("INSERT INTO documento_adjuntos (id_documento, nombre, tipo, ruta_archivo, nombre_original, extension, peso) VALUES (?, ?, 'anexo', ?, ?, ?, ?)");
                    $stmtAdj->bind_param("issssi", $id_doc, $nuevo_nombre, $ruta_bd, $nombre_original, $extension, $archivo_anexo['size']);
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

        // Procesar archivo
        if ($archivo_final !== null && isset($archivo_final['tmp_name']) && $archivo_final['error'] === UPLOAD_ERR_OK) {
            
            // Ruta absoluta usando dirname para subir un nivel desde 'modelo/' hacia la raíz
            $directorio_raiz = dirname(__DIR__); 
            $directorio_final = $directorio_raiz . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "finales" . DIRECTORY_SEPARATOR;

            if (!file_exists($directorio_final)) {
                mkdir($directorio_final, 0777, true);
            }

            $extension = strtolower(pathinfo($archivo_final['name'], PATHINFO_EXTENSION));
            $nombre_archivo = "FINAL_" . $id_doc . "_" . time() . "." . $extension;
            $ruta_destino = $directorio_final . $nombre_archivo;

            if (move_uploaded_file($archivo_final['tmp_name'], $ruta_destino)) {
                $ruta_bd = "uploads/finales/" . $nombre_archivo;
            }
        }

        // 1. Insertar en documento_archivo
        $stmt1 = $conn->prepare("INSERT INTO documento_archivo (id_documento, fecha_archivado, mensaje, id_usuario, ruta_archivo_final) VALUES (?, NOW(), ?, ?, ?)");
        $stmt1->bind_param("isis", $id_doc, $mensaje, $id_user_sesion, $ruta_bd);
        $stmt1->execute();

        // 2. Marcar documento como archivado
        $stmt2 = $conn->prepare("UPDATE documento SET estado = 'archivado' WHERE id_documento = ?");
        $stmt2->bind_param("i", $id_doc);
        $stmt2->execute();

        // 3. Finalizar derivaciones vigentes
        $stmt3 = $conn->prepare("UPDATE documento_derivacion SET estado = 'finalizado' WHERE id_documento = ? AND estado != 'finalizado'");
        $stmt3->bind_param("i", $id_doc);
        $stmt3->execute();

        // 4. Historial con el mensaje personalizado
        $stmt4 = $conn->prepare("INSERT INTO documento_historial (id_documento, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'archivado', ?)");
        $observacion_historial = "ARCHIVO FINAL: " . $mensaje;
        $stmt4->bind_param("iis", $id_doc, $id_user_sesion, $observacion_historial);
        $stmt4->execute();

        $conn->commit();
        return ["status" => "ok", "mensaje" => "Expediente finalizado y enviado al archivo."];

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
        // CORRECCIÓN: Filtramos documentos que NO estén en la tabla de archivo
        $sql = "SELECT d.id_documento, d.codigo_documento, d.asunto, d.fecha_emision, 
                       IFNULL(CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario), 'Sistema') as remitente,
                       (SELECT id_derivacion FROM documento_derivacion WHERE id_documento = d.id_documento ORDER BY id_derivacion DESC LIMIT 1) as id_derivacion,
                       (SELECT tipo_destino FROM documento_derivacion WHERE id_documento = d.id_documento ORDER BY id_derivacion DESC LIMIT 1) as tipo_destino,
                       (SELECT ruta_archivo FROM documento_adjuntos WHERE id_documento = d.id_documento ORDER BY id_adjunto DESC LIMIT 1) as ultimo_archivo
                FROM documento d
                INNER JOIN documento_historial h ON d.id_documento = h.id_documento
                LEFT JOIN usuario u ON d.id_usuario_emisor = u.id_usuario
                WHERE h.id_usuario = ? 
                  AND h.tipo_evento = 'atendido'
                  AND d.id_documento NOT IN (SELECT id_documento FROM documento_archivo)
                GROUP BY d.id_documento
                ORDER BY h.fecha DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function atenderDocumento($id_doc, $id_derivacion, $mensaje, $id_usuario) {
        global $conn;
        $conn->begin_transaction();
        try {
            $stmt1 = $conn->prepare("UPDATE documento SET estado = 'en_proceso' WHERE id_documento = ?");
            $stmt1->bind_param("i", $id_doc);
            $stmt1->execute();

            $stmt_der = $conn->prepare("UPDATE documento_derivacion SET estado = 'atendido' WHERE id_derivacion = ?");
            $stmt_der->bind_param("i", $id_derivacion);
            $stmt_der->execute();

            $stmt2 = $conn->prepare("INSERT INTO documento_historial (id_documento, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'atendido', ?)");
            $stmt2->bind_param("iis", $id_doc, $id_usuario, $mensaje);
            $stmt2->execute();

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Se ha registrado el avance del trámite."];
        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    public function obtenerSeguimiento($id_documento) {
    global $conn;

    // 1. Consultar el historial general incluyendo la ruta del archivo si es un archivado
    $stmt = $conn->prepare("SELECT 
        h.tipo_evento, h.observacion, h.fecha, u.nombres_usuario,
        a.ruta_archivo_final as archivo_historial
    FROM documento_historial h
    LEFT JOIN usuario u ON u.id_usuario = h.id_usuario
    LEFT JOIN documento_archivo a ON (h.id_documento = a.id_documento AND h.tipo_evento = 'archivado')
    WHERE h.id_documento = ?
    ORDER BY h.fecha ASC");

    $stmt->bind_param("i", $id_documento);
    $stmt->execute();
    $historial = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // 2. Consultar derivaciones y buscar si tienen un adjunto específico
    $stmt2 = $conn->prepare("SELECT 
        d.id_derivacion, d.tipo_destino, d.id_destino, d.estado, d.fecha_envio,
        CASE 
            WHEN d.tipo_destino = 'usuario' THEN u.nombres_usuario
            WHEN d.tipo_destino = 'area' THEN ar.nombre_area
            WHEN d.tipo_destino = 'cargo' THEN c.cargo
            WHEN d.tipo_destino = 'rol' THEN r.rol
            WHEN d.tipo_destino = 'programa' THEN p.programa_estudio
            ELSE 'Desconocido'
        END AS destino_nombre,
        /* Buscamos el archivo que se subió en el momento exacto de esta derivación */
        (SELECT ruta_archivo FROM documento_adjuntos 
         WHERE id_documento = d.id_documento 
         AND ABS(TIMESTAMPDIFF(SECOND, fecha_subida, d.fecha_envio)) < 5 
         LIMIT 1) as archivo_adjunto
    FROM documento_derivacion d
    LEFT JOIN usuario u ON (d.tipo_destino = 'usuario' AND d.id_destino = u.id_usuario)
    LEFT JOIN area ar ON (d.tipo_destino = 'area' AND d.id_destino = ar.id_area)
    LEFT JOIN cargo c ON (d.tipo_destino = 'cargo' AND d.id_destino = c.id_cargo)
    LEFT JOIN rol r ON (d.tipo_destino = 'rol' AND d.id_destino = r.id_rol)
    LEFT JOIN programa_estudio p ON (d.tipo_destino = 'programa' AND d.id_destino = p.id_programa_estudio)
    WHERE d.id_documento = ?
    ORDER BY d.fecha_envio ASC");

    $stmt2->bind_param("i", $id_documento);
    $stmt2->execute();
    $derivaciones = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

    return [
        "historial" => $historial,
        "derivaciones" => $derivaciones
    ];
}
public function registrarDocumentoInterno($post, $files, $id_usuario_emisor) {
    global $conn;
    $conn->begin_transaction();
    try {
        // 1. Obtener ID de tipo documento (Podrías tener uno genérico o buscar por nombre)
        // Por ahora usemos 1 o busca el tipo 'OFICIO'/'INTERNO'
        $id_tipo = 2; 

        // 2. Insertar Documento
        $stmt = $conn->prepare("INSERT INTO documento (id_tipo, codigo_documento, asunto, descripcion, fecha_emision, id_usuario_emisor, estado) VALUES (?, ?, ?, ?, NOW(), ?, 'enviado')");
        $stmt->bind_param("isssi", $id_tipo, $post['codigo_documento'], $post['asunto'], $post['descripcion'], $id_usuario_emisor);
        $stmt->execute();
        $id_doc = $conn->insert_id;

        // 3. Subir Archivo Principal
        if (isset($files['url_doc']) && $files['url_doc']['error'] === UPLOAD_ERR_OK) {
            $dir = dirname(__DIR__) . "/uploads/internos/";
            if (!file_exists($dir)) mkdir($dir, 0777, true);
            
            $ext = pathinfo($files['url_doc']['name'], PATHINFO_EXTENSION);
            $nombre_archivo = "INT_" . time() . "_" . $id_doc . "." . $ext;
            
            if (move_uploaded_file($files['url_doc']['tmp_name'], $dir . $nombre_archivo)) {
                $ruta_bd = "uploads/internos/" . $nombre_archivo;
                $stmtAdj = $conn->prepare("INSERT INTO documento_adjuntos (id_documento, nombre, tipo, ruta_archivo, nombre_original, extension, peso) VALUES (?, ?, 'pdf', ?, ?, ?, ?)");
                $stmtAdj->bind_param("issssi", $id_doc, $nombre_archivo, $ruta_bd, $files['url_doc']['name'], $ext, $files['url_doc']['size']);
                $stmtAdj->execute();
            }
        }

        // 4. Crear Derivación Inicial al destino seleccionado
        $stmtDer = $conn->prepare("INSERT INTO documento_derivacion (id_documento, tipo_destino, id_destino, estado, fecha_envio) VALUES (?, ?, ?, 'pendiente', NOW())");
        $stmtDer->bind_param("isi", $id_doc, $post['tipo_envio'], $post['id_destino']);
        $stmtDer->execute();

        // 5. Historial de creación
        $stmtHist = $conn->prepare("INSERT INTO documento_historial (id_documento, id_usuario, tipo_evento, observacion) VALUES (?, ?, 'creado', 'Documento interno registrado y enviado')");
        $stmtHist->bind_param("ii", $id_doc, $id_usuario_emisor);
        $stmtHist->execute();

        $conn->commit();
        return ["status" => "ok", "mensaje" => "Documento enviado correctamente con código: " . $post['codigo_documento']];
    } catch (Exception $e) {
        $conn->rollback();
        return ["status" => "error", "mensaje" => $e->getMessage()];
    }
}
}
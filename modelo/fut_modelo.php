<?php
require_once __DIR__ . "/../librerias/conexion.php";

class FutModelo {

    public function guardar(){

        global $conn;

        if(!isset($_SESSION['id_usuario'])){
            return ["status"=>false, "msg"=>"Sesión no iniciada"];
        }

        $id_usuario = $_SESSION['id_usuario'];
        $codigo = "FUT-" . date('YmdHis');

        $asunto = $_POST['asunto'];
        $descripcion = $_POST['descripcion'];

        $lugar = "Pacarán";
        $id_area_origen = 12; //  área usuaria

        //  OBTENER ID TIPO FUT
        $stmtTipo = $conn->prepare("SELECT id_tipo FROM tipo_documento WHERE nombre = 'FUT' LIMIT 1");
        $stmtTipo->execute();
        $resTipo = $stmtTipo->get_result();

        if(!$rowTipo = $resTipo->fetch_assoc()){
            return ["status"=>false, "msg"=>"No existe tipo_documento FUT"];
        }

        $id_tipo = $rowTipo['id_tipo'];


        $stmt = $conn->prepare("INSERT INTO documento (
            id_tipo,
            codigo_documento,
            asunto,
            descripcion,
            fecha_emision,
            lugar,
            id_usuario_emisor,
            id_area_origen,
            estado
        ) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, 'enviado')");

        $stmt->bind_param("issssii",
            $id_tipo,
            $codigo,
            $asunto,
            $descripcion,
            $lugar,
            $id_usuario,
            $id_area_origen
        );

        if(!$stmt->execute()){
            return ["status"=>false, "msg"=>$stmt->error];
        }

        $id_documento = $conn->insert_id;

        //  ARCHIVO
        if (!empty($_FILES['doc_anexado']['name'])) {

            $nombreOriginal = $_FILES['doc_anexado']['name'];
            $tmp = $_FILES['doc_anexado']['tmp_name'];
            $peso = $_FILES['doc_anexado']['size'];

            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

            if ($extension != 'pdf') {
                return ["status"=>false, "msg"=>"Solo PDF"];
            }

            $nuevoNombre = "fut_" . time() . "." . $extension;

            $rutaFisica = __DIR__ . "/../uploads/fut/" . $nuevoNombre;
            $rutaBD = "uploads/fut/" . $nuevoNombre;

            if (!move_uploaded_file($tmp, $rutaFisica)) {
                return ["status"=>false, "msg"=>"Error al subir archivo"];
            }

            $stmtAdj = $conn->prepare("INSERT INTO documento_adjuntos (
                id_documento,
                nombre,
                tipo,
                ruta_archivo,
                nombre_original,
                extension,
                peso
            ) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $tipo = "pdf";

            $stmtAdj->bind_param("isssssi",
                $id_documento,
                $nuevoNombre,
                $tipo,
                $rutaBD,
                $nombreOriginal,
                $extension,
                $peso
            );

            $stmtAdj->execute();
        }

        // HISTORIAL - CREACIÓN
        $evento = "creado";
        $obs = "FUT registrado";

        $stmtHist = $conn->prepare("INSERT INTO documento_historial (
            id_documento,
            id_usuario,
            tipo_evento,
            observacion
        ) VALUES (?, ?, ?, ?)");

        $stmtHist->bind_param("iiss",
            $id_documento,
            $id_usuario,
            $evento,
            $obs
        );

        $stmtHist->execute();

        // DERIVACIÓN AUTOMÁTICA
        $tipo_destino = "cargo";
        $id_destino = 6; // mesa de partes

        $stmtDer = $conn->prepare("INSERT INTO documento_derivacion (
            id_documento,
            tipo_destino,
            id_destino,
            estado
        ) VALUES (?, ?, ?, 'pendiente')");

        $stmtDer->bind_param("isi",
            $id_documento,
            $tipo_destino,
            $id_destino
        );

        $stmtDer->execute();

        $evento2 = "enviado";
        $obs2 = "FUT enviado a mesa de partes";

        $stmtHist2 = $conn->prepare("INSERT INTO documento_historial (
            id_documento,
            id_usuario,
            tipo_evento,
            tipo_destino,
            id_destino,
            observacion
        ) VALUES (?, ?, ?, ?, ?, ?)");

        $stmtHist2->bind_param("iissis",
            $id_documento,
            $id_usuario,
            $evento2,
            $tipo_destino,
            $id_destino,
            $obs2
        );

        $stmtHist2->execute();

        return ["status"=>true];
    }

    public function listarMisFuts(){

    global $conn;

    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("SELECT 
        id_documento,
        codigo_documento,
        asunto,
        estado,
        fecha_emision
    FROM documento 
    WHERE id_usuario_emisor = ?
    ORDER BY id_documento DESC");

    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $result = $stmt->get_result();

    $data = [];

    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }

    return $data;
}
public function historial($id_documento) {
    global $conn;

    // 1. Obtener Historial (Eventos) + Archivo Final si existe
    $stmt = $conn->prepare("SELECT 
        h.tipo_evento, h.observacion, h.fecha, u.nombres_usuario,
        a.ruta_archivo_final
    FROM documento_historial h
    LEFT JOIN usuario u ON u.id_usuario = h.id_usuario
    LEFT JOIN documento_archivo a ON (h.id_documento = a.id_documento AND h.tipo_evento = 'archivado')
    WHERE h.id_documento = ?
    ORDER BY h.fecha ASC");

    $stmt->bind_param("i", $id_documento);
    $stmt->execute();
    $historial = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // 2. Obtener Derivaciones + Anexos subidos en ese momento
    $stmt2 = $conn->prepare("SELECT 
        d.tipo_destino, d.id_destino, d.estado, d.fecha_envio,
        CASE 
            WHEN d.tipo_destino = 'usuario' THEN us.nombres_usuario
            WHEN d.tipo_destino = 'area' THEN ar.nombre_area
            WHEN d.tipo_destino = 'cargo' THEN c.cargo
            WHEN d.tipo_destino = 'rol' THEN r.rol
            WHEN d.tipo_destino = 'programa' THEN p.programa_estudio
            ELSE 'Desconocido'
        END AS destino_nombre,
        (SELECT ruta_archivo FROM documento_adjuntos 
         WHERE id_documento = d.id_documento 
         AND ABS(TIMESTAMPDIFF(SECOND, fecha_subida, d.fecha_envio)) < 10 
         LIMIT 1) as ruta_anexo
    FROM documento_derivacion d
    LEFT JOIN usuario us ON (d.tipo_destino = 'usuario' AND d.id_destino = us.id_usuario)
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
}


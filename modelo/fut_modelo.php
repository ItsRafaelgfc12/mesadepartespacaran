<?php
require_once __DIR__ . "/../librerias/conexion.php";

class FutModelo {

    public function guardar(){

        global $conn;

        if(!isset($_SESSION['id_usuario'])){
            return ["status"=>false, "msg"=>"Sesión no iniciada"];
        }

        $id_usuario = $_SESSION['id_usuario'];
        $id_tipo = 1; // FUT
        $codigo = "FUT-" . date('YmdHis');

        $asunto = $_POST['asunto'];
        $descripcion = $_POST['descripcion'];
        $fecha = !empty($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
        $lugar = "Pacarán";

        // INSERT DOCUMENTO
        $stmt = $conn->prepare("INSERT INTO documento (
            id_tipo,
            codigo_documento,
            asunto,
            descripcion,
            fecha_emision,
            lugar,
            id_usuario_emisor,
            estado
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'enviado')");

        $stmt->bind_param("isssssi",
            $id_tipo,
            $codigo,
            $asunto,
            $descripcion,
            $fecha,
            $lugar,
            $id_usuario
        );

        if(!$stmt->execute()){
            return ["status"=>false, "msg"=>$stmt->error];
        }

        $id_documento = $conn->insert_id;

        // 📎 ARCHIVO
        if (!empty($_FILES['doc_anexado']['name'])) {

            $nombreOriginal = $_FILES['doc_anexado']['name'];
            $tmp = $_FILES['doc_anexado']['tmp_name'];
            $peso = $_FILES['doc_anexado']['size'];

            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

            if ($extension != 'pdf') {
                return ["status"=>false, "msg"=>"Solo PDF"];
            }

            $nuevoNombre = "fut_" . time() . "." . $extension;

            // RUTA REAL
            $rutaFisica = __DIR__ . "/../uploads/fut/" . $nuevoNombre;

            // RUTA PARA BD
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

        // HISTORIAL
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

        return ["status"=>true];
    }
}
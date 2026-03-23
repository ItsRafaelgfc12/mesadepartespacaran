<?php
require_once __DIR__ . "/../librerias/conexion.php";

class ProgramaController {

    // LISTAR (para uso interno PHP si lo necesitas)
    public function listar() {
        global $conn;

        $sql = "SELECT * FROM programa_estudio";
        return $conn->query($sql);
    }

    // LISTAR EN FORMATO JSON (para AJAX)
    public function listarJSON() {
        global $conn;

        $sql = "SELECT * FROM programa_estudio";
        $resultado = $conn->query($sql);

        $data = [];

        while ($row = $resultado->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }

    // GUARDAR (INSERT / UPDATE)
    public function guardar() {
        global $conn;

        $id = $_POST['id_programa'] ?? "";
        $nombre = $_POST['nombre'] ?? "";
        $descripcion = $_POST['descripcion'] ?? "";
        $estado = ($_POST['estado'] == 'activo') ? 1 : 0;

        if ($id == "") {
            // INSERT
            $stmt = $conn->prepare("INSERT INTO programa_estudio (programa_estudio, descripcion, estado) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $nombre, $descripcion, $estado);
        } else {
            // UPDATE
            $stmt = $conn->prepare("UPDATE programa_estudio SET programa_estudio=?, descripcion=?, estado=? WHERE id_programa_estudio=?");
            $stmt->bind_param("ssii", $nombre, $descripcion, $estado, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode([
                "status" => "error",
                "msg" => $stmt->error
            ]);
        }
    }

    // ELIMINAR
    public function eliminar() {
        global $conn;

        $id = $_GET['id'] ?? 0;

        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM programa_estudio WHERE id_programa_estudio=?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["status" => "ok"]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "msg" => $stmt->error
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "msg" => "ID inválido"
            ]);
        }
    }
}
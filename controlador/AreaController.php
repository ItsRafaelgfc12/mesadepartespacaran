<?php
require_once __DIR__ . "/../librerias/conexion.php";

class AreaController {

    public function listarJSON() {
        global $conn;

        $sql = "SELECT * FROM area";
        $result = $conn->query($sql);

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function guardar() {
        global $conn;

        $id = $_POST['id_area'] ?? "";
        $nombre = $_POST['nombre'] ?? "";
        $descripcion = $_POST['descripcion'] ?? "";
        $estado = ($_POST['estado'] == 'activo') ? 1 : 0;

        if ($id == "") {
            // INSERT
            $stmt = $conn->prepare("INSERT INTO area (nombre_area, descripcion, estado) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $nombre, $descripcion, $estado);
        } else {
            // UPDATE
            $stmt = $conn->prepare("UPDATE area SET nombre_area=?, descripcion=?, estado=? WHERE id_area=?");
            $stmt->bind_param("ssii", $nombre, $descripcion, $estado, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "msg" => $stmt->error]);
        }
    }

    public function eliminar() {
        global $conn;

        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM area WHERE id_area=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(["status" => "ok"]);
    }
}
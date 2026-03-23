<?php
require_once __DIR__ . "/../librerias/conexion.php";

class RolController {

    // LISTAR PARA AJAX
    public function listarJSON() {
        global $conn;

        $sql = "SELECT * FROM rol";
        $result = $conn->query($sql);

        $data = [];

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        echo json_encode($data);
    }

    // GUARDAR (INSERT / UPDATE)
    public function guardar() {
        global $conn;

        $id = $_POST['id_rol'] ?? "";
        $nombre = $_POST['nombre'] ?? "";
        $descripcion = $_POST['descripcion'] ?? "";
        $estado = ($_POST['estado'] == 'activo') ? 1 : 0;

        if ($id == "") {
            $stmt = $conn->prepare("INSERT INTO rol (rol, descripcion, estado) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $nombre, $descripcion, $estado);
        } else {
            $stmt = $conn->prepare("UPDATE rol SET rol=?, descripcion=?, estado=? WHERE id_rol=?");
            $stmt->bind_param("ssii", $nombre, $descripcion, $estado, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }

    // ELIMINAR
    public function eliminar() {
        global $conn;

        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM rol WHERE id_rol=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(["status" => "ok"]);
    }
}
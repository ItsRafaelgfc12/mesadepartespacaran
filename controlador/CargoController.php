<?php
require_once __DIR__ . "/../librerias/conexion.php";

class CargoController {

    // LISTAR NORMAL (por si lo usas en PHP)
    public function listar() {
        global $conn;
        return $conn->query("SELECT * FROM cargo");
    }

    // LISTAR JSON
    public function listarJSON() {
    global $conn;

    $sql = "SELECT 
                c.id_cargo,
                c.cargo,
                c.id_area,
                c.descripcion,
                c.estado,
                a.nombre_area
            FROM cargo c
            LEFT JOIN area a ON c.id_area = a.id_area";

    $result = $conn->query($sql);

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}

    // GUARDAR (INSERT / UPDATE)
    public function guardar() {
    global $conn;

    $id = $_POST['id_cargo'] ?? "";
    $nombre = $_POST['nombre'] ?? "";
    $descripcion = $_POST['descripcion'] ?? "";
    $id_area = $_POST['id_area'] ?? null;
    $estado = ($_POST['estado'] == 'activo') ? 1 : 0;

    if ($id == "") {
        $stmt = $conn->prepare("INSERT INTO cargo (cargo, descripcion, id_area, estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nombre, $descripcion, $id_area, $estado);
    } else {
        $stmt = $conn->prepare("UPDATE cargo SET cargo=?, descripcion=?, id_area=?, estado=? WHERE id_cargo=?");
        $stmt->bind_param("ssiii", $nombre, $descripcion, $id_area, $estado, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "error", "msg" => $stmt->error]);
    }
}

    // ELIMINAR
    public function eliminar() {
        global $conn;

        $id = $_GET['id'] ?? 0;

        $stmt = $conn->prepare("DELETE FROM cargo WHERE id_cargo=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(["status" => "ok"]);
    }
}
<?php
session_start();
require_once __DIR__ . "/../librerias/conexion.php";

$accion = $_GET['accion'] ?? '';
$tipo = $_GET['tipo'] ?? '';

if ($accion === 'listar_destinos') {
    $data = [];
    
    switch ($tipo) {
        case 'area':
            $sql = "SELECT id_area as id, nombre_area as nombre FROM area ORDER BY nombre_area ASC";
            break;
        case 'cargo':
            $sql = "SELECT id_cargo as id, cargo as nombre FROM cargo ORDER BY cargo ASC";
            break;
        case 'usuario':
            $sql = "SELECT id_usuario as id, CONCAT(nombres_usuario, ' ', apellidos_usuario) as nombre FROM usuario WHERE id_estado = 1 ORDER BY nombres_usuario ASC";
            break;
        case 'rol':
            $sql = "SELECT id_rol as id, rol as nombre FROM rol ORDER BY rol ASC";
            break;
        default:
            echo json_encode([]);
            exit;
    }

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}
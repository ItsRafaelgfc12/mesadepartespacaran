<?php
session_start();
require_once __DIR__ . "/../librerias/conexion.php";

$accion = $_GET['accion'] ?? '';
$tipo = $_GET['tipo'] ?? '';

header('Content-Type: application/json');

switch ($accion) {
    
    // 1. LISTAR DESTINOS (Para derivaciones y envíos internos)
    case 'listar_destinos':
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
            case 'programa': // Añadido para que coincida con tu vista
                $sql = "SELECT id_programa_estudio as id, programa_estudio as nombre FROM programa_estudio ORDER BY programa_estudio ASC";
                break;
            default:
                echo json_encode([]);
                exit;
        }

        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        echo json_encode($data);
        break;

    // 2. LISTAR TIPOS DE DOCUMENTO (Solo internos, ID > 1)
    case 'listar_tipos_doc_internos':
        $data = [];
        // Filtramos para que no aparezca el FUT (ID 1)
        $sql = "SELECT id_tipo, nombre FROM tipo_documento WHERE id_tipo > 1 ORDER BY nombre ASC";
        
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        echo json_encode($data);
        break;

    default:
        echo json_encode(["error" => "Acción no reconocida"]);
        break;
}
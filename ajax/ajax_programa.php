<?php
require_once __DIR__ . "/../controlador/ProgramaController.php";

$controller = new ProgramaController();

$accion = $_GET['accion'] ?? '';

switch ($accion) {

    case 'listar':
        $controller->listarJSON();
        break;

    case 'guardar':
        $controller->guardar();
        break;

    case 'eliminar':
        $controller->eliminar();
        break;

    default:
        echo json_encode([
            "status" => "error",
            "msg" => "Acción no válida"
        ]);
        break;
}
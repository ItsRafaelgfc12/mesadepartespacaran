<?php
require_once __DIR__ . "/../controlador/CargoController.php";

$controller = new CargoController();

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
}
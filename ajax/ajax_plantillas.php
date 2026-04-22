<?php
require_once __DIR__ . "/../controlador/PlantillaControlador.php";
header('Content-Type: application/json');

$controlador = new PlantillaControlador();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'subir':
        echo json_encode($controlador->subirPlantilla($_POST, $_FILES));
        break;
        
    case 'listar':
        echo json_encode($controlador->listarPlantillas());
        break;
        
    case 'eliminar':
        echo json_encode($controlador->eliminarPlantilla($_POST['id']));
        break;
        
    case 'obtener':
        echo json_encode($controlador->obtenerPlantilla($_GET['id']));
        break;
        
    case 'editar':
        echo json_encode($controlador->editarPlantilla($_POST, $_FILES));
        break;
        
    default:
        echo json_encode(["status" => "error", "mensaje" => "Acción no reconocida."]);
        break;
}
?>
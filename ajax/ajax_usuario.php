<?php
require_once __DIR__ . "/../controlador/UsuarioControlador.php";
header('Content-Type: application/json');

$controlador = new UsuarioControlador();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    // 1. Exclusivo para rellenar el formulario FUT
    case 'DatosPersonalesFut':
        echo json_encode($controlador->obtenerDatosPersonalesFut());
        break;

    // 2. Exclusivo para la tabla del Administrador
    case 'ListarUsuarios':
        echo json_encode($controlador->listarUsuariosAdmin());
        break;

    // Para Registrar / Editar Usuario
    case 'RegistrarUsuario':
        echo json_encode($controlador->registrarUsuario($_POST));
        break;
    
    case 'ObtenerOpciones':
        echo json_encode($controlador->obtenerOpciones());
        break;

    case 'ObtenerUsuario':
        echo json_encode($controlador->obtenerUsuario($_GET['id']));
        break;

    case 'EliminarUsuario':
        echo json_encode($controlador->eliminarUsuario($_POST['id_usuario']));
        break;

    default:
        echo json_encode(["status" => "error", "mensaje" => "Acción no reconocida."]);
        break;
}
?>
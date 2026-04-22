<?php
require_once __DIR__ . "/../controlador/ExpedienteControlador.php";
header('Content-Type: application/json');

$controlador = new ExpedienteControlador();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear_expediente':
        echo json_encode($controlador->crearExpediente($_POST, $_FILES));
        break;
    case 'listar_mis_expedientes':
        echo json_encode($controlador->listarMisExpedientes());
        break;
    case 'obtener_detalles':
        echo json_encode($controlador->obtenerDetallesExpediente($_GET['id']));
        break;

    case 'subir_version':
        echo json_encode($controlador->subirVersionExpediente($_POST, $_FILES));
        break;
    case 'obtener_accesos':
        echo json_encode($controlador->obtenerAccesosExpediente($_GET['id']));
        break;

    case 'agregar_acceso':
        echo json_encode($controlador->agregarAccesoExpediente($_POST));
        break;

    case 'revocar_acceso':
        echo json_encode($controlador->revocarAccesoExpediente($_POST));
        break;
    case 'listar_solicitudes':
        echo json_encode($controlador->listarSolicitudesAcceso($_GET['id']));
        break;
    case 'procesar_solicitud':
        echo json_encode($controlador->procesarSolicitudAcceso($_POST));
        break;
    case 'listar_publicos':
        echo json_encode($controlador->listarPublicos());
        break;

    case 'enviar_solicitud_acceso':
        echo json_encode($controlador->enviarSolicitudAcceso($_POST));
        break;
    case 'editar_expediente':
        echo json_encode($controlador->editarExpediente($_POST));
        break;
    default:
        echo json_encode(["status" => "error", "mensaje" => "Acción no reconocida en Expedientes."]);
        break;
}
?>
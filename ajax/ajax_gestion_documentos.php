<?php
require_once __DIR__ . "/../controlador/DocumentoControlador.php";

header('Content-Type: application/json');

$controlador = new DocumentoControlador();
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'listar_recibidos':
        echo json_encode($controlador->listarRecibidos());
        break;

    case 'procesar_derivacion':
        echo json_encode($controlador->procesarDerivacion($_POST, $_FILES));
        break;

    case 'atender_documento':
        echo json_encode($controlador->atenderDocumento($_POST));
        break;

    case 'obtener_seguimiento':
        echo json_encode($controlador->obtenerSeguimiento($_GET['id']));
        break;

    case 'listar_atendidos':
        echo json_encode($controlador->listarAtendidos());
        break;

    // Solo un case para archivar, pasando POST y FILES
    case 'archivar_documento':
        echo json_encode($controlador->archivarDocumento($_POST, $_FILES));
        break;

    case 'listar_archivados':
        echo json_encode($controlador->listarArchivados());
        break;
    case 'registrar_documento_interno':
        echo json_encode($controlador->registrarDocumentoInterno($_POST, $_FILES));
        break;
    case 'listar_historial_enviados':
        echo json_encode($controlador->listarHistorialEnviados());
        break;
    case 'liberar_documento':
        echo json_encode($controlador->liberarDocumento($_POST));
        break;
    default:
        echo json_encode(["error" => "Acción no reconocida"]);
        break;
}
?>
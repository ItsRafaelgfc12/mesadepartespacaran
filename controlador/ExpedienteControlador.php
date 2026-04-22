<?php
require_once __DIR__ . "/../modelo/ExpedienteModelo.php";

class ExpedienteControlador {
    
    private $modelo;

    public function __construct() {
        $this->modelo = new ExpedienteModelo();
    }

    public function crearExpediente($post, $files) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;

        if ($id_usuario == 0) {
            return ["status" => "error", "mensaje" => "Sesión expirada o inválida."];
        }

        // Validación básica de campos vacíos
        if (empty(trim($post['codigo_expediente'])) || empty(trim($post['asunto']))) {
            return ["status" => "error", "mensaje" => "El código y el asunto son obligatorios."];
        }

        return $this->modelo->crearExpediente($post, $files, $id_usuario);
    }
    public function listarMisExpedientes() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        
        $id_rol     = $_SESSION['id_rol'] ?? 0;
        $cargos_ids = $_SESSION['cargos_ids'] ?? '0';
        $areas_ids  = $_SESSION['areas_ids'] ?? '0';

        if ($id_usuario == 0) return ["error" => "Sesión inválida"];

        $datos = $this->modelo->listarMisExpedientes($id_usuario, $id_rol, $cargos_ids, $areas_ids);
        return ["data" => $datos];
    }
    public function obtenerDetallesExpediente($id_expediente) {
        return $this->modelo->obtenerDetallesExpediente($id_expediente);
    }

    public function subirVersionExpediente($post, $files) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        if ($id_usuario == 0) return ["status" => "error", "mensaje" => "Sesión inválida"];

        if (!isset($files['archivo']) || $files['archivo']['error'] !== UPLOAD_ERR_OK) {
            return ["status" => "error", "mensaje" => "Debe seleccionar un archivo válido."];
        }

        return $this->modelo->subirVersionExpediente($post, $files['archivo'], $id_usuario);
    }

    public function obtenerAccesosExpediente($id_expediente) {
        return $this->modelo->obtenerAccesosExpediente($id_expediente);
    }

    public function agregarAccesoExpediente($post) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        if ($id_usuario == 0) return ["status" => "error", "mensaje" => "Sesión inválida"];

        return $this->modelo->agregarAccesoExpediente($post, $id_usuario);
    }

    public function revocarAccesoExpediente($post) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        if ($id_usuario == 0) return ["status" => "error", "mensaje" => "Sesión inválida"];

        return $this->modelo->revocarAccesoExpediente($post['id_acceso'], $post['id_expediente'], $id_usuario);
    }
    public function listarSolicitudesAcceso($id_expediente) {
        return $this->modelo->listarSolicitudesAcceso($id_expediente);
    }
    public function procesarSolicitudAcceso($post) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_admin = $_SESSION['id_usuario'] ?? 0;
        return $this->modelo->procesarSolicitudAcceso($post['id_solicitud'], $post['id_expediente'], $post['estado'], $id_admin);
    }
    public function listarPublicos() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        $id_rol     = $_SESSION['id_rol'] ?? 0;
        $cargos_ids = $_SESSION['cargos_ids'] ?? '0';
        $areas_ids  = $_SESSION['areas_ids'] ?? '0';

        return ["data" => $this->modelo->listarPublicos($id_usuario, $id_rol, $areas_ids, $cargos_ids)];
    }

    public function enviarSolicitudAcceso($post) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        
        if ($id_usuario == 0) return ["status" => "error", "mensaje" => "Sesión inválida"];
        if (empty(trim($post['mensaje']))) return ["status" => "error", "mensaje" => "El motivo es obligatorio."];

        return $this->modelo->enviarSolicitudAcceso($post['id_expediente'], $id_usuario, $post['mensaje']);
    }
    public function editarExpediente($post) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_admin = $_SESSION['id_usuario'] ?? 0;
        
        if ($id_admin == 0) return ["status" => "error", "mensaje" => "Sesión inválida"];
        
        return $this->modelo->editarExpediente($post['id_expediente'], $post['asunto'], $post['estado'], $post['tipo'], $id_admin);
    }
}

?>
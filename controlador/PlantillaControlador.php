<?php
require_once __DIR__ . "/../modelo/PlantillaModelo.php";

class PlantillaControlador {
    
    private $modelo;

    public function __construct() {
        $this->modelo = new PlantillaModelo();
    }

    public function subirPlantilla($post, $files) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;

        if ($id_usuario == 0) return ["status" => "error", "mensaje" => "Tu sesión ha expirado."];
        if (empty(trim($post['titulo'])) || empty(trim($post['descripcion']))) {
            return ["status" => "error", "mensaje" => "El título y la descripción son obligatorios."];
        }
        if (!isset($files['imagen']) || $files['imagen']['error'] !== UPLOAD_ERR_OK) {
            return ["status" => "error", "mensaje" => "Debe subir una imagen de previsualización válida."];
        }
        if (!isset($files['archivo']) || $files['archivo']['error'] !== UPLOAD_ERR_OK) {
            return ["status" => "error", "mensaje" => "Debe subir el archivo de la plantilla."];
        }

        return $this->modelo->subirPlantilla($post, $files, $id_usuario);
    }

    public function listarPlantillas() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario_actual = $_SESSION['id_usuario'] ?? 0;
        $id_rol_actual = $_SESSION['id_rol'] ?? 0; // 1 = Administrador

        return [
            "data" => $this->modelo->listarPlantillas(),
            "id_usuario_actual" => $id_usuario_actual,
            "id_rol_actual" => $id_rol_actual
        ];
    }

    public function eliminarPlantilla($id_plantilla) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        $id_rol = $_SESSION['id_rol'] ?? 0;
        
        if ($id_usuario == 0) return ["status" => "error", "mensaje" => "Sesión expirada."];
        
        return $this->modelo->eliminarPlantilla($id_plantilla, $id_usuario, $id_rol);
    }

    public function obtenerPlantilla($id_plantilla) {
        $data = $this->modelo->obtenerPlantilla($id_plantilla);
        if ($data) {
            return ["status" => "ok", "data" => $data];
        }
        return ["status" => "error", "mensaje" => "Plantilla no encontrada."];
    }

    public function editarPlantilla($post, $files) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        $id_rol = $_SESSION['id_rol'] ?? 0;

        if ($id_usuario == 0) return ["status" => "error", "mensaje" => "Sesión expirada."];
        if (empty($post['id_plantilla']) || empty(trim($post['titulo']))) {
            return ["status" => "error", "mensaje" => "Faltan datos obligatorios."];
        }

        return $this->modelo->editarPlantilla($post, $files, $id_usuario, $id_rol);
    }
}
?>
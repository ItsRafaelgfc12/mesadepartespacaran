<?php
require_once __DIR__ . "/../modelo/UsuarioModelo.php";

class UsuarioControlador {
    
    private $modelo;

    public function __construct() {
        $this->modelo = new UsuarioModelo();
    }

    // Método 1: Para el FUT
    public function obtenerDatosPersonalesFut() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        
        if ($id_usuario == 0) {
            return ["status" => "error", "mensaje" => "Sesión no iniciada"];
        }

        return $this->modelo->obtenerDatosPersonalesFut($id_usuario);
    }

    // Método 2: Para Administrar Usuarios
    public function listarUsuariosAdmin() {
        return ["status" => "ok", "data" => $this->modelo->listarUsuariosAdmin()];
    }

    public function obtenerOpciones() {
        return ["status" => "ok", "data" => $this->modelo->obtenerListasDesplegables()];
    }

    public function registrarUsuario($post) {
        if (empty(trim($post['nombres_usuario'])) || empty(trim($post['email_per']))) {
            return ["status" => "error", "mensaje" => "Nombres y Correo son obligatorios."];
        }
        return $this->modelo->registrarUsuario($post);
    }
    public function obtenerUsuario($id) {
        return ["status" => "ok", "data" => $this->modelo->obtenerUsuario($id)];
    }

    public function eliminarUsuario($id) {
        return $this->modelo->eliminarUsuario($id);
    }
}
?>
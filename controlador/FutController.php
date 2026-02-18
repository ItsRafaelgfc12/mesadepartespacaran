<?php
    require_once "./modelo/UsuarioModel.php";

    class FutController {

        public function crear() {

    session_start();

    if (!isset($_SESSION['id_usuario'])) {
        header("Location: login.php");
        exit();
    }

    require_once "librerias/conexion.php";

    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    require_once "views/administrador/vistas/fut/crear.php";
}

    }
?>
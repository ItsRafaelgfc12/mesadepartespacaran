<?php
class UsuarioModel {

    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "tu_basedatos");
    }

    public function obtenerPorId($id) {

        $stmt = $this->conexion->prepare(
            "SELECT * FROM usuario WHERE id_usuario = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
}

?>
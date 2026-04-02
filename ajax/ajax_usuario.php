<?php
require_once __DIR__ . "/../librerias/conexion.php";

session_start();

global $conn;

$id = $_SESSION['id_usuario'];

$stmt = $conn->prepare("SELECT 
    nombres_usuario,
    apellidos_usuario,
    tipo_documento,
    numero_documento,
    direccion_usuario,
    celular_usuario,
    email_per
FROM usuario WHERE id_usuario = ?");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);
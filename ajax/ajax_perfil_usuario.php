<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../librerias/conexion.php";

session_start();
global $conn;

header('Content-Type: application/json');

if(!isset($_SESSION['id_usuario'])){
    echo json_encode(["error" => "Sesión no válida"]);
    exit;
}

$id = $_SESSION['id_usuario'];

// 🔹 DATOS USUARIO
$stmt = $conn->prepare("SELECT 
    u.nombres_usuario,
    u.apellidos_usuario,
    u.tipo_documento,
    u.numero_documento,
    u.direccion_usuario,
    u.celular_usuario,
    u.email_per,
    u.email_ins,
    u.url_foto_usuario,
    u.url_dni_usuario,
    u.url_firma,
    u.id_dep,
    u.id_prov,
    u.id_dis
FROM usuario u
WHERE u.id_usuario = ?");

$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();


// 🔹 CARGOS + ÁREAS
$stmt2 = $conn->prepare("SELECT 
    c.cargo,
    a.nombre_area
FROM usuario_cargo uc
INNER JOIN cargo c ON c.id_cargo = uc.id_cargo
LEFT JOIN area a ON a.id_area = c.id_area
WHERE uc.id_usuario = ?");

$stmt2->bind_param("i", $id);
$stmt2->execute();
$cargos = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);


// 🔹 PROGRAMAS DE ESTUDIO
$stmt3 = $conn->prepare("SELECT 
    pe.programa_estudio
FROM usuario_programa_estudio up
INNER JOIN programa_estudio pe 
    ON pe.id_programa_estudio = up.id_programa_estudio
WHERE up.id_usuario = ?");

$stmt3->bind_param("i", $id);
$stmt3->execute();
$programas = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);


// 🔹 RESPUESTA FINAL
echo json_encode([
    "usuario" => $usuario,
    "cargos" => $cargos,
    "programas" => $programas
]);


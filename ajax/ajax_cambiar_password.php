<?php
require_once __DIR__ . "/../librerias/conexion.php";
session_start();
global $conn;

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["status" => "error", "mensaje" => "Sesión no válida"]);
    exit;
}

$id = $_SESSION['id_usuario'];
$pass_actual = $_POST['password_actual'] ?? '';
$pass_nueva   = $_POST['password_nueva'] ?? '';

if(empty($pass_actual) || empty($pass_nueva)){
    echo json_encode(["status" => "error", "mensaje" => "Ambos campos son obligatorios"]);
    exit;
}

// 1. Obtener la contraseña de la BD
$sql = "SELECT contrasena FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    echo json_encode(["status" => "error", "mensaje" => "Usuario no encontrado"]);
    exit;
}

$hash_db = $user['contrasena'];
$es_correcta = false;

// 2. Lógica Híbrida de Verificación
if (strpos($hash_db, '$2y$') === 0) {
    // Es una contraseña encriptada (BCRYPT empieza con $2y$)
    if (password_verify($pass_actual, $hash_db)) {
        $es_correcta = true;
    }
} else {
    // Es una contraseña antigua en texto plano
    if ($pass_actual === $hash_db) {
        $es_correcta = true;
    }
}

if (!$es_correcta) {
    echo json_encode(["status" => "error", "mensaje" => "La contraseña actual es incorrecta"]);
    exit;
}

// 3. Encriptar la NUEVA contraseña (siempre encriptamos ahora)
$pass_hash = password_hash($pass_nueva, PASSWORD_DEFAULT);

// 4. Actualizar en la BD
$sql_up = "UPDATE usuario SET contrasena = ? WHERE id_usuario = ?";
$stmt_up = $conn->prepare($sql_up);
$stmt_up->bind_param("si", $pass_hash, $id);

if ($stmt_up->execute()) {
    echo json_encode(["status" => "ok", "mensaje" => "Contraseña actualizada correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Error al actualizar: " . $conn->error]);
}
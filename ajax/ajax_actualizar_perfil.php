<?php
require_once __DIR__ . "/../librerias/conexion.php";

session_start();
global $conn;

$id = $_SESSION['id_usuario'];

// DATOS
$nombres = $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$email_per = $_POST['email_personal'] ?? '';
$email_ins = $_POST['email_institucional'] ?? '';
$celular = $_POST['celular'] ?? '';
$tipo_doc = $_POST['tipo_documento'] ?? '';
$num_doc = $_POST['numero_identidad'] ?? '';
$direccion = $_POST['direccion_usuario'] ?? '';

$dep = $_POST['departamento'] ?? null;
$prov = $_POST['provincia'] ?? null;
$dist = $_POST['distrito'] ?? null;
error_log(print_r($_FILES, true));

// RUTA BASE
$ruta = __DIR__ . "/../../uploads/usuarios/";if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

// FUNCION PARA SUBIR ARCHIVOS
function subirArchivo($file, $prefijo, $ruta){

    if(isset($file) && $file['error'] === 0){

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombre = $prefijo . "_" . uniqid() . "." . $ext;

        $destino = $ruta . $nombre;

        if(move_uploaded_file($file['tmp_name'], $destino)){
            return $nombre;
        }else{
            error_log("Error moviendo archivo a: " . $destino);
            return null;
        }
    }else{
        error_log("Error en archivo: " . $file['error']);
    }

    return null;
}
echo $ruta;
exit;

// SUBIR ARCHIVOS
$foto_usuario = subirArchivo($_FILES['foto_usuario'], "foto", $ruta);
$foto_dni = subirArchivo($_FILES['foto_dni'], "dni", $ruta);
$firma = subirArchivo($_FILES['foto_firma'], "firma", $ruta);

// ARMAR QUERY DINÁMICO
$sql = "UPDATE usuario SET
    nombres_usuario = ?,
    apellidos_usuario = ?,
    email_per = ?,
    email_ins = ?,
    celular_usuario = ?,
    tipo_documento = ?,
    numero_documento = ?,
    direccion_usuario = ?,
    id_dep = ?,
    id_prov = ?,
    id_dis = ?";

$params = [
    $nombres, $apellidos, $email_per, $email_ins,
    $celular, $tipo_doc, $num_doc, $direccion,
    $dep, $prov, $dist
];

$types = "sssssssssss";

// SOLO ACTUALIZA SI SUBEN ARCHIVOS
if($foto_usuario){
    $sql .= ", url_foto_usuario = ?";
    $params[] = $foto_usuario;
    $types .= "s";
}

if($foto_dni){
    $sql .= ", url_dni_usuario = ?";
    $params[] = $foto_dni;
    $types .= "s";
}

if($firma){
    $sql .= ", url_firma = ?";
    $params[] = $firma;
    $types .= "s";
}

$sql .= " WHERE id_usuario = ?";
$params[] = $id;
$types .= "i";

// EJECUTAR
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if($stmt->execute()){
    echo json_encode([
        "status" => "ok",
        "mensaje" => "Perfil actualizado correctamente"
    ]);
}else{
    echo json_encode([
        "status" => "error",
        "mensaje" => $stmt->error
    ]);
}
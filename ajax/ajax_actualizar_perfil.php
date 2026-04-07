<?php
require_once __DIR__ . "/../librerias/conexion.php";

session_start();
global $conn;

// 1. Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["status" => "error", "mensaje" => "Sesión no iniciada"]);
    exit;
}

$id = $_SESSION['id_usuario'];

// 2. Capturar Datos
$nombres = $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$email_per = $_POST['email_personal'] ?? '';
$email_ins = $_POST['email_institucional'] ?? '';
$celular = $_POST['celular'] ?? '';
$tipo_doc = $_POST['tipo_documento'] ?? '';
$num_doc = $_POST['numero_identidad'] ?? '';
$direccion = $_POST['direccion_usuario'] ?? '';

$dep = !empty($_POST['departamento']) ? $_POST['departamento'] : null;
$prov = !empty($_POST['provincia']) ? $_POST['provincia'] : null;
$dist = !empty($_POST['distrito']) ? $_POST['distrito'] : null;

// 3. RUTA BASE (Asegúrate de que la ruta sea correcta y tenga permisos de escritura)
$ruta_carpeta = __DIR__ . "/../uploads/usuarios/";
if (!file_exists($ruta_carpeta)) {
    mkdir($ruta_carpeta, 0777, true);
}

// 4. FUNCIÓN PARA SUBIR ARCHIVOS MEJORADA
function subirArchivo($file, $prefijo, $ruta_destino) {
    // Verificar si el archivo fue enviado y no tiene errores
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombre_archivo = $prefijo . "_" . uniqid() . "." . $ext;
        $destino_final = $ruta_destino . $nombre_archivo;

        if (move_uploaded_file($file['tmp_name'], $destino_final)) {
            return $nombre_archivo; // Retornamos solo el nombre para la BD
        }
    }
    return null;
}

// 5. PROCESAR SUBIDA (Aquí eliminamos el exit que tenías)
$foto_usuario = subirArchivo($_FILES['foto_usuario'] ?? null, "foto", $ruta_carpeta);
$foto_dni     = subirArchivo($_FILES['foto_dni'] ?? null, "dni", $ruta_carpeta);
$firma        = subirArchivo($_FILES['foto_firma'] ?? null, "firma", $ruta_carpeta);

// 6. ARMAR QUERY DINÁMICO
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

$params = [$nombres, $apellidos, $email_per, $email_ins, $celular, $tipo_doc, $num_doc, $direccion, $dep, $prov, $dist];
$types = "ssssssssiii"; // Ajustado: i para los IDs de ubigeo que suelen ser enteros

// Agregar archivos solo si se subieron nuevos
if ($foto_usuario) {
    $sql .= ", url_foto_usuario = ?";
    $params[] = $foto_usuario;
    $types .= "s";
}
if ($foto_dni) {
    $sql .= ", url_dni_usuario = ?";
    $params[] = $foto_dni;
    $types .= "s";
}
if ($firma) {
    $sql .= ", url_firma = ?";
    $params[] = $firma;
    $types .= "s";
}

$sql .= " WHERE id_usuario = ?";
$params[] = $id;
$types .= "i";

// 7. EJECUTAR
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "ok",
            "mensaje" => "Perfil actualizado correctamente"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "mensaje" => "Error al ejecutar: " . $stmt->error
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error en la preparación: " . $conn->error
    ]);
}
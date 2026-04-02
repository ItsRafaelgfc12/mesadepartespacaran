<?php
require_once __DIR__ . "/../librerias/conexion.php";

global $conn;

$accion = $_GET['accion'] ?? '';

// 🔸 DEPARTAMENTOS
if($accion == "departamentos"){

    $res = $conn->query("SELECT id, name FROM ubigeo_peru_departments");

    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
}

// 🔸 PROVINCIAS
if($accion == "provincias"){

    $dep = $_GET['dep'];

    $stmt = $conn->prepare("SELECT id, name 
        FROM ubigeo_peru_provinces 
        WHERE department_id = ?");

    $stmt->bind_param("s", $dep);
    $stmt->execute();

    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
}

// 🔸 DISTRITOS
if($accion == "distritos"){

    $prov = $_GET['prov'];

    $stmt = $conn->prepare("SELECT id, name 
        FROM ubigeo_peru_districts 
        WHERE province_id = ?");

    $stmt->bind_param("s", $prov);
    $stmt->execute();

    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
}
<?php
session_start();
require_once "../modelo/fut_modelo.php";

$accion = $_GET['accion'] ?? '';

switch ($accion) {

    case 'guardar':

        $modelo = new FutModelo();

        $respuesta = $modelo->guardar($_POST, $_FILES);

        echo json_encode($respuesta);
        break;
}
<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = strtolower(trim($_POST["email_per"]));
    $password = trim($_POST["contraseña_usu"]);

    $sql = "SELECT id_usuario, nombres_usuario, apellidos_usuario, contrasena, id_rol 
            FROM usuario 
            WHERE email_per = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // SI usas contraseñas sin encriptar, deja esta línea:
        if ($password == $user["contrasena"]) {

        // SI usas password_hash(), reemplazar por:
        // if (password_verify($password, $user["contrasena"])) {

            $_SESSION["id_usuario"] = $user["id_usuario"];
            $_SESSION["nombre"] = $user["nombres_usuario"];
            $_SESSION["apellido"] = $user["apellidos_usuario"];
            $_SESSION["id_rol"] = $user["id_rol"];

            switch ($user["id_rol"]) {
                case 1:
                    header("Location: ../views/administrador/home.php");
                    exit;
                case 2:
                    header("Location: views/usuario/home.php");
                    exit;
                default:
                    echo "<script>alert('El usuario no tiene un rol asignado'); window.location='index.php';</script>";
                    exit;
            }

        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='index.php';</script>";
    }
}
?>

<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email_per"]);
    $password = trim($_POST["password"]);

    // Consulta segura
    $sql = "SELECT id_usuario, nombres_usuario, apellidos_usuario, contrasena, id_rol 
            FROM usuario WHERE email_per = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verificar contraseña (si usas password_hash)
        if ($password == $user["contrasena"]) {
            $_SESSION["id_usuario"] = $user["id_usuario"];
            $_SESSION["nombre"] = $user["nombres_usuario"];
            $_SESSION["apellido"] = $user["apellidos_usuario"];
            $_SESSION["id_rol"] = $user["id_rol"];
            // Redirección según el rol
            switch ($user["id_rol"]) {
                case 1: // Administrador
                    header("Location: views/administrador/home.php");
                    break;
                case 2: // Usuarios
                    header("Location: views/usuario/home.php");
                    break;
            }
            exit;
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='index.php';</script>";
    }
}
?>

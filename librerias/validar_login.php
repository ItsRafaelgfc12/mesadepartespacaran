<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = strtolower(trim($_POST["email_per"]));
    $password = trim($_POST["contraseña_usu"]);

    // 1. Obtener los datos del usuario por email
    $sql = "SELECT id_usuario, nombres_usuario, apellidos_usuario, contrasena, id_rol, url_foto_usuario
            FROM usuario
            WHERE email_per = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hash_db = $user["contrasena"];
        $login_exitoso = false;

        // 2. LÓGICA HÍBRIDA DE VERIFICACIÓN
        if (strpos($hash_db, '$2y$') === 0) {
            // Contraseña encriptada (Hash)
            if (password_verify($password, $hash_db)) {
                $login_exitoso = true;
            }
        } else {
            // Contraseña antigua (Texto plano)
            if ($password === $hash_db) {
                $login_exitoso = true;
                // OPCIONAL: Podrías encriptarla aquí mismo para que ya quede segura
            }
        }

        if ($login_exitoso) {
            // Guardar datos del usuario en sesión
            $_SESSION["id_usuario"] = $user["id_usuario"];
            $_SESSION["nombre"] = $user["nombres_usuario"];
            $_SESSION["apellido"] = $user["apellidos_usuario"];
            $_SESSION["id_rol"] = $user["id_rol"];
            $_SESSION["foto"] = $user["url_foto_usuario"]; // Guarda el nombre del archivo

            // Traer cargos
            $sql_cargos = "SELECT c.cargo 
                           FROM usuario_cargo uc
                           JOIN cargo c ON uc.id_cargo = c.id_cargo
                           WHERE uc.id_usuario = ?";
            $stmt_c = $conn->prepare($sql_cargos);
            $stmt_c->bind_param("i", $user['id_usuario']);
            $stmt_c->execute();
            $result_c = $stmt_c->get_result();

            $_SESSION['cargos'] = [];
            while($row = $result_c->fetch_assoc()){
                $_SESSION['cargos'][] = $row['cargo'];
            }

            // Redirección según rol
            switch ($user["id_rol"]) {
                case 1: header("Location: ../views/administrador/home.php"); break;
                case 2: header("Location: ../views/usuario/home.php"); break;
                case 3: header("Location: ../views/docente/home.php"); break;
                case 4: header("Location: ../views/administrativo/home.php"); break;
                default:
                    echo "<script>alert('El usuario no tiene un rol asignado'); window.location='../index.php';</script>";
                    exit;
            }
            exit;

        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='../index.php';</script>";
        }

    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='../index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>
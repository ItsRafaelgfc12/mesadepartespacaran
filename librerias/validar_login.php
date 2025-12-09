<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = strtolower(trim($_POST["email_per"]));
    $password = trim($_POST["contraseña_usu"]);

    // Preparar consulta para obtener usuario
    $sql = "SELECT id_usuario, nombres_usuario, apellidos_usuario, contrasena, id_rol 
            FROM usuario 
            WHERE email_per = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Verificar contraseña
        // SI las contraseñas están en texto plano:
        if ($password === $user["contrasena"]) {

        // SI usas password_hash() en la DB:
        // if (password_verify($password, $user["contrasena"])) {

            // Guardar datos del usuario en sesión
            $_SESSION["id_usuario"] = $user["id_usuario"];
            $_SESSION["nombre"] = $user["nombres_usuario"];
            $_SESSION["apellido"] = $user["apellidos_usuario"];
            $_SESSION["id_rol"] = $user["id_rol"];

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
            switch ($user["id_rol"]) {
                case 1:
                    header("Location: ../views/administrador/home.php");
                    exit;
                case 2:
                    header("Location: ../views/usuario/home.php");
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
} else {
    header("Location: index.php");
    exit;
}
?>

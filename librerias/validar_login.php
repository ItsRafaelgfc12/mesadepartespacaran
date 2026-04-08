<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = strtolower(trim($_POST["email_per"]));
    $password = trim($_POST["contraseña_usu"]);

    // 1. Obtener los datos básicos del usuario
    $sql = "SELECT id_usuario, nombres_usuario, apellidos_usuario, contrasena, id_rol, url_foto_usuario 
            FROM usuario WHERE email_per = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verificación de contraseña (Híbrida)
        $login_exitoso = false;
        if (strpos($user["contrasena"], '$2y$') === 0) {
            if (password_verify($password, $user["contrasena"])) { $login_exitoso = true; }
        } else {
            if ($password === $user["contrasena"]) { $login_exitoso = true; }
        }

        if ($login_exitoso) {
            // Guardar datos básicos
            $_SESSION["id_usuario"] = $user["id_usuario"];
            $_SESSION["nombre"] = $user["nombres_usuario"];
            $_SESSION["apellido"] = $user["apellidos_usuario"];
            $_SESSION["id_rol"] = $user["id_rol"];
            $_SESSION["foto"] = $user["url_foto_usuario"];

            // 2. OBTENER TODOS LOS CARGOS Y ÁREAS (Lógica Polimórfica Multicargo)
            $sql_cargos = "SELECT c.id_cargo, c.cargo, c.id_area 
                           FROM usuario_cargo uc
                           JOIN cargo c ON uc.id_cargo = c.id_cargo
                           WHERE uc.id_usuario = ?";
            
            $stmt_c = $conn->prepare($sql_cargos);
            $stmt_c->bind_param("i", $user['id_usuario']);
            $stmt_c->execute();
            $result_c = $stmt_c->get_result();

            // Arrays para recolectar la información
            $cargos_nombres = [];
            $cargos_ids = [];
            $areas_ids = [];

            // Variables legado (por si se ocupan en otro lado)
            $_SESSION['id_cargo_principal'] = 0;
            $_SESSION['id_area'] = 0;

            while($row = $result_c->fetch_assoc()){
                $cargos_nombres[] = $row['cargo'];
                $cargos_ids[] = $row['id_cargo'];
                
                if (!empty($row['id_area'])) {
                    $areas_ids[] = $row['id_area']; 
                }

                // Definimos el cargo y área principal del primer registro
                if($_SESSION['id_cargo_principal'] == 0){
                    $_SESSION['id_cargo_principal'] = $row['id_cargo'];
                    $_SESSION['id_area'] = $row['id_area']; 
                }
            }

            // MAGIA MULTICARGO: Guardamos todo en la sesión como texto "3,6"
            // array_unique evita que si dos cargos están en la misma área, el área se repita.
            $_SESSION['cargos_ids'] = empty($cargos_ids) ? '0' : implode(',', array_unique($cargos_ids));
            $_SESSION['areas_ids']  = empty($areas_ids) ? '0' : implode(',', array_unique($areas_ids));
            $_SESSION['cargos'] = $cargos_nombres; // El array original por si imprimes los nombres en el menú

            // Redirección por roles
            $redirects = [
                1 => "../views/administrador/home.php",
                2 => "../views/usuario/home.php",
                3 => "../views/docente/home.php",
                4 => "../views/administrativo/home.php"
            ];

            $location = $redirects[$user["id_rol"]] ?? "../index.php";
            header("Location: " . $location);
            exit;

        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='../index.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='../index.php';</script>";
    }
}
?>
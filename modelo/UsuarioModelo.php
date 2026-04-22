<?php
require_once __DIR__ . "/../librerias/conexion.php";

class UsuarioModelo {

    // 1. Consulta para el FUT (Perfil del usuario logueado)
    public function obtenerDatosPersonalesFut($id_usuario) {
        global $conn;
        $stmt = $conn->prepare("SELECT nombres_usuario, apellidos_usuario, tipo_documento, numero_documento, direccion_usuario, celular_usuario, email_per FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc(); 
    }

    // 2. Extraer opciones para los Selects del Modal y Filtros
    public function obtenerListasDesplegables() {
        global $conn;
        $data = [];
        $data['roles'] = $conn->query("SELECT id_rol as id, rol as nombre FROM rol")->fetch_all(MYSQLI_ASSOC);
        $data['areas'] = $conn->query("SELECT id_area as id, nombre_area as nombre FROM area")->fetch_all(MYSQLI_ASSOC);
        // IMPORTANTE: Traemos el id_area del cargo
        $data['cargos'] = $conn->query("SELECT id_cargo as id, cargo as nombre, id_area FROM cargo")->fetch_all(MYSQLI_ASSOC);
        $data['programas'] = $conn->query("SELECT id_programa_estudio as id, programa_estudio as nombre FROM programa_estudio")->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    public function listarUsuariosAdmin() {
        global $conn;
        $sql = "SELECT u.id_usuario, u.nombres_usuario, u.apellidos_usuario, u.email_per as correo, LOWER(eu.nombre) as estado,
                       r.rol as rol, a.nombre_area as area, c.cargo as cargo, pe.programa_estudio as programa
                FROM usuario u
                LEFT JOIN estado_usuario eu ON u.id_estado = eu.id_estado
                LEFT JOIN rol r ON u.id_rol = r.id_rol
                LEFT JOIN usuario_cargo uc ON u.id_usuario = uc.id_usuario
                LEFT JOIN cargo c ON uc.id_cargo = c.id_cargo
                LEFT JOIN area a ON c.id_area = a.id_area
                LEFT JOIN usuario_programa_estudio upe ON u.id_usuario = upe.id_usuario
                LEFT JOIN programa_estudio pe ON upe.id_programa_estudio = pe.id_programa_estudio
                ORDER BY u.id_usuario DESC";
        $result = $conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // 2. GUARDAR (Registrar y Editar)
    public function registrarUsuario($post) {
        global $conn;

        $id_usuario = !empty($post['id_usuario']) ? intval($post['id_usuario']) : 0;
        $nombres = trim($post['nombres_usuario']);
        $apellidos = trim($post['apellidos_usuario']);
        $email = trim($post['email_per']);
        $id_rol = intval($post['id_rol']);
        $id_cargo = !empty($post['id_cargo']) ? intval($post['id_cargo']) : NULL;
        $id_programa = !empty($post['id_programa']) ? intval($post['id_programa']) : NULL;

        $conn->begin_transaction();
        try {
            if ($id_usuario == 0) { // NUEVO USUARIO
                $contrasena = !empty($post['password']) ? password_hash($post['password'], PASSWORD_DEFAULT) : password_hash('123456', PASSWORD_DEFAULT); 
                $stmt = $conn->prepare("INSERT INTO usuario (nombres_usuario, apellidos_usuario, email_per, contrasena, id_rol, id_estado) VALUES (?, ?, ?, ?, ?, 1)");
                $stmt->bind_param("ssssi", $nombres, $apellidos, $email, $contrasena, $id_rol);
                if (!$stmt->execute()) throw new Exception("Error al crear usuario.");
                $id_usuario = $conn->insert_id;
            } else { // EDITAR USUARIO
                if (!empty($post['password'])) {
                    $contrasena = password_hash($post['password'], PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE usuario SET nombres_usuario=?, apellidos_usuario=?, email_per=?, id_rol=?, contrasena=? WHERE id_usuario=?");
                    $stmt->bind_param("ssssii", $nombres, $apellidos, $email, $id_rol, $contrasena, $id_usuario);
                } else {
                    $stmt = $conn->prepare("UPDATE usuario SET nombres_usuario=?, apellidos_usuario=?, email_per=?, id_rol=? WHERE id_usuario=?");
                    $stmt->bind_param("ssssi", $nombres, $apellidos, $email, $id_rol, $id_usuario);
                }
                if (!$stmt->execute()) throw new Exception("Error al actualizar usuario.");
                
                // Limpiamos los cargos y programas anteriores para reemplazarlos
                $conn->query("DELETE FROM usuario_cargo WHERE id_usuario = $id_usuario");
                $conn->query("DELETE FROM usuario_programa_estudio WHERE id_usuario = $id_usuario");
            }

            // Insertar Cargo
            if ($id_cargo) {
                $stmt_cargo = $conn->prepare("INSERT INTO usuario_cargo (id_usuario, id_cargo) VALUES (?, ?)");
                $stmt_cargo->bind_param("ii", $id_usuario, $id_cargo);
                $stmt_cargo->execute();
            }

            // Insertar Programa
            if ($id_programa) {
                $stmt_prog = $conn->prepare("INSERT INTO usuario_programa_estudio (id_usuario, id_programa_estudio) VALUES (?, ?)");
                $stmt_prog->bind_param("ii", $id_usuario, $id_programa);
                $stmt_prog->execute();
            }

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Datos guardados correctamente."];

        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    // 3. OBTENER DATOS DE UN USUARIO (Para llenarlos en el Modal de Editar)
    public function obtenerUsuario($id_usuario) {
        global $conn;
        $sql = "SELECT u.id_usuario, u.nombres_usuario, u.apellidos_usuario, u.email_per, u.id_rol,
                       uc.id_cargo, c.id_area, upe.id_programa_estudio as id_programa
                FROM usuario u
                LEFT JOIN usuario_cargo uc ON u.id_usuario = uc.id_usuario
                LEFT JOIN cargo c ON uc.id_cargo = c.id_cargo
                LEFT JOIN usuario_programa_estudio upe ON u.id_usuario = upe.id_usuario
                WHERE u.id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 4. ELIMINAR (Dar de baja: id_estado = 2)
    public function eliminarUsuario($id_usuario) {
        global $conn;
        $stmt = $conn->prepare("UPDATE usuario SET id_estado = 2 WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        if($stmt->execute()) return ["status" => "ok", "mensaje" => "Usuario inhabilitado."];
        return ["status" => "error", "mensaje" => "Error al inhabilitar."];
    }
    
}
?>
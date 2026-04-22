<?php
require_once __DIR__ . "/../librerias/conexion.php";

class PlantillaModelo {

    // 1. SUBIR PLANTILLA NUEVA
    public function subirPlantilla($post, $files, $id_usuario) {
        global $conn;
        
        $titulo = trim($post['titulo']);
        $descripcion = trim($post['descripcion']);
        $tipo_acceso = $post['tipo_acceso'];
        $id_referencia = !empty($post['id_referencia']) ? $post['id_referencia'] : NULL;

        $dir_base = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "plantillas" . DIRECTORY_SEPARATOR;
        $dir_img = $dir_base . "imagenes" . DIRECTORY_SEPARATOR;
        $dir_arch = $dir_base . "archivos" . DIRECTORY_SEPARATOR;

        if (!file_exists($dir_img)) mkdir($dir_img, 0777, true);
        if (!file_exists($dir_arch)) mkdir($dir_arch, 0777, true);

        // Procesar Archivos
        $img_ext = strtolower(pathinfo($files['imagen']['name'], PATHINFO_EXTENSION));
        $doc_ext = strtolower(pathinfo($files['archivo']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($img_ext, ['jpg', 'jpeg', 'png', 'webp'])) return ["status" => "error", "mensaje" => "Imagen no válida."];
        if (!in_array($doc_ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf'])) return ["status" => "error", "mensaje" => "Documento no válido."];

        $nuevo_nombre_img = "prev_" . time() . "_" . uniqid() . "." . $img_ext;
        $ruta_img_fisica = $dir_img . $nuevo_nombre_img;
        $ruta_img_bd = "uploads/plantillas/imagenes/" . $nuevo_nombre_img;

        $nuevo_nombre_doc = "doc_" . time() . "_" . uniqid() . "." . $doc_ext;
        $ruta_doc_fisica = $dir_arch . $nuevo_nombre_doc;
        $ruta_doc_bd = "uploads/plantillas/archivos/" . $nuevo_nombre_doc;

        $conn->begin_transaction();
        try {
            if (!move_uploaded_file($files['imagen']['tmp_name'], $ruta_img_fisica)) throw new Exception("Error al subir imagen.");
            if (!move_uploaded_file($files['archivo']['tmp_name'], $ruta_doc_fisica)) {
                unlink($ruta_img_fisica); throw new Exception("Error al subir archivo.");
            }

            $stmt = $conn->prepare("INSERT INTO plantilla (titulo, descripcion, url_imagen, ruta_archivo, id_usuario) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $titulo, $descripcion, $ruta_img_bd, $ruta_doc_bd, $id_usuario);
            if (!$stmt->execute()) throw new Exception("Error en la base de datos.");

            $id_plantilla = $conn->insert_id;

            if ($tipo_acceso === 'publico') {
                $stmtAcceso = $conn->prepare("INSERT INTO plantilla_acceso (id_plantilla, tipo_acceso, permiso) VALUES (?, 'publico', 'usar')");
                $stmtAcceso->bind_param("i", $id_plantilla);
            } else {
                $stmtAcceso = $conn->prepare("INSERT INTO plantilla_acceso (id_plantilla, tipo_acceso, id_referencia, permiso) VALUES (?, ?, ?, 'usar')");
                $stmtAcceso->bind_param("isi", $id_plantilla, $tipo_acceso, $id_referencia);
            }
            if (!$stmtAcceso->execute()) throw new Exception("Error en permisos.");

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Plantilla guardada exitosamente."];

        } catch (Exception $e) {
            $conn->rollback();
            if (file_exists($ruta_img_fisica)) @unlink($ruta_img_fisica);
            if (file_exists($ruta_doc_fisica)) @unlink($ruta_doc_fisica);
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }

    // 2. LISTAR PLANTILLAS
    public function listarPlantillas() {
        global $conn;
        // Traemos todas (activas e inactivas) para que el Admin Panel las vea todas
        $sql = "SELECT p.*, CONCAT(u.nombres_usuario, ' ', u.apellidos_usuario) as autor 
                FROM plantilla p 
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario 
                ORDER BY p.id_plantilla DESC";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // 3. ELIMINAR PLANTILLA (Inactivar)
    public function eliminarPlantilla($id_plantilla, $id_usuario, $id_rol) {
        global $conn;
        
        $plantilla = $this->obtenerPlantilla($id_plantilla);
        if (!$plantilla) return ["status" => "error", "mensaje" => "No encontrada."];
        
        // Validación de seguridad backend
        if ($plantilla['id_usuario'] != $id_usuario && $id_rol != 1) {
            return ["status" => "error", "mensaje" => "No tienes permisos."];
        }

        $stmt = $conn->prepare("UPDATE plantilla SET estado = 'inactivo' WHERE id_plantilla = ?");
        $stmt->bind_param("i", $id_plantilla);
        
        if($stmt->execute()) return ["status" => "ok", "mensaje" => "Plantilla movida a papelera."];
        return ["status" => "error", "mensaje" => "Error al eliminar."];
    }

    // 4. OBTENER UNA SOLA PLANTILLA
    public function obtenerPlantilla($id_plantilla) {
        global $conn;
        $sql = "SELECT p.*, pa.tipo_acceso, pa.id_referencia 
                FROM plantilla p 
                LEFT JOIN plantilla_acceso pa ON p.id_plantilla = pa.id_plantilla 
                WHERE p.id_plantilla = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_plantilla);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 5. EDITAR PLANTILLA
    public function editarPlantilla($post, $files, $id_usuario_actual, $id_rol_actual) {
        global $conn;
        
        $id_plantilla = intval($post['id_plantilla']);
        $titulo = trim($post['titulo']);
        $descripcion = trim($post['descripcion']);
        $estado = $post['estado'];
        $tipo_acceso = $post['tipo_acceso'];
        $id_referencia = !empty($post['id_referencia']) ? $post['id_referencia'] : NULL;

        $plantilla_actual = $this->obtenerPlantilla($id_plantilla);
        if (!$plantilla_actual) return ["status" => "error", "mensaje" => "Plantilla no encontrada."];
        if ($plantilla_actual['id_usuario'] != $id_usuario_actual && $id_rol_actual != 1) {
            return ["status" => "error", "mensaje" => "No tienes permisos."];
        }

        $conn->begin_transaction();
        try {
            // Actualizar datos de texto
            $stmt = $conn->prepare("UPDATE plantilla SET titulo=?, descripcion=?, estado=? WHERE id_plantilla=?");
            $stmt->bind_param("sssi", $titulo, $descripcion, $estado, $id_plantilla);
            if (!$stmt->execute()) throw new Exception("Error al actualizar textos.");

            // Actualizar Accesos
            $conn->query("DELETE FROM plantilla_acceso WHERE id_plantilla = $id_plantilla");
            
            if ($tipo_acceso === 'publico') {
                $stmtAcceso = $conn->prepare("INSERT INTO plantilla_acceso (id_plantilla, tipo_acceso, permiso) VALUES (?, 'publico', 'usar')");
                $stmtAcceso->bind_param("i", $id_plantilla);
            } else {
                $stmtAcceso = $conn->prepare("INSERT INTO plantilla_acceso (id_plantilla, tipo_acceso, id_referencia, permiso) VALUES (?, ?, ?, 'usar')");
                $stmtAcceso->bind_param("isi", $id_plantilla, $tipo_acceso, $id_referencia);
            }
            if (!$stmtAcceso->execute()) throw new Exception("Error al actualizar permisos.");

            $dir_base = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "plantillas" . DIRECTORY_SEPARATOR;
            
            // Reemplazar Imagen si existe
            if (isset($files['imagen']) && $files['imagen']['error'] === UPLOAD_ERR_OK) {
                $img_ext = strtolower(pathinfo($files['imagen']['name'], PATHINFO_EXTENSION));
                $nuevo_nom_img = "prev_" . time() . "_" . uniqid() . "." . $img_ext;
                $ruta_fisica_img = $dir_base . "imagenes" . DIRECTORY_SEPARATOR . $nuevo_nom_img;
                
                if (move_uploaded_file($files['imagen']['tmp_name'], $ruta_fisica_img)) {
                    $ruta_bd = "uploads/plantillas/imagenes/" . $nuevo_nom_img;
                    $conn->query("UPDATE plantilla SET url_imagen = '$ruta_bd' WHERE id_plantilla = $id_plantilla");
                    if (file_exists(dirname(__DIR__) . "/../" . $plantilla_actual['url_imagen'])) @unlink(dirname(__DIR__) . "/../" . $plantilla_actual['url_imagen']);
                }
            }

            // Reemplazar Archivo si existe
            if (isset($files['archivo']) && $files['archivo']['error'] === UPLOAD_ERR_OK) {
                $doc_ext = strtolower(pathinfo($files['archivo']['name'], PATHINFO_EXTENSION));
                $nuevo_nom_doc = "doc_" . time() . "_" . uniqid() . "." . $doc_ext;
                $ruta_fisica_doc = $dir_base . "archivos" . DIRECTORY_SEPARATOR . $nuevo_nom_doc;
                
                if (move_uploaded_file($files['archivo']['tmp_name'], $ruta_fisica_doc)) {
                    $ruta_bd = "uploads/plantillas/archivos/" . $nuevo_nom_doc;
                    $conn->query("UPDATE plantilla SET ruta_archivo = '$ruta_bd' WHERE id_plantilla = $id_plantilla");
                    if (file_exists(dirname(__DIR__) . "/../" . $plantilla_actual['ruta_archivo'])) @unlink(dirname(__DIR__) . "/../" . $plantilla_actual['ruta_archivo']);
                }
            }

            $conn->commit();
            return ["status" => "ok", "mensaje" => "Plantilla actualizada correctamente."];

        } catch (Exception $e) {
            $conn->rollback();
            return ["status" => "error", "mensaje" => $e->getMessage()];
        }
    }
}
?>
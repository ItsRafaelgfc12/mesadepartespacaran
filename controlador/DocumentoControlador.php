<?php
require_once __DIR__ . "/../modelo/DocumentoModelo.php";

class DocumentoControlador {
    
    private $modelo;

    public function __construct() {
        $this->modelo = new DocumentoModelo();
    }

    public function listarRecibidos() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['id_usuario'])) {
            return ["error" => "Sesión no válida"];
        }

        $id_usuario = $_SESSION['id_usuario'];
        $id_rol     = $_SESSION['id_rol'] ?? 0;
        $cargos_ids = $_SESSION['cargos_ids'] ?? '0';
        $areas_ids  = $_SESSION['areas_ids'] ?? '0';
        $prog_ids   = $_SESSION['programas_ids'] ?? '0';

        $datos = $this->modelo->listarRecibidos($id_usuario, $cargos_ids, $areas_ids, $id_rol, $prog_ids);

        return ["data" => $datos];
    }

    public function procesarDerivacion($post, $archivos = null) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_user_sesion = $_SESSION['id_usuario'] ?? 0;

        $archivo_anexo = null;

        if (isset($archivos['archivo_anexo']) && $archivos['archivo_anexo']['name'] !== '') {
            $error_code = $archivos['archivo_anexo']['error'];
            if ($error_code === UPLOAD_ERR_OK) {
                $archivo_anexo = $archivos['archivo_anexo'];
            } else {
                return ["status" => "error", "mensaje" => "Error al subir el anexo (Código: $error_code)"];
            }
        }

        return $this->modelo->derivarDocumento(
            $post['id_documento'], 
            $post['id_derivacion_padre'], 
            $post['tipo_destino'], 
            $post['id_destino'], 
            $post['observacion'], 
            $id_user_sesion,
            $archivo_anexo
        );
    }

    public function atenderDocumento($post) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        
        $id_doc = $post['id_documento'] ?? 0;
        $id_derivacion = $post['id_derivacion'] ?? 0;
        $mensaje = $post['mensaje'] ?? 'Trámite en proceso.';

        if ($id_usuario == 0) return ["status" => "error", "mensaje" => "Sesión expirada"];

        return $this->modelo->atenderDocumento($id_doc, $id_derivacion, $mensaje, $id_usuario);
    }

    public function archivarDocumento($post, $files) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['id_usuario'] ?? 0;
        
        $id_doc = $post['id_documento'] ?? 0;
        $mensaje = $post['mensaje'] ?? 'Sin observación de cierre.';
        $archivo = (isset($files['archivo_final']) && $files['archivo_final']['name'] != '') ? $files['archivo_final'] : null;

        return $this->modelo->archivarDocumento($id_doc, $mensaje, $id_user, $archivo);
    }

    public function listarArchivados() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_user = $_SESSION['id_usuario'] ?? 0;
        return ["data" => $this->modelo->listarArchivados($id_user)];
    }

    public function obtenerSeguimiento($id_doc) {
        return $this->modelo->obtenerSeguimiento($id_doc);
    }
    
    public function listarAtendidos() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'];
        $datos = $this->modelo->listarAtendidos($id_usuario);
        return ["data" => $datos];
    }

    // ==============================================================
    // REGISTRO DE DOCUMENTO INTERNO (Soporta Única y Múltiple)
    // ==============================================================
    public function registrarDocumentoInterno($post, $files) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_emisor = $_SESSION['id_usuario'] ?? 0;
        
        if($id_emisor == 0) return ["status" => "error", "mensaje" => "Sesión expirada. Vuelva a iniciar sesión."];
        if(empty($post['codigo_documento'])) return ["status" => "error", "mensaje" => "El código del documento es obligatorio."];

        // El modelo procesará el array 'destinatarios_multiples' si la modalidad es múltiple
        return $this->modelo->registrarDocumentoInterno($post, $files, $id_emisor);
    }

    public function listarHistorialEnviados() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'];
        return ["data" => $this->modelo->listarHistorialEnviados($id_usuario)];
    }

    public function liberarDocumento($post) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['id_usuario'] ?? 0;
        return $this->modelo->liberarDocumento($post['id_documento'], $post['id_derivacion'], $id_usuario);
    }
}
?>
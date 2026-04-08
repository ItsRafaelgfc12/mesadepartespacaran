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

        // Capturamos todos los frentes posibles del usuario
        $id_usuario = $_SESSION['id_usuario'];
        $id_rol     = $_SESSION['id_rol'] ?? 0;
        $cargos_ids = $_SESSION['cargos_ids'] ?? '0';
        $areas_ids  = $_SESSION['areas_ids'] ?? '0';
        $prog_ids   = $_SESSION['programas_ids'] ?? '0'; // Preparado para cuando agregues programas al login

        $datos = $this->modelo->listarRecibidos($id_usuario, $cargos_ids, $areas_ids, $id_rol, $prog_ids);

        return [
            "data" => $datos,
            "debug" => [
                "buscando_usuario" => $id_usuario,
                "buscando_cargos" => $cargos_ids,
                "buscando_areas" => $areas_ids,
                "buscando_rol" => $id_rol,
                "buscando_programas" => $prog_ids
            ]
        ];
    }

    // AÑADIDO: Recibimos $archivos ($_FILES)
    public function procesarDerivacion($post, $archivos = null) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_user_sesion = $_SESSION['id_usuario'] ?? 0;

        $archivo_anexo = null;

        // VERIFICACIÓN ESTRICTA DEL ARCHIVO
        if (isset($archivos['archivo_anexo']) && $archivos['archivo_anexo']['name'] !== '') {
            $error_code = $archivos['archivo_anexo']['error'];
            
            if ($error_code === UPLOAD_ERR_OK) {
                $archivo_anexo = $archivos['archivo_anexo'];
            } else {
                // Mapeo de errores para que SweetAlert te diga qué pasa
                $errores = [
                    1 => 'El archivo pesa demasiado (Excede el límite de upload_max_filesize en php.ini).',
                    2 => 'El archivo excede el límite MAX_FILE_SIZE del HTML.',
                    3 => 'El archivo se subió por la mitad (interrupción de red).',
                    4 => 'No se subió ningún archivo.',
                    6 => 'Falta la carpeta temporal de PHP en el servidor.',
                    7 => 'Error de permisos: No se pudo escribir el archivo en el disco.',
                    8 => 'Una extensión de PHP bloqueó la subida.'
                ];
                $msg_error = $errores[$error_code] ?? 'Error desconocido al subir el archivo (Código: '.$error_code.')';
                return ["status" => "error", "mensaje" => $msg_error];
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

    public function archivarDocumento($post, $files) {
    if (session_status() == PHP_SESSION_NONE) session_start();
    $id_user = $_SESSION['id_usuario'] ?? 0;
    
    $id_doc = $post['id_documento'] ?? 0;
    $mensaje = $post['mensaje'] ?? 'Sin observación de cierre.';
    
    // Validamos si realmente se envió un archivo
    $archivo = (isset($files['archivo_final']) && $files['archivo_final']['name'] != '') ? $files['archivo_final'] : null;

    return $this->modelo->archivarDocumento($id_doc, $mensaje, $id_user, $archivo);
}
    public function listarArchivados() {
        $datos = $this->modelo->listarArchivados();
        return ["data" => $datos];
    }

    // Agrega esto debajo de la función archivarDocumento
    public function atenderDocumento($post) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $id_user_sesion = $_SESSION['id_usuario'] ?? 0;
        
        $id_doc = $post['id_documento'] ?? 0;
        $id_derivacion = $post['id_derivacion'] ?? 0; // Se captura gracias al input oculto que arreglamos
        $mensaje = $post['mensaje'] ?? 'Trámite en proceso.';

        return $this->modelo->atenderDocumento($id_doc, $id_derivacion, $mensaje, $id_user_sesion);
    }

    public function obtenerSeguimiento($id_doc) {
        return $this->modelo->obtenerSeguimiento($id_doc);
    }
   
    public function listarAtendidos() {
    if (session_status() == PHP_SESSION_NONE) session_start();
    $id_usuario = $_SESSION['id_usuario'];
    
    $datos = $this->modelo->listarAtendidos($id_usuario);
    
    return [
        "data" => $datos,
        "debug" => [
            "buscando_cargos_ids" => $_SESSION['cargos_ids'] ?? '0',
            "buscando_areas_ids" => $_SESSION['areas_ids'] ?? '0'
        ]
    ];
}
public function registrarDocumentoInterno($post, $files) {
    if (session_status() == PHP_SESSION_NONE) session_start();
    $id_emisor = $_SESSION['id_usuario'] ?? 0;
    
    if($id_emisor == 0) return ["status" => "error", "mensaje" => "Sesión expirada"];
    if(empty($post['codigo_documento'])) return ["status" => "error", "mensaje" => "El código es obligatorio"];

    return $this->modelo->registrarDocumentoInterno($post, $files, $id_emisor);
}
    
}
?>
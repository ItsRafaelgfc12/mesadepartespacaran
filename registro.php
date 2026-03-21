<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Registro de Usuario</title>

<link rel="icon" href="img/LOGIESPACARAN.png" type="image/png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

<style>
body {
    font-family: 'Nunito', sans-serif;
    background: linear-gradient(135deg, #6f42c1, #4e2a84);
    min-height: 100vh;
}
.card {
    border-radius: 1rem;
}
.preview-img {
    max-height: 120px;
    margin-top: 10px;
    border-radius: 8px;
}
</style>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10 col-md-12">
            <div class="card shadow-lg p-4">
                
                <div class="text-center mb-4">
                    <h2 class="text-primary fw-bold">Registro de Usuario</h2>
                    <a href="index.php" class="btn btn-secondary btn-sm mt-2">
                        <i class="bi bi-arrow-left"></i> Volver al Inicio
                    </a>
                </div>

                <form method="POST" enctype="multipart/form-data">

                    <!-- Datos Personales -->
                    <h5 class="text-secondary mb-3">Datos Personales</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="nombres_usuario" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input type="text" class="form-control" name="apellidos_usuario" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Email Personal</label>
                            <input type="email" class="form-control" name="email_per" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Institucional</label>
                            <input type="email" class="form-control" name="email_ins" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Celular</label>
                            <input type="tel" class="form-control" name="celular_usuario" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo Documento</label>
                            <select class="form-select" name="tipo_documento" required>
                                <option value="">Seleccione</option>
                                <option value="DNI">DNI</option>
                                <option value="CE">Carné de Extranjería</option>
                                <option value="Pasaporte">Pasaporte</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label class="form-label">Número Documento</label>
                        <input type="text" class="form-control" name="numero_documento" required>
                    </div>

                    <div class="mt-2">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion_usuario">
                    </div>

                    <hr>

                    <!-- Acceso -->
                    <h5 class="text-secondary mb-3">Acceso al Sistema</h5>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="contrasena" required>
                    </div>

                    <hr>

                    <!-- Archivos -->
                    <h5 class="text-secondary mb-3">Archivos</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Foto Usuario</label>
                            <input type="file" class="form-control" name="url_foto_usuario" accept="image/*" onchange="previewImage(event,'preview1')">
                            <img id="preview1" class="preview-img">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Foto DNI</label>
                            <input type="file" class="form-control" name="url_dni_usuario" accept="image/*" onchange="previewImage(event,'preview2')">
                            <img id="preview2" class="preview-img">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Firma</label>
                            <input type="file" class="form-control" name="url_firma" accept="image/*" onchange="previewImage(event,'preview3')">
                            <img id="preview3" class="preview-img">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4">Registrarse</button>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event, id){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById(id).src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
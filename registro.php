<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro</title>

    <link rel="icon" href="img/LOGIESPACARAN.png" type="image/png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
<style>
.bg-gradient-purple {
    background: linear-gradient(135deg, #6f42c1, #4e2a84);
    background-size: cover;
}
</style>

</head>

<body class="bg-gradient-purple">

<div class="container">

    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">

                    <div class="text-center mb-4">
                        <h1 class="h4 text-gray-900">Registro de Usuario</h1>
                    </div>
                    <div class="text-center mb-3">
                        <a href="index.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Regresar al Inicio
                        </a>
                    </div>

                    <form method="POST" enctype="multipart/form-data">

                        <!-- Datos Personales -->
                        <h6 class="font-weight-bold text-primary">Datos Personales</h6>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nombres</label>
                                <input type="text" class="form-control" name="nombres_usuario">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Apellidos</label>
                                <input type="text" class="form-control" name="apellidos_usuario">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Email Personal</label>
                                <input type="email" class="form-control" name="email_per">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email Institucional</label>
                                <input type="email" class="form-control" name="email_ins">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Celular</label>
                                <input type="number" class="form-control" name="celular_usuario">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tipo Documento</label>
                                <select class="form-control" name="tipo_documento">
                                    <option value="">Seleccione</option>
                                    <option value="DNI">DNI</option>
                                    <option value="CE">Carné de Extranjería</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Número Documento</label>
                            <input type="number" class="form-control" name="numero_documento">
                        </div>

                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" class="form-control" name="direccion_usuario">
                        </div>

                        <hr>

                        <!-- Ubicación -->
                        <h6 class="font-weight-bold text-primary">Ubicación</h6>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>ID Departamento</label>
                                <input type="text" class="form-control" name="id_dep">
                            </div>
                            <div class="form-group col-md-4">
                                <label>ID Provincia</label>
                                <input type="text" class="form-control" name="id_prov">
                            </div>
                            <div class="form-group col-md-4">
                                <label>ID Distrito</label>
                                <input type="text" class="form-control" name="id_dis">
                            </div>
                        </div>

                        <hr>

                        <!-- Acceso -->
                        <h6 class="font-weight-bold text-primary">Acceso al Sistema</h6>

                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="password" class="form-control" name="contrasena">
                        </div>

                        <hr>

                        <!-- Archivos -->
                        <h6 class="font-weight-bold text-primary">Archivos</h6>

                        <div class="form-group">
                            <label>Foto Usuario</label>
                            <input type="file" class="form-control-file" name="url_foto_usuario">
                        </div>

                        <div class="form-group">
                            <label>Foto DNI</label>
                            <input type="file" class="form-control-file" name="url_dni_usuario">
                        </div>

                        <div class="form-group">
                            <label>Firma</label>
                            <input type="file" class="form-control-file" name="url_firma">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            Registrarse
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- JS -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>

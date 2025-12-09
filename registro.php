<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Registro</title>
    <link rel="icon" href="img/LOGIESPACARAN.png" type="image/png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Crear una cuenta!</h1>
                            </div>
                            <form class="user">
                                <!-- Apellidos y nombres -->
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="apellidosusuario"
                                            placeholder="Apellidos">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="nombresusuario"
                                            placeholder="Nombres">
                                    </div>
                                </div>
                                <!-- Correos -->
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="correopersonal"
                                        placeholder="Correo Personal">
                                </div>
                                <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="correoinstitucional"
                                        placeholder="Correo institucional">
                                </div>
                                <!-- Celular y documento de identidad -->
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user"
                                        id="celular_usuario" name="celular_usuario" placeholder="Celular">
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="tipo_documento" name="tipo_documento">
                                            <option value="">Seleccione tipo de documento</option>
                                            <option value="DNI">DNI</option>
                                            <option value="CE">Carné de Extranjería</option>
                                        <option value="PASAPORTE">Pasaporte</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control form-control-user"
                                            id="numero_documento" name="numero_documento" placeholder="Número de documento">
                                    </div>
                                </div>
                                <!-- Ubigeo -->
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user"
                                            id="celular_usuario" name="departamento" placeholder="Departamento">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control form-control-user"
                                            id="celular_usuario" name="provincia" placeholder="Provincia">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control form-control-user"
                                            id="numero_documento" name="distrito" placeholder="Distrito">
                                    </div>
                                </div>
                                <!-- Direccion -->
                                 <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="direccion"
                                        placeholder="Direccion">
                                </div>
                                <!-- Contraseña -->
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="contraseña" placeholder="Contraseña">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="verificaciondecontraseña" placeholder="Repetir Contraseña">
                                    </div>
                                </div>
                                <a href="login.html" class="btn btn-primary btn-user btn-block">
                                    Registrarme
                                </a>

                                <!-- Posiblemente de habilite para el registro con google o facebook
                                <hr>
                                <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a>
                                -->

                            </form>
                            <hr>

                            <div class="text-center">
                                <a class="small" href="forgot-password.html">¿Olvidaste tu contraseña?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.html">¿Ya tienes una cuenta? Inicia sesión!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
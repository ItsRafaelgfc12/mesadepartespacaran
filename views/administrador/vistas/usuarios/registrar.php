<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-user-plus text-primary"></i> Registrar nuevo usuario
</h1>

<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <!-- DATOS PERSONALES -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-id-card"></i> Datos Personales
                    </h5>

                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-user"></i> Nombres</label>
                            <input type="text" class="form-control" name="nombres_usuario" placeholder="Ingrese nombres">
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-user"></i> Apellidos</label>
                            <input type="text" class="form-control" name="apellidos_usuario" placeholder="Ingrese apellidos">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-envelope"></i> Email Personal</label>
                            <input type="email" class="form-control" name="email_per">
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-envelope-open"></i> Email Institucional</label>
                            <input type="email" class="form-control" name="email_ins">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-phone"></i> Celular</label>
                            <input type="number" class="form-control" name="celular_usuario">
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-id-badge"></i> Tipo Documento</label>
                            <select class="form-control" name="tipo_documento">
                                <option value="">Seleccione</option>
                                <option value="DNI">DNI</option>
                                <option value="CE">Carné de Extranjería</option>
                                <option value="Pasaporte">Pasaporte</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-hashtag"></i> Número Documento</label>
                        <input type="number" class="form-control" name="numero_documento">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map"></i> Dirección</label>
                        <input type="text" class="form-control" name="direccion_usuario">
                    </div>
                </div>

                <!-- UBICACIÓN -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-map-marker-alt"></i> Ubicación
                    </h5>

                    <div class="form-row mt-3">
                        <div class="form-group col-md-4">
                            <label>Departamento</label>
                            <input type="text" class="form-control" name="id_dep">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Provincia</label>
                            <input type="text" class="form-control" name="id_prov">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Distrito</label>
                            <input type="text" class="form-control" name="id_dis">
                        </div>
                    </div>
                </div>

                <!-- ORGANIZACIONAL -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-briefcase"></i> Información Organizacional
                    </h5>

                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-user-tag"></i> Rol</label>
                            <select class="form-control" name="rol">
                                <option value="">Seleccione</option>
                                <option value="admin">Administrador</option>
                                <option value="docente">Docente</option>
                                <option value="estudiante">Estudiante</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-sitemap"></i> Área</label>
                            <input type="text" class="form-control" name="area">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-user-tie"></i> Cargo</label>
                            <input type="text" class="form-control" name="cargo">
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-graduation-cap"></i> Programa de Estudios</label>
                            <input type="text" class="form-control" name="programa_estudios">
                        </div>
                    </div>
                </div>

                <!-- ACCESO -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-lock"></i> Acceso al Sistema
                    </h5>

                    <div class="form-group mt-3">
                        <label><i class="fas fa-key"></i> Contraseña</label>
                        <input type="password" class="form-control" name="contrasena">
                    </div>
                </div>

                <!-- ARCHIVOS -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-folder-open"></i> Archivos
                    </h5>

                    <div class="form-group mt-3">
                        <label><i class="fas fa-image"></i> Foto Usuario</label>
                        <input type="file" class="form-control-file" name="url_foto_usuario">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Foto DNI</label>
                        <input type="file" class="form-control-file" name="url_dni_usuario">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-signature"></i> Firma</label>
                        <input type="file" class="form-control-file" name="url_firma">
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-between">
                    <a href="usuarios.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Usuario
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
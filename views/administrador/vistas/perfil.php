<div class="container-fluid">

    <!-- HEADER PERFIL -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex align-items-center">

            <div class="mr-4">
                <img src="../../img/undraw_profile_1.svg" class="rounded-circle" width="100">
            </div>

            <div>
                <h4 class="mb-1">
                    <?php echo $_SESSION["nombre"] . " " . $_SESSION["apellido"]; ?>
                </h4>

                <p class="text-muted mb-0">
                    <i class="fas fa-briefcase"></i> Área | Cargo | Programa
                </p>
            </div>

        </div>
    </div>

    <!-- FORMULARIO -->
    <div class="card shadow">
        <div class="card-body">

            <h5 class="text-primary mb-4">
                <i class="fas fa-user-edit"></i> Editar Información Personal
            </h5>

            <form action="actualizar_perfil.php" method="POST" enctype="multipart/form-data">

                <!-- DATOS PERSONALES -->
                <h6 class="text-primary border-bottom pb-2">
                    <i class="fas fa-id-card"></i> Datos Personales
                </h6>

                <div class="form-row mt-3">
                    <div class="form-group col-md-6">
                        <label>Nombres</label>
                        <input type="text" name="nombres" class="form-control" value="<?php echo $_SESSION['nombre']; ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="<?php echo $_SESSION['apellido']; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Email Personal</label>
                        <input type="email" name="email_personal" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Email Institucional</label>
                        <input type="email" name="email_institucional" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Celular</label>
                        <input type="text" name="celular" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Tipo Documento</label>
                        <select name="tipo_documento" class="form-control">
                            <option value="DNI">DNI</option>
                            <option value="CE">Carnet de Extranjería</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Número Documento</label>
                        <input type="text" name="numero_identidad" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion_usuario" class="form-control">
                </div>

                <!-- UBICACIÓN -->
                <h6 class="text-primary border-bottom pb-2 mt-4">
                    <i class="fas fa-map-marker-alt"></i> Ubicación
                </h6>

                <div class="form-row mt-3">
                    <div class="form-group col-md-4">
                        <label>Departamento</label>
                        <input type="text" name="departamento" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Provincia</label>
                        <input type="text" name="provincia" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Distrito</label>
                        <input type="text" name="distrito" class="form-control">
                    </div>
                </div>

                <!-- ARCHIVOS -->
                <h6 class="text-primary border-bottom pb-2 mt-4">
                    <i class="fas fa-folder-open"></i> Archivos
                </h6>

                <div class="form-row mt-3">

                    <div class="form-group col-md-4 text-center">
                        <label>Foto Usuario</label>
                        <input type="file" name="foto_usuario" class="form-control-file" onchange="previewImage(event,'preview1')">
                        <img id="preview1" class="img-fluid mt-2 rounded" width="120">
                    </div>

                    <div class="form-group col-md-4 text-center">
                        <label>Foto DNI</label>
                        <input type="file" name="foto_dni" class="form-control-file" onchange="previewImage(event,'preview2')">
                        <img id="preview2" class="img-fluid mt-2 rounded" width="120">
                    </div>

                    <div class="form-group col-md-4 text-center">
                        <label>Firma</label>
                        <input type="file" name="foto_firma" class="form-control-file" onchange="previewImage(event,'preview3')">
                        <img id="preview3" class="img-fluid mt-2 rounded" width="120">
                    </div>

                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#passwordModal">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="cambiar_password.php" method="POST" onsubmit="return validarPassword()">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Contraseña Actual</label>
                        <input type="password" name="password_actual" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Nueva Contraseña</label>
                        <input type="password" id="nueva" name="password_nueva" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" id="confirmar" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
function previewImage(event, id){
    const reader = new FileReader();
    reader.onload = () => {
        document.getElementById(id).src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function validarPassword(){
    const nueva = document.getElementById("nueva").value;
    const confirmar = document.getElementById("confirmar").value;

    if(nueva !== confirmar){
        alert("Las contraseñas no coinciden");
        return false;
    }

    if(nueva.length < 6){
        alert("Mínimo 6 caracteres");
        return false;
    }

    return true;
}
</script>
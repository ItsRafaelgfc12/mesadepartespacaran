<div class="perfil-view">
<header>
    <div class="imgcontainer">
        <img src="../../img/undraw_profile_1.svg" alt="Foto de perfil">
    </div>
    <div class="datos">
        <h1><?php echo $_SESSION["nombre"] . " " . $_SESSION["apellido"]; ?></h1>
        <p>
            Area | Cargo | Carrera
        </p>
    </div>
</header>

 <div class="perfil-container">

    <h2>Editar Información Personal</h2>

    <form action="actualizar_perfil.php" method="POST" enctype="multipart/form-data">

        <div class="form-row">
            <div class="form-group">
                <label>Nombres</label>
                <input type="text" name="nombres" value="<?php echo $_SESSION['nombre']; ?>" required>
            </div>

            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" value="<?php echo $_SESSION['apellido']; ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email Personal</label>
                <input type="email" name="email_personal">
            </div>

            <div class="form-group">
                <label>Email Institucional</label>
                <input type="email" name="email_institucional">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Celular</label>
                <input type="text" name="celular">
            </div>

            <div class="form-group">
                <label>Tipo Documento</label>
                <select name="tipo_documento">
                    <option value="DNI">DNI</option>
                    <option value="CE">Carnet de Extranjería</option>
                    <option value="Pasaporte">Pasaporte</option>
                </select>
            </div>

            <div class="form-group">
                <label>Número Identidad</label>
                <input type="text" name="numero_identidad">
            </div>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion_usuario">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Departamento</label>
                <input type="text" name="departamento">
            </div>

            <div class="form-group">
                <label>Provincia</label>
                <input type="text" name="provincia">
            </div>

            <div class="form-group">
                <label>Distrito</label>
                <input type="text" name="distrito">
            </div>
        </div>

        <h3>Archivos</h3>

        <div class="form-row">
            <div class="form-group">
                <label>Foto Usuario</label>
                <input type="file" name="foto_usuario" accept="image/*" onchange="previewImage(event,'preview1')">
                <img id="preview1" class="preview-img">
            </div>

            <div class="form-group">
                <label>Foto DNI</label>
                <input type="file" name="foto_dni" accept="image/*" onchange="previewImage(event,'preview2')">
                <img id="preview2" class="preview-img">
            </div>

            <div class="form-group">
                <label>Foto Firma</label>
                <input type="file" name="foto_firma" accept="image/*" onchange="previewImage(event,'preview3')">
                <img id="preview3" class="preview-img">
            </div>
        </div>

        <br>

        <button type="submit" class="btn">Guardar Cambios</button>
        <button type="button" class="btn btn-secondary" onclick="openModal()">Cambiar Contraseña</button>

    </form>


    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <h3>Cambiar Contraseña</h3>
            <form action="cambiar_password.php" method="POST" onsubmit="return validarPassword()">

                <div class="form-group">
                    <label>Contraseña Actual</label>
                    <input type="password" name="password_actual" required>
                </div>

                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" id="nueva" name="password_nueva" required>
                </div>

                <div class="form-group">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" id="confirmar" required>
                </div>

                <br>

                <button type="submit" class="btn">Actualizar</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>

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

function openModal(){
    document.getElementById("passwordModal").classList.add("active");
}

function closeModal(){
    document.getElementById("passwordModal").classList.remove("active");
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

<div class="container-fluid">

    <!-- HEADER PERFIL -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex align-items-center">

            <div class="mr-4">
                <img src="../../img/undraw_profile_1.svg" class="rounded-circle" width="100">
            </div>

            <div>
                <h4 class="mb-1" id="nombreCompleto">
    Cargando...
</h4>
                <p class="text-muted mb-0">
    <i class="fas fa-briefcase"></i> 
    <span id="infoLaboral">Cargando...</span>
</p>

<p class="text-muted mb-0">
    <i class="fas fa-graduation-cap"></i> 
    <span id="infoPrograma">Cargando...</span>
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

            <form id="formPerfil">

                <!-- DATOS PERSONALES -->
                <h6 class="text-primary border-bottom pb-2">
                    <i class="fas fa-id-card"></i> Datos Personales
                </h6>

                <div class="form-row mt-3">
                    <div class="form-group col-md-6">
                        <label>Nombres</label>
                        <input type="text" name="nombres" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" required>
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
                            <option value="1">DNI</option>
                            <option value="2">Carnet de Extranjería</option>
                            <option value="3">Pasaporte</option>
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
                        <select name="departamento" id="departamento" class="form-control"></select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Provincia</label>
                        <select name="provincia" id="provincia" class="form-control"></select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Distrito</label>
                        <select name="distrito" id="distrito" class="form-control"></select>
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

// CARGAR PERFIL COMPLETO
function cargarPerfil(){

    fetch('../../ajax/ajax_perfil_usuario.php?accion=perfil')
    .then(res => res.json())
    .then(data => {

        let u = data.usuario;

        // INPUTS
        document.querySelector('[name="nombres"]').value = u.nombres_usuario ?? '';
        document.querySelector('[name="apellidos"]').value = u.apellidos_usuario ?? '';
        document.querySelector('[name="email_personal"]').value = u.email_per ?? '';
        document.querySelector('[name="email_institucional"]').value = u.email_ins ?? '';
        document.querySelector('[name="celular"]').value = u.celular_usuario ?? '';
        document.querySelector('[name="tipo_documento"]').value = u.tipo_documento ?? '';
        document.querySelector('[name="numero_identidad"]').value = u.numero_documento ?? '';
        document.querySelector('[name="direccion_usuario"]').value = u.direccion_usuario ?? '';

        // IMÁGENES 
        const base = "/mesadepartespacaran/uploads/usuarios/";

        document.getElementById("preview1").src = u.url_foto_usuario 
            ? base + u.url_foto_usuario 
            : "../../img/undraw_profile_1.svg";

        document.getElementById("preview2").src = u.url_dni_usuario 
            ? base + u.url_dni_usuario 
            : "";

        document.getElementById("preview3").src = u.url_firma 
            ? base + u.url_firma 
            : "";

        // HEADER
        document.getElementById("nombreCompleto").innerText = 
            (u.nombres_usuario ?? '') + ' ' + (u.apellidos_usuario ?? '');

        let texto = (data.cargos || []).map(c => 
            c.nombre_area + " | " + c.cargo
        ).join(" / ");

        document.getElementById("infoLaboral").innerHTML = texto || 'Sin asignación';

        let programas = (data.programas || []).map(p => 
            p.programa_estudio
        ).join(" / ");

        document.getElementById("infoPrograma").innerHTML = programas || 'Sin programa asignado';

        cargarDepartamentos(u);

    });
}

// DEPARTAMENTOS
function cargarDepartamentos(u){

    fetch('../../ajax/ajax_ubigeo.php?accion=departamentos')
    .then(res => res.json())
    .then(data => {

        let dep = document.getElementById("departamento");
        dep.innerHTML = '<option value="">Seleccione</option>';

        data.forEach(d => {
            dep.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });

        if(u.id_dep){
            dep.value = u.id_dep;
            cargarProvincias(u.id_dep, u);
        }

    });
}

// PROVINCIAS
function cargarProvincias(id_dep, u = null){

    fetch('../../ajax/ajax_ubigeo.php?accion=provincias&dep=' + id_dep)
    .then(res => res.json())
    .then(data => {

        let prov = document.getElementById("provincia");
        prov.innerHTML = '<option value="">Seleccione</option>';

        data.forEach(p => {
            prov.innerHTML += `<option value="${p.id}">${p.name}</option>`;
        });

        if(u && u.id_prov){
            prov.value = u.id_prov;
            cargarDistritos(u.id_prov, u);
        }

    });
}

// DISTRITOS
function cargarDistritos(id_prov, u = null){

    fetch('../../ajax/ajax_ubigeo.php?accion=distritos&prov=' + id_prov)
    .then(res => res.json())
    .then(data => {

        let dist = document.getElementById("distrito");
        dist.innerHTML = '<option value="">Seleccione</option>';

        data.forEach(d => {
            dist.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });

        if(u && u.id_dis){
            dist.value = u.id_dis;
        }

    });
}

// SUBMIT
document.getElementById("formPerfil").addEventListener("submit", function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch('../../ajax/ajax_actualizar_perfil.php?accion=actualizar', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {

        if(res.status === "ok"){
            alert(res.mensaje);
            cargarPerfil();
        }

    })
    .catch(err => {
        console.error(err);
        alert("Error en la petición");
    });

});

// EVENTOS
document.addEventListener("DOMContentLoaded", function(){

    cargarPerfil();

    document.getElementById("departamento").addEventListener("change", function(){
        cargarProvincias(this.value);
    });

    document.getElementById("provincia").addEventListener("change", function(){
        cargarDistritos(this.value);
    });

});
</script>
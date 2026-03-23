<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-user-tag text-primary"></i> Gestión de Roles
</h1>

<div class="container">

    <!-- ACCIONES -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">

            <input type="text" id="buscador" class="form-control w-50" placeholder="Buscar rol...">

            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRol">
                <i class="fas fa-plus"></i> Nuevo Rol
            </button>

        </div>
    </div>
        <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover" id="tablaRoles">

                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Rol</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- EJEMPLO -->
                        <tr>
                            <td>001</td>
                            <td><strong>Administrador</strong></td>
                            <td>Acceso completo al sistema</td>
                            <td><span class="badge badge-success">Activo</span></td>

                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                    onclick="editarRol('1','Administrador','Acceso completo','activo')">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>002</td>
                            <td><strong>Usuario</strong></td>
                            <td>Acceso básico</td>
                            <td><span class="badge badge-secondary">Inactivo</span></td>

                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                    onclick="editarRol('2','Usuario','Acceso básico','inactivo')">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
<div class="modal fade" id="modalRol" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-tag"></i> Rol
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id_rol" id="id_rol">

                    <div class="form-group">
                        <label>Nombre del Rol</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="modalRol" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-tag"></i> Rol
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id_rol" id="id_rol">

                    <div class="form-group">
                        <label>Nombre del Rol</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
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
document.addEventListener("DOMContentLoaded", function() {

    // BUSCADOR
    document.getElementById("buscador").addEventListener("input", function(){
        let texto = this.value.toLowerCase();

        document.querySelectorAll("#tablaRoles tbody tr").forEach(fila => {
            fila.style.display = fila.innerText.toLowerCase().includes(texto) ? "" : "none";
        });
    });

    // GUARDAR
    document.querySelector("#modalRol form").addEventListener("submit", function(e){
        e.preventDefault();

        let formData = new FormData(this);

        fetch('../../ajax/ajax_rol.php?accion=guardar', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {

            $('#modalRol').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Rol guardado correctamente',
                timer: 2000,
                showConfirmButton: false
            });

            cargarRoles();
        });
    });

    // LIMPIAR MODAL
    $('#modalRol').on('hidden.bs.modal', function () {
        this.querySelector("form").reset();
        document.getElementById("id_rol").value = "";
    });

    // INICIAR
    cargarRoles();
});

// 🔥 GLOBAL
function cargarRoles() {
    fetch('../../ajax/ajax_rol.php?accion=listar')
    .then(res => res.json())
    .then(data => {

        let tabla = document.querySelector("#tablaRoles tbody");
        tabla.innerHTML = "";

        data.forEach((row, index) => {

            tabla.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${row.rol}</strong></td>
                    <td>${row.descripcion ?? '-'}</td>
                    <td>
                        ${row.estado == 1 
                        ? '<span class="badge badge-success">Activo</span>' 
                        : '<span class="badge badge-secondary">Inactivo</span>'}
                    </td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm"
                            onclick="editarRol('${row.id_rol}','${row.rol}','${row.descripcion ?? ''}','${row.estado}')">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm"
                            onclick="eliminarRol(${row.id_rol})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

    });
}

// EDITAR
function editarRol(id, nombre, descripcion, estado){

    document.getElementById("id_rol").value = id;
    document.getElementById("nombre").value = nombre;
    document.getElementById("descripcion").value = descripcion;

    document.getElementById("estado").value = (estado == 1) ? "activo" : "inactivo";

    $('#modalRol').modal('show');
}

// ELIMINAR
function eliminarRol(id){

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'No podrás revertir esta acción',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            fetch('../../ajax/ajax_rol.php?accion=eliminar&id=' + id)
            .then(res => res.json())
            .then(data => {

                Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: 'Rol eliminado correctamente',
                    timer: 1500,
                    showConfirmButton: false
                });

                cargarRoles();
            });

        }

    });
}
</script>
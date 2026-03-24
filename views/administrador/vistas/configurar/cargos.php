    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-id-badge text-primary"></i> Gestión de Cargos
    </h1>

    <div class="container-fluid">

        <!-- ACCIONES -->
        <div class="card shadow mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">

                <input type="text" id="buscador" class="form-control w-50" placeholder="Buscar cargo...">

                <button class="btn btn-primary" data-toggle="modal" data-target="#modalCargo">
                    <i class="fas fa-plus"></i> Nuevo Cargo
                </button>

            </div>
        </div>
            <div class="card shadow">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover" id="tablaCargos">

                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Cargo</th>
                                <th>Área</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
    <div class="modal fade" id="modalCargo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content"> 
                <form id="formCargo">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-id-badge"></i> Cargo
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id_cargo" id="id_cargo">

                        <div class="form-group">
                            <label>Nombre del Cargo</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Área</label>
                            <select name="id_area" id="id_area" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="1">Sistemas</option>
                                <option value="2">Administración</option>
                            </select>
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

            document.querySelectorAll("#tablaCargos tbody tr").forEach(fila => {
                fila.style.display = fila.innerText.toLowerCase().includes(texto) ? "" : "none";
            });
        });

        // GUARDAR
        document.querySelector("#modalCargo form").addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);

            fetch('../../ajax/ajax_cargo.php?accion=guardar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                $('#modalCargo').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Cargo guardado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });

                cargarCargos();
            });
        });

        // LIMPIAR MODAL
        $('#modalCargo').on('hidden.bs.modal', function () {
            this.querySelector("form").reset();
            document.getElementById("id_cargo").value = "";
        });

        // INICIAR
        cargarCargos();
    });

    // 🔥 GLOBAL
    function cargarCargos() {
        fetch('../../ajax/ajax_cargo.php?accion=listar')
        .then(res => res.json())
        .then(data => {

            console.log("DATA:", data); // 🔥 IMPORTANTE

            let tabla = document.querySelector("#tablaCargos tbody");
            tabla.innerHTML = "";

            data.forEach((row, index) => {
                tabla.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td><strong>${row.cargo}</strong></td>
                        <td>${row.nombre_area ?? '-'}</td>
                        <td>${row.descripcion ?? '-'}</td>
                        <td>
                            ${row.estado == 1 
                            ? '<span class="badge badge-success">Activo</span>' 
                            : '<span class="badge badge-secondary">Inactivo</span>'}
                        </td>
                        <td class="text-center">

                            <button class="btn btn-warning btn-sm"
                                onclick="editarCargo(
                                    '${row.id_cargo}',
                                    '${row.cargo}',
                                    '${row.id_area}',
                                    '${row.descripcion ?? ''}',
                                    '${row.estado}'
                                )">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-sm"
                                onclick="eliminarCargo(${row.id_cargo})">
                                <i class="fas fa-trash"></i>
                            </button>

                        </td>
                    </tr>
                `;
            });

        })
        .catch(error => {
            console.error("ERROR:", error);
        });
    }

    // EDITAR
    function editarCargo(id, nombre, id_area, descripcion, estado){

        document.getElementById("id_cargo").value = id;
        document.getElementById("nombre").value = nombre;
        document.getElementById("id_area").value = id_area;
        document.getElementById("descripcion").value = descripcion;
        document.getElementById("estado").value = (estado == 1) ? "activo" : "inactivo";

        $('#modalCargo').modal('show');
    }

    // ELIMINAR
    function eliminarCargo(id){

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

                fetch('../../ajax/ajax_cargo.php?accion=eliminar&id=' + id)
                .then(res => res.json())
                .then(data => {

                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'Cargo eliminado correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    cargarCargos();
                });

            }

        });
    }
    </script>
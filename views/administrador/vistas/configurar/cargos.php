<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-id-badge text-primary"></i> Gestión de Cargos
</h1>

<div class="container">

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

                        <!-- EJEMPLO -->
                        <tr>
                            <td>001</td>
                            <td><strong>Jefe de Sistemas</strong></td>
                            <td>Sistemas</td>
                            <td>Responsable del área TI</td>
                            <td><span class="badge badge-success">Activo</span></td>

                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                    onclick="editarCargo('1','Jefe de Sistemas','1','Responsable del área TI','activo')">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>002</td>
                            <td><strong>Asistente Administrativo</strong></td>
                            <td>Administración</td>
                            <td>Apoyo en gestión documental</td>
                            <td><span class="badge badge-secondary">Inactivo</span></td>

                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                    onclick="editarCargo('2','Asistente Administrativo','2','Apoyo administrativo','inactivo')">
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
<div class="modal fade" id="modalCargo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="guardar_cargo.php">

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
// BUSCADOR
document.getElementById("buscador").addEventListener("input", function(){
    let texto = this.value.toLowerCase();

    document.querySelectorAll("#tablaCargos tbody tr").forEach(fila => {
        fila.style.display = fila.innerText.toLowerCase().includes(texto) ? "" : "none";
    });
});

// EDITAR
function editarCargo(id, nombre, area, descripcion, estado){
    document.getElementById("id_cargo").value = id;
    document.getElementById("nombre").value = nombre;
    document.getElementById("id_area").value = area;
    document.getElementById("descripcion").value = descripcion;
    document.getElementById("estado").value = estado;

    $('#modalCargo').modal('show');
}

// LIMPIAR MODAL
$('#modalCargo').on('hidden.bs.modal', function () {
    document.getElementById("id_cargo").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("id_area").value = "";
    document.getElementById("descripcion").value = "";
    document.getElementById("estado").value = "activo";
});
</script>
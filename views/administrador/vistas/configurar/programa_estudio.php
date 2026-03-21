<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-graduation-cap text-primary"></i> Programas de Estudio
</h1>

<div class="container">

    <!-- ACCIONES -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">

            <input type="text" id="buscador" class="form-control w-50" placeholder="Buscar programa...">

            <button class="btn btn-primary" data-toggle="modal" data-target="#modalPrograma">
                <i class="fas fa-plus"></i> Nuevo Programa
            </button>

        </div>
    </div>
        <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover" id="tablaProgramas">

                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Programa</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- EJEMPLO -->
                        <tr>
                            <td>001</td>
                            <td><strong>Ingeniería de Sistemas</strong></td>
                            <td>Sistemas informáticos y desarrollo</td>
                            <td><span class="badge badge-success">Activo</span></td>

                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" onclick="editarPrograma('1','Ingeniería de Sistemas','Sistemas informáticos')">
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
<div class="modal fade" id="modalPrograma" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="guardar_programa.php">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-graduation-cap"></i> Programa de Estudio
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id_programa" id="id_programa">

                    <div class="form-group">
                        <label>Nombre del Programa</label>
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
// BUSCADOR
document.getElementById("buscador").addEventListener("input", function(){
    let texto = this.value.toLowerCase();

    document.querySelectorAll("#tablaProgramas tbody tr").forEach(fila => {
        fila.style.display = fila.innerText.toLowerCase().includes(texto) ? "" : "none";
    });
});

// EDITAR
function editarPrograma(id, nombre, descripcion){
    document.getElementById("id_programa").value = id;
    document.getElementById("nombre").value = nombre;
    document.getElementById("descripcion").value = descripcion;

    $('#modalPrograma').modal('show');
}

// LIMPIAR MODAL (cuando es nuevo)
$('#modalPrograma').on('hidden.bs.modal', function () {
    document.getElementById("id_programa").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("descripcion").value = "";
});
</script>
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-folder-open text-primary"></i> Administrar Plantillas
</h1>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div class="w-50">
                    <label class="mb-1">
                        <i class="fas fa-search"></i> Buscar plantilla
                    </label>
                    <input type="text" id="buscador" class="form-control" placeholder="Nombre, autor...">
                </div>

                <a href="crear_plantilla.php" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Nueva Plantilla
                </a>

            </div>

        </div>
    </div>

    <!-- TABLA -->
    <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaPlantillas">

                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Plantilla</th>
                            <th>Autor</th>
                            <th>Imagen</th>
                            <th>Archivo</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- EJEMPLO -->
                        <tr>
                            <td>001</td>

                            <td>
                                <strong>Plantilla Informe</strong><br>
                                <small class="text-muted">Formato institucional</small>
                            </td>

                            <td>Juan Pérez</td>

                            <!-- IMAGEN -->
                            <td>
                                <button class="btn btn-outline-primary btn-sm" title="Ver imagen">
                                    <i class="fas fa-image"></i>
                                </button>
                            </td>

                            <!-- ARCHIVO -->
                            <td>
                                <button class="btn btn-outline-success btn-sm" title="Descargar archivo">
                                    <i class="fas fa-file-download"></i>
                                </button>
                            </td>

                            <!-- ESTADO -->
                            <td>
                                <span class="badge badge-success">Activo</span>
                            </td>

                            <!-- ACCIONES -->
                            <td class="text-center">

                                <!-- Ver -->
                                <button class="btn btn-info btn-sm" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Editar -->
                                <button class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Eliminar -->
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </td>
                        </tr>

                        <tr>
                            <td>002</td>

                            <td>
                                <strong>Memorando</strong><br>
                                <small class="text-muted">Documento interno</small>
                            </td>

                            <td>María López</td>

                            <td>
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-image"></i>
                                </button>
                            </td>

                            <td>
                                <button class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-file-download"></i>
                                </button>
                            </td>

                            <td>
                                <span class="badge badge-secondary">Inactivo</span>
                            </td>

                            <td class="text-center">

                                <button class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button class="btn btn-warning btn-sm">
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
<script>
const buscador = document.getElementById("buscador");

buscador.addEventListener("input", () => {
    const filtro = buscador.value.toLowerCase();

    document.querySelectorAll("#tablaPlantillas tbody tr").forEach(fila => {
        const texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});
</script>
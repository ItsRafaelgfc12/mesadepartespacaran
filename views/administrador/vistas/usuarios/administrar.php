<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-users text-primary"></i> Administrar Usuarios
</h1>

<div class="container-fluid">

    <!-- FILTROS -->
    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-primary m-0">
                    <i class="fas fa-filter"></i> Filtros
                </h6>

                <a href="crear_usuario.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </a>
            </div>

            <div class="form-row">

                <div class="form-group col-md-3">
                    <label><i class="fas fa-search"></i> Buscar</label>
                    <input type="text" id="buscador" class="form-control" placeholder="Nombre, correo...">
                </div>

                <div class="form-group col-md-2">
                    <label><i class="fas fa-user-tag"></i> Rol</label>
                    <select id="filtroRol" class="form-control">
                        <option value="">Todos</option>
                        <option>Administrador</option>
                        <option>Usuario</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label><i class="fas fa-sitemap"></i> Área</label>
                    <select id="filtroArea" class="form-control">
                        <option value="">Todas</option>
                        <option>RRHH</option>
                        <option>Legal</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label><i class="fas fa-user-tie"></i> Cargo</label>
                    <select id="filtroCargo" class="form-control">
                        <option value="">Todos</option>
                        <option>Jefe</option>
                        <option>Asistente</option>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label><i class="fas fa-graduation-cap"></i> Programa</label>
                    <select id="filtroPrograma" class="form-control">
                        <option value="">Todos</option>
                        <option>Sistemas</option>
                        <option>Administración</option>
                    </select>
                </div>

            </div>

        </div>
    </div>

    <!-- TABLA -->
    <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaUsuarios">

                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Área</th>
                            <th>Cargo</th>
                            <th>Programa</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- EJEMPLO -->
                        <tr>
                            <td>001</td>

                            <td>
                                <strong>Juan Pérez</strong><br>
                                <small class="text-muted">juan@email.com</small>
                            </td>

                            <td><span class="badge badge-primary">Administrador</span></td>
                            <td>RRHH</td>
                            <td>Jefe</td>
                            <td>Sistemas</td>

                            <td>
                                <span class="badge badge-success">Activo</span>
                            </td>

                            <td class="text-center">

                                <!-- Ver -->
                                <button class="btn btn-info btn-sm" title="Ver">
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
                                <strong>María López</strong><br>
                                <small class="text-muted">maria@email.com</small>
                            </td>

                            <td><span class="badge badge-secondary">Usuario</span></td>
                            <td>Legal</td>
                            <td>Asistente</td>
                            <td>Administración</td>

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
const filtroRol = document.getElementById("filtroRol");
const filtroArea = document.getElementById("filtroArea");
const filtroCargo = document.getElementById("filtroCargo");
const filtroPrograma = document.getElementById("filtroPrograma");

function filtrarTabla() {
    const texto = buscador.value.toLowerCase();
    const rol = filtroRol.value.toLowerCase();
    const area = filtroArea.value.toLowerCase();
    const cargo = filtroCargo.value.toLowerCase();
    const programa = filtroPrograma.value.toLowerCase();

    document.querySelectorAll("#tablaUsuarios tbody tr").forEach(fila => {
        const contenido = fila.innerText.toLowerCase();

        const visible =
            contenido.includes(texto) &&
            (!rol || contenido.includes(rol)) &&
            (!area || contenido.includes(area)) &&
            (!cargo || contenido.includes(cargo)) &&
            (!programa || contenido.includes(programa));

        fila.style.display = visible ? "" : "none";
    });
}

[buscador, filtroRol, filtroArea, filtroCargo, filtroPrograma]
    .forEach(el => el.addEventListener("input", filtrarTabla));
</script>
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-folder text-primary"></i> Gestión de Documentos
</h1>

<div class="container">

    <!-- NAV TABS -->
    <ul class="nav nav-tabs mb-4" id="tabsDocumentos">

        <li class="nav-item">
            <a class="nav-link active" href="#" onclick="mostrar('recibidos', this)">
                <i class="fas fa-inbox"></i> Recibidos
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" onclick="mostrar('pendientes', this)">
                <i class="fas fa-clock"></i> Pendientes
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" onclick="mostrar('archivo', this)">
                <i class="fas fa-archive"></i> Archivo
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" onclick="mostrar('atendidos', this)">
                <i class="fas fa-check-circle"></i> Atendidos
            </a>
        </li>

    </ul>

    <!-- RECIBIDOS -->
    <div id="recibidos" class="seccion">

        <div class="card shadow mb-4">
            <div class="card-body">

                <h5 class="text-primary mb-3">
                    <i class="fas fa-inbox"></i> Documentos Recibidos
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover">

                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Documento</th>
                                <th>Fecha</th>
                                <th>Remitente</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>001</td>
                                <td>Informe Legal</td>
                                <td>20/03/2026</td>
                                <td>Juan Pérez</td>

                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button class="btn btn-warning btn-sm" title="Marcar pendiente">
                                        <i class="fas fa-clock"></i>
                                    </button>

                                    <button class="btn btn-success btn-sm" title="Atender">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

    <!-- PENDIENTES -->
    <div id="pendientes" class="seccion d-none">

        <div class="card shadow mb-4">
            <div class="card-body">

                <h5 class="text-warning mb-3">
                    <i class="fas fa-clock"></i> Pendientes Temporales
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover">

                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Documento</th>
                                <th>Motivo</th>
                                <th>Fecha Límite</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>001</td>
                                <td>Informe Legal</td>
                                <td>Revisión</td>
                                <td>25/03/2026</td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

    <!-- ARCHIVO -->
    <div id="archivo" class="seccion d-none">

        <div class="card shadow mb-4">
            <div class="card-body text-center">

                <h5 class="text-secondary">
                    <i class="fas fa-archive"></i> Archivo
                </h5>

                <p class="text-muted">Aquí se mostrarán los documentos archivados.</p>

            </div>
        </div>

    </div>

    <!-- ATENDIDOS -->
    <div id="atendidos" class="seccion d-none">

        <div class="card shadow mb-4">
            <div class="card-body text-center">

                <h5 class="text-success">
                    <i class="fas fa-check-circle"></i> Documentos Atendidos
                </h5>

                <p class="text-muted">Aquí se listan los documentos ya gestionados.</p>

            </div>
        </div>

    </div>

</div>
<script>
function mostrar(seccionId, elemento) {

    // Ocultar todas las secciones
    document.querySelectorAll('.seccion').forEach(sec => {
        sec.classList.add('d-none');
    });

    // Mostrar la seleccionada
    document.getElementById(seccionId).classList.remove('d-none');

    // Manejar active en tabs
    document.querySelectorAll('#tabsDocumentos .nav-link').forEach(tab => {
        tab.classList.remove('active');
    });

    elemento.classList.add('active');
}
</script>
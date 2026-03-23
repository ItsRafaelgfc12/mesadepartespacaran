<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary">
            <i class="fas fa-calendar-alt"></i> Gestión de Eventos
        </h4>

        <a href="home.php?vista=eventos/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Evento
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- EJEMPLO -->
                        <tr>
                            <td>1</td>

                            <td>
                                <img src="img/evento1.jpg" width="80" class="img-thumbnail">
                            </td>

                            <td>Conferencia Tech</td>

                            <td style="max-width:200px;">
                                Evento sobre innovación tecnológica...
                            </td>

                            <td>
                                <strong>Auditorio</strong><br>
                                <a href="#" target="_blank">Ver ubicación</a>
                            </td>

                            <td>25/04/2026</td>
                            <td>10:00 AM</td>

                            <td>
                                <span class="badge badge-success">Activo</span>
                            </td>

                            <td>

                                <!-- Ver asistentes -->
                                <a href="asistentes_evento.php?id=1" class="btn btn-info btn-sm" title="Ver asistentes">
                                    <i class="fas fa-users"></i>
                                </a>

                                <!-- Editar -->
                                <a href="editar_evento.php?id=1" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Eliminar -->
                                <button class="btn btn-danger btn-sm" title="Eliminar">
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

<div class="container mt-4">

    <h4 class="text-primary mb-3">
        <i class="fas fa-users"></i> Lista de Asistentes
    </h4>

    <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered">

                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Fecha de inscripción</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- EJEMPLO -->
                        <tr>
                            <td>1</td>
                            <td>Juan Pérez</td>
                            <td>juan@email.com</td>
                            <td>987654321</td>
                            <td>20/03/2026</td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
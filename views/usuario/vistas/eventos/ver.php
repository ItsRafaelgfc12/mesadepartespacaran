
    <style>
        .card-evento {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-evento:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }

        .img-evento {
            height: 200px;
            object-fit: cover;
        }
    </style>

<div class="container mt-5">

    <div class="text-center mb-4">
        <h2 class="text-primary">Eventos Disponibles</h2>
        <p class="text-muted">Inscríbete en los eventos activos</p>
    </div>

    <div class="row">

        <!-- CARD EVENTO -->
        <div class="col-md-4 mb-4">
            <div class="card card-evento shadow">

                <img src="../../img/plantilla_evento2.jpg" class="card-img-top img-evento" alt="Evento">

                <div class="card-body">
                    <h5 class="card-title">Conferencia de Tecnología</h5>

                    <p class="card-text">
                        Evento sobre tendencias tecnológicas, innovación y desarrollo de software.
                    </p>

                    <p class="mb-1">
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        Auditorio Principal
                    </p>

                    <p class="mb-1">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        25/04/2026
                    </p>

                    <p class="mb-3">
                        <i class="fas fa-clock text-success"></i>
                        10:00 AM
                    </p>

                    <button class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Inscribirse
                    </button>
                </div>

            </div>
        </div>

        <!-- CARD EVENTO -->
        <div class="col-md-4 mb-4">
            <div class="card card-evento shadow">

                <img src="../../img/plantilla_evento.jpg" class="card-img-top img-evento" alt="Evento">

                <div class="card-body">
                    <h5 class="card-title">Taller de Emprendimiento</h5>

                    <p class="card-text">
                        Aprende a crear tu propio negocio desde cero con expertos.
                    </p>

                    <p class="mb-1">
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        Sala 2 - Innovación
                    </p>

                    <p class="mb-1">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        28/04/2026
                    </p>

                    <p class="mb-3">
                        <i class="fas fa-clock text-success"></i>
                        3:00 PM
                    </p>

                    <button class="btn btn-success btn-block">
                        <i class="fas fa-user-plus"></i> Inscribirse
                    </button>
                </div>

            </div>
        </div>

        <!-- CARD EVENTO -->
        <div class="col-md-4 mb-4">
            <div class="card card-evento shadow">

                <img src="../../img/plantilla_evento3.jpg" class="card-img-top img-evento" alt="Evento">

                <div class="card-body">
                    <h5 class="card-title">Seminario de Marketing</h5>

                    <p class="card-text">
                        Estrategias digitales para posicionar tu marca en redes sociales.
                    </p>

                    <p class="mb-1">
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        Aula Magna
                    </p>

                    <p class="mb-1">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        30/04/2026
                    </p>

                    <p class="mb-3">
                        <i class="fas fa-clock text-success"></i>
                        6:00 PM
                    </p>

                    <button class="btn btn-info btn-block">
                        <i class="fas fa-user-plus"></i> Inscribirse
                    </button>
                </div>

            </div>
        </div>

    </div>

</div>
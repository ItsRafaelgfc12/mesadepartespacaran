<div class="container-fluid">

    <div class="card shadow">
        <div class="card-body">

            <h4 class="text-primary mb-4">
                <i class="fas fa-calendar-plus"></i> Crear Evento
            </h4>

            <form method="POST" enctype="multipart/form-data">

                <!-- Título -->
                <div class="form-group">
                    <label><i class="fas fa-heading"></i> Título del Evento</label>
                    <input type="text" name="titulo" class="form-control" placeholder="Ingrese el título">
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Descripción</label>
                    <textarea name="descripcion" rows="4" class="form-control" placeholder="Descripción del evento"></textarea>
                </div>

                <!-- Ubicación -->
                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label><i class="fas fa-map-marker-alt"></i> Nombre del Lugar</label>
                        <input type="text" name="ubicacion_nombre" class="form-control" placeholder="Ej: Auditorio Central">
                    </div>

                    <div class="form-group col-md-6">
                        <label><i class="fas fa-link"></i> Link de Ubicación</label>
                        <input type="url" name="ubicacion_link" class="form-control" placeholder="https://maps.google.com/...">
                    </div>

                </div>

                <!-- Fecha y Hora -->
                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label><i class="fas fa-calendar-alt"></i> Fecha</label>
                        <input type="date" name="fecha" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label><i class="fas fa-clock"></i> Hora</label>
                        <input type="time" name="hora" class="form-control">
                    </div>

                </div>

                <!-- Imagen -->
                <div class="form-group">
                    <label><i class="fas fa-image"></i> Imagen del Evento</label>
                    <input type="file" name="imagen" class="form-control-file">
                </div>

                <!-- Botones -->
                <div class="text-right">
                    <a href="eventos.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Evento
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
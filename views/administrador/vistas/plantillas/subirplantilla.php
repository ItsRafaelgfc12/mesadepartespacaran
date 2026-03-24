<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-file-upload text-primary"></i> Subir Nueva Plantilla
</h1>

<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-body">

            <form action="#" method="POST" enctype="multipart/form-data">

                <!-- INFORMACIÓN GENERAL -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-info-circle"></i> Información de la Plantilla
                    </h5>

                    <div class="form-group mt-3">
                        <label><i class="fas fa-heading"></i> Título</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ej: Plantilla de Informe" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe la plantilla..." required></textarea>
                    </div>
                </div>

                <!-- ARCHIVOS -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-folder-open"></i> Archivos
                    </h5>

                    <div class="form-group mt-3">
                        <label><i class="fas fa-image"></i> Captura de Imagen</label>
                        <input type="file" name="imagen" class="form-control-file" accept="image/*" required>
                        <small class="form-text text-muted">
                            Imagen referencial de la plantilla (JPG, PNG).
                        </small>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-file"></i> Archivo de la Plantilla</label>
                        <input type="file" name="archivo" class="form-control-file" required>
                        <small class="form-text text-muted">
                            Puede ser Word, PDF, Excel, etc.
                        </small>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-between">
                    <a href="plantillas.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Subir Plantilla
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
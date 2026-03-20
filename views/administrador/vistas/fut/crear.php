<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-file-alt text-primary"></i> Crear FUT
</h1>

<div class="container">

    <div class="card shadow mb-4">
        <div class="card-body">

            <h5 class="text-center font-weight-bold mb-4">
                FORMATO ÚNICO DE TRÁMITE (FUT)
            </h5>

            <form action="#" method="POST" enctype="multipart/form-data">

                <!-- DEPENDENCIA -->
                <div class="form-group">
                    <label><i class="fas fa-building"></i> Dependencia o autoridad</label>
                    <input type="text" name="dependencia" class="form-control" placeholder="Ej: Dirección Académica" required>
                </div>

                <!-- DATOS DEL SOLICITANTE -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-user"></i> 1. Datos del Solicitante
                    </h5>

                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label>Nombres</label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-id-badge"></i> Tipo de Documento</label>
                            <select name="tipo_documento" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="dni">DNI</option>
                                <option value="ce">Carné de Extranjería</option>
                                <option value="pasaporte">Pasaporte</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-hashtag"></i> Número de Documento</label>
                            <input type="text" name="numero_documento" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map"></i> Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-envelope"></i> Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- DETALLE -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-file-signature"></i> 2. Detalle de la Solicitud
                    </h5>

                    <div class="form-group mt-3">
                        <label><i class="fas fa-heading"></i> Asunto</label>
                        <input type="text" name="asunto" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Descripción</label>
                        <textarea name="descripcion" rows="5" class="form-control" placeholder="Detalle su solicitud..." required></textarea>
                    </div>
                </div>

                <!-- FECHA Y ARCHIVOS -->
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-paperclip"></i> Información Adicional
                    </h5>

                    <div class="form-group mt-3">
                        <label><i class="fas fa-calendar-alt"></i> Fecha</label>
                        <input type="date" name="fecha" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-file-pdf"></i> Documentos anexados (PDF)
                        </label>

                        <input type="file" name="doc_anexado" class="form-control-file">

                        <small class="form-text text-muted">
                            Todos los documentos deben estar en un solo archivo PDF.
                            <a href="https://www.ilovepdf.com/es/unir_pdf" target="_blank">
                                Unir PDF aquí
                            </a>
                        </small>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-between">
                    <a href="fut_listado.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
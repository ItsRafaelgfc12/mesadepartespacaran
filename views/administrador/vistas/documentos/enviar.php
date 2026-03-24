<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-file-alt text-primary"></i> Nuevo Documento
</h1>

<div class="container-fluid">

    <div class="card shadow">
        <div class="card-body">

            <form action="guardar_documento.php" method="POST" enctype="multipart/form-data">

                <!-- DATOS DEL DOCUMENTO -->
                <h6 class="text-primary border-bottom pb-2">
                    <i class="fas fa-info-circle"></i> Información del Documento
                </h6>

                <div class="form-group mt-3">
                    <label>Código Documento</label>
                    <input type="text" name="codigo_documento" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Asunto</label>
                    <input type="text" name="asunto" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label>Archivo</label>
                    <input type="file" name="url_doc" class="form-control-file">
                </div>

                <hr>

                <!-- TIPO DE ENVÍO -->
                <h6 class="text-primary border-bottom pb-2">
                    <i class="fas fa-share"></i> Tipo de Envío
                </h6>

                <div class="form-group mt-3">
                    <label>Enviar a:</label>
                    <select name="tipo_envio" id="tipo_envio" class="form-control" onchange="mostrarDestino()" required>
                        <option value="">Seleccione</option>
                        <option value="usuario">Usuario</option>
                        <option value="area">Área</option>
                        <option value="programa">Programa</option>
                        <option value="rol">Rol</option>
                        <option value="cargo">Cargo</option>
                    </select>
                </div>

                <!-- DESTINOS DINÁMICOS -->

                <div id="destino_usuario" class="destino d-none">
                    <label>Seleccionar Usuario</label>
                    <select name="id_usuario_destino" class="form-control">
                        <option value="">Seleccione usuario</option>
                        <!-- foreach usuarios -->
                    </select>
                </div>

                <div id="destino_area" class="destino d-none">
                    <label>Seleccionar Área</label>
                    <select name="id_area_destino" class="form-control">
                        <option value="">Seleccione área</option>
                    </select>
                </div>

                <div id="destino_programa" class="destino d-none">
                    <label>Seleccionar Programa</label>
                    <select name="id_programa_destino" class="form-control">
                        <option value="">Seleccione programa</option>
                    </select>
                </div>

                <div id="destino_rol" class="destino d-none">
                    <label>Seleccionar Rol</label>
                    <select name="id_rol_destino" class="form-control">
                        <option value="">Seleccione rol</option>
                    </select>
                </div>

                <div id="destino_cargo" class="destino d-none">
                    <label>Seleccionar Cargo</label>
                    <select name="id_cargo_destino" class="form-control">
                        <option value="">Seleccione cargo</option>
                    </select>
                </div>

                <br>

                <!-- BOTÓN -->
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Documento
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
function mostrarDestino() {
    const tipo = document.getElementById("tipo_envio").value;

    // Ocultar todos
    document.querySelectorAll('.destino').forEach(div => {
        div.classList.add('d-none');
    });

    // Mostrar el seleccionado
    if(tipo){
        document.getElementById("destino_" + tipo).classList.remove('d-none');
    }
}
</script>
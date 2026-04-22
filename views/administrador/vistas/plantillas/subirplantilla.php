<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-file-upload text-primary"></i> Subir Nueva Plantilla
</h1>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="formSubirPlantilla" enctype="multipart/form-data">
                
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

                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-lock"></i> Permisos de Acceso
                    </h5>
                    <div class="row mt-3">
                        <div class="col-md-6 form-group">
                            <label>¿Quién puede ver y usar esta plantilla?</label>
                            <select name="tipo_acceso" class="form-control" onchange="cargarDestinosPlantilla(this)" required>
                                <option value="publico">Público (Todos los usuarios)</option>
                                <option value="rol">Por Rol (Ej. Solo Docentes)</option>
                                <option value="area">Por Área (Ej. Solo Dirección)</option>
                                <option value="cargo">Por Cargo</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group" id="div_referencia" style="display:none;">
                            <label>Seleccionar Destino</label>
                            <select name="id_referencia" id="select_referencia" class="form-control">
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-folder-open"></i> Archivos
                    </h5>
                    <div class="row">
                        <div class="col-md-6 form-group mt-3">
                            <label><i class="fas fa-image"></i> Captura de Imagen (Previsualización)</label>
                            <input type="file" name="imagen" class="form-control-file border p-2 rounded" accept="image/jpeg, image/png, image/webp" required>
                            <small class="form-text text-muted">Formatos: JPG, PNG, WEBP.</small>
                            <img id="preview" src="#" alt="Vista previa" style="display:none; width: 100%; max-height: 200px; object-fit: contain;" class="mt-2 border p-1 bg-light">
                        </div>
                        <div class="col-md-6 form-group mt-3">
                            <label><i class="fas fa-file-word"></i> Archivo de la Plantilla</label>
                            <input type="file" name="archivo" class="form-control-file border p-2 rounded" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf" required>
                            <small class="form-text text-muted">Formatos: Word, Excel, PowerPoint, PDF.</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSubir">
                        <i class="fas fa-upload"></i> Subir Plantilla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelector('input[name="imagen"]').onchange = evt => {
    const [file] = evt.target.files;
    if (file) {
        document.getElementById('preview').src = URL.createObjectURL(file);
        document.getElementById('preview').style.display = 'block';
    }
}

function cargarDestinosPlantilla(selectElement) {
    const tipo = selectElement.value;
    const divRef = document.getElementById('div_referencia');
    const selectRef = document.getElementById('select_referencia');

    if (tipo === 'publico') {
        divRef.style.display = 'none';
        selectRef.required = false;
        selectRef.innerHTML = '<option value="">Seleccione...</option>';
        return;
    }

    divRef.style.display = 'block';
    selectRef.required = true;

    fetch(`../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=${tipo}`)
    .then(res => res.json())
    .then(data => {
        selectRef.innerHTML = '<option value="">Seleccione...</option>';
        data.forEach(item => {
            selectRef.innerHTML += `<option value="${item.id}">${item.nombre}</option>`;
        });
    })
    .catch(err => console.error("Error cargando destinos:", err));
}

document.getElementById('formSubirPlantilla').onsubmit = function(e) {
    e.preventDefault();
    let btn = document.getElementById('btnSubir');
    let textoOriginal = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subiendo archivos...';

    fetch('../../ajax/ajax_plantillas.php?accion=subir', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = textoOriginal;

        if (data.status === "ok") {
            Swal.fire({
                title: '¡Plantilla Subida!',
                text: data.mensaje,
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#28a745',
                confirmButtonText: '<i class="fas fa-folder-open"></i> Ir al Directorio',
                cancelButtonText: '<i class="fas fa-plus"></i> Subir Otra',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'home.php?vista=plantillas/ver'; 
                } else {
                    document.getElementById('formSubirPlantilla').reset();
                    document.getElementById('preview').style.display = 'none';
                    document.getElementById('div_referencia').style.display = 'none';
                }
            });
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = textoOriginal;
        Swal.fire('Error', 'Problema de conexión con el servidor.', 'error');
    });
};
</script>
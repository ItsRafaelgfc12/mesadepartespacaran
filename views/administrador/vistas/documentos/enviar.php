<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-file-alt text-primary"></i> Nuevo Documento
</h1>

<div class="container-fluid">
    <div class="card shadow">
        <div class="card-body">
            <form id="formNuevoDocumento" enctype="multipart/form-data">
                <h6 class="text-primary border-bottom pb-2">
                    <i class="fas fa-info-circle"></i> Información del Documento
                </h6>

                <div class="row">
                    <div class="col-md-4 form-group mt-3">
                        <label>Tipo de Documento</label>
                        <select name="id_tipo" id="id_tipo_doc" class="form-control" required>
                            <option value="">Cargando tipos...</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group mt-3">
                        <label>Código / Nro de Documento</label>
                        <input type="text" name="codigo_documento" class="form-control" placeholder="Ej: OFICIO-001-2026" required>
                    </div>
                    
                    <div class="col-md-4 form-group mt-3">
                        <label>Asunto</label>
                        <input type="text" name="asunto" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Descripción / Proveído</label>
                    <textarea name="descripcion" class="form-control" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label>Archivo Principal</label>
                    <input type="file" name="url_doc" class="form-control-file" accept=".pdf, .zip, .rar, .7z" required>
                    <small class="text-muted">Formatos permitidos: PDF, ZIP, RAR o 7Z.</small>
                </div>

                <hr>

                <h6 class="text-primary border-bottom pb-2">
                    <i class="fas fa-share"></i> Destinatario
                </h6>

                <div class="row">
                    <div class="col-md-6 form-group mt-3">
                        <label>Enviar a:</label>
                        <select name="tipo_envio" id="tipo_envio" class="form-control" onchange="cargarDestinosInternos(this.value)" required>
                            <option value="">Seleccione tipo</option>
                            <option value="usuario">Usuario Directo</option>
                            <option value="area">Área / Oficina</option>
                            <option value="programa">Programa de Estudios</option>
                            <option value="rol">Rol del Sistema</option>
                            <option value="cargo">Cargo Específico</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group mt-3">
                        <label>Seleccionar Destino</label>
                        <select name="id_destino" id="id_destino" class="form-control" required>
                            <option value="">Primero seleccione tipo...</option>
                        </select>
                    </div>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" id="btnEnviarDoc" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Registrar y Enviar Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para cargar los tipos de documento (Excepto FUT que es ID 1)
function cargarTiposDocumento() {
    const select = document.getElementById('id_tipo_doc');
    
    // Usaremos un nuevo endpoint en utilitarios que debemos crear
    fetch('../../ajax/ajax_utilitarios.php?accion=listar_tipos_doc_internos')
    .then(res => res.json())
    .then(data => {
        select.innerHTML = '<option value="">Seleccione tipo...</option>';
        data.forEach(tipo => {
            select.innerHTML += `<option value="${tipo.id_tipo}">${tipo.nombre}</option>`;
        });
    })
    .catch(err => {
        select.innerHTML = '<option value="">Error al cargar</option>';
    });
}

// Ejecutar al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    cargarTiposDocumento();
});

// Reutilizamos la lógica de carga dinámica que ya tenemos en utilitarios
function cargarDestinosInternos(tipo) {
    const select = document.getElementById('id_destino');
    if(!tipo) { 
        select.innerHTML = '<option value="">Primero seleccione tipo...</option>'; 
        return; 
    }
    
    fetch(`../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=${tipo}`)
    .then(res => res.json())
    .then(data => {
        select.innerHTML = '<option value="">Seleccione el destinatario</option>';
        data.forEach(item => {
            select.innerHTML += `<option value="${item.id}">${item.nombre}</option>`;
        });
    });
}

document.getElementById("formNuevoDocumento").onsubmit = function(e) {
    e.preventDefault();
    const btn = document.getElementById("btnEnviarDoc");
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

    let formData = new FormData(this);  

    fetch('../../ajax/ajax_gestion_documentos.php?accion=registrar_documento_interno', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Registrar y Enviar Documento';
        
        if (data.status === "ok") {
            Swal.fire('¡Éxito!', data.mensaje, 'success').then(() => {
                location.reload(); // O redirigir a la lista de enviados
            });
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        console.error(err);
    });
};
</script>
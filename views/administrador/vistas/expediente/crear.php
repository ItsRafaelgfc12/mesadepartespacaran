<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-folder-plus text-primary"></i> Crear Nuevo Expediente
</h1>

<div class="container-fluid">
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            
            <form id="formCrearExpediente" enctype="multipart/form-data">
                
                <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3">
                    <i class="fas fa-info-circle"></i> Datos del Expediente
                </h6>
                
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Código del Expediente</label>
                        <input type="text" name="codigo_expediente" class="form-control" placeholder="Ej: EXP-2026-0001" required>
                        <small class="text-muted">Debe ser único en el sistema.</small>
                    </div>
                    
                    <div class="col-md-8 form-group">
                        <label>Asunto / Título</label>
                        <input type="text" name="asunto" class="form-control" placeholder="Descripción clara del expediente..." required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Nivel de Privacidad</label>
                        <select name="tipo" id="tipo_privacidad" class="form-control" required>
                            <option value="privado">Privado (Solo yo)</option>
                            <option value="publico">Público (Todos pueden ver)</option>
                            <option value="compartido">Compartido (Accesos específicos)</option>
                        </select>
                    </div>
                </div>

                <div id="panel_compartido" class="p-3 mb-3 bg-light border rounded" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-weight-bold text-dark m-0"><i class="fas fa-users-cog"></i> Asignar Accesos</h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="agregarFilaAcceso()">
                            <i class="fas fa-plus"></i> Agregar Acceso
                        </button>
                    </div>
                    
                    <div id="contenedor_accesos">
                        <div class="row fila-acceso align-items-end mb-2">
                            <div class="col-md-3 form-group mb-0">
                                <label>Tipo de Destino</label>
                                <select name="tipo_acceso[]" class="form-control input-acceso" onchange="cargarDestinosExpediente(this)">
                                    <option value="">Seleccione...</option>
                                    <option value="area">Área / Oficina</option>
                                    <option value="cargo">Cargo Específico</option>
                                    <option value="rol">Rol del Sistema</option>
                                    <option value="usuario">Usuario Directo</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-0">
                                <label>Seleccionar</label>
                                <select name="id_referencia[]" class="form-control select-referencia input-acceso">
                                    <option value="">Primero elija tipo...</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group mb-0">
                                <label>Permiso</label>
                                <select name="permiso[]" class="form-control input-acceso">
                                    <option value="lectura">Lectura (Solo ver)</option>
                                    <option value="edicion">Edición (Subir versiones)</option>
                                    <option value="administrador">Administrador</option>
                                </select>
                            </div>
                            <div class="col-md-2 form-group mb-0">
                                <button type="button" class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-lock"></i> Base
                                </button>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">Añade tantas filas como áreas o usuarios necesiten acceso inicial.</small>
                </div>
                <hr>

                <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3 mt-4">
                    <i class="fas fa-file-upload"></i> Documento Inicial (Versión 1)
                </h6>
                <div class="row">
                    <div class="col-md-5 form-group">
                        <label>Nombre del Documento Lógico</label>
                        <input type="text" name="nombre_documento" class="form-control" placeholder="Ej: Informe Técnico Base">
                        <small class="text-muted">Deje en blanco si aún no subirá archivos.</small>
                    </div>
                    <div class="col-md-7 form-group">
                        <label>Archivo Físico (PDF, ZIP, RAR, DOCX)</label>
                        <input type="file" name="archivo_version" class="form-control-file" accept=".pdf,.doc,.docx,.zip,.rar,.7z">
                    </div>
                </div>
                <div class="form-group">
                    <label>Comentario de la Versión</label>
                    <textarea name="comentario_version" class="form-control" rows="2" placeholder="Ej: Se adjunta el borrador inicial para revisión..."></textarea>
                </div>

                <div class="text-right mt-4">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                    <button type="submit" id="btnGuardarExpediente" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Expediente
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
// ==========================================
// 1. MANEJO DE LA UI (Nivel de Privacidad)
// ==========================================
document.getElementById('tipo_privacidad').addEventListener('change', function() {
    const panel = document.getElementById('panel_compartido');
    const inputsAcceso = document.querySelectorAll('.input-acceso');

    if (this.value === 'compartido') {
        panel.style.display = 'block';
        inputsAcceso.forEach(input => input.required = true);
    } else {
        panel.style.display = 'none';
        inputsAcceso.forEach(input => input.required = false);
    }
});

// ==========================================
// 2. CARGAR DESTINOS DINÁMICOS
// ==========================================
function cargarDestinosExpediente(selectTipo) {
    const tipo = selectTipo.value;
    const selectReferencia = selectTipo.closest('.row').querySelector('.select-referencia');
    
    if(!tipo) { 
        selectReferencia.innerHTML = '<option value="">Primero elija tipo...</option>'; 
        return; 
    }

    fetch(`../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=${tipo}`)
    .then(res => res.json())
    .then(data => {
        selectReferencia.innerHTML = '<option value="">Seleccione...</option>';
        data.forEach(item => { 
            selectReferencia.innerHTML += `<option value="${item.id}">${item.nombre}</option>`; 
        });
    })
    .catch(err => {
        console.error("Error al cargar destinos:", err);
        selectReferencia.innerHTML = '<option value="">Error de carga</option>';
    });
}

// ==========================================
// 3. AGREGAR / ELIMINAR FILAS DE ACCESO
// ==========================================
function agregarFilaAcceso() {
    const contenedor = document.getElementById('contenedor_accesos');
    const nuevaFila = document.createElement('div');
    nuevaFila.className = 'row fila-acceso align-items-end mb-2 mt-2 pt-2 border-top';
    
    nuevaFila.innerHTML = `
        <div class="col-md-3 form-group mb-0">
            <select name="tipo_acceso[]" class="form-control input-acceso" onchange="cargarDestinosExpediente(this)" required>
                <option value="">Seleccione...</option>
                <option value="area">Área / Oficina</option>
                <option value="cargo">Cargo Específico</option>
                <option value="rol">Rol del Sistema</option>
                <option value="usuario">Usuario Directo</option>
            </select>
        </div>
        <div class="col-md-4 form-group mb-0">
            <select name="id_referencia[]" class="form-control select-referencia input-acceso" required>
                <option value="">Primero elija tipo...</option>
            </select>
        </div>
        <div class="col-md-3 form-group mb-0">
            <select name="permiso[]" class="form-control input-acceso" required>
                <option value="lectura">Lectura (Solo ver)</option>
                <option value="edicion">Edición (Subir versiones)</option>
                <option value="administrador">Administrador</option>
            </select>
        </div>
        <div class="col-md-2 form-group mb-0">
            <button type="button" class="btn btn-outline-danger w-100" onclick="eliminarFilaAcceso(this)">
                <i class="fas fa-trash"></i> Quitar
            </button>
        </div>
    `;
    contenedor.appendChild(nuevaFila);
}

function eliminarFilaAcceso(boton) {
    boton.closest('.fila-acceso').remove();
}

// ==========================================
// 4. ENVÍO DEL FORMULARIO (AJAX)
// ==========================================
document.getElementById('formCrearExpediente').onsubmit = function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btnGuardarExpediente');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';

    const formData = new FormData(this);

    fetch('../../ajax/ajax_expedientes.php?accion=crear_expediente', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Crear Expediente';

        if (data.status === "ok") {
            Swal.fire('¡Éxito!', data.mensaje, 'success').then(() => {
                this.reset();
                document.getElementById('panel_compartido').style.display = 'none';
                
                // Si quieres limpiar las filas extras creadas:
                document.querySelectorAll('.fila-acceso').forEach((fila, index) => {
                    if(index > 0) fila.remove(); 
                });
            });
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Crear Expediente';
        Swal.fire('Error crítico', 'Ocurrió un problema de red al intentar crear el expediente.', 'error');
        console.error(err);
    });
};
</script>
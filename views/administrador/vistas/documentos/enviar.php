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

                <h6 class="text-primary border-bottom pb-2 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-share"></i> Destinatario</span>
                    
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-sm btn-outline-primary active" onclick="cambiarModalidad('unica')">
                            <input type="radio" name="modalidad_envio" value="unica" checked> Atención Única
                        </label>
                        <label class="btn btn-sm btn-outline-primary" onclick="cambiarModalidad('multiple')">
                            <input type="radio" name="modalidad_envio" value="multiple"> Atención Múltiple
                        </label>
                    </div>
                </h6>

                <div id="panel_unica" class="row mt-3">
                    <div class="col-md-6 form-group">
                        <label>Enviar a:</label>
                        <select name="tipo_envio" id="tipo_envio" class="form-control input-unica" onchange="cargarDestinosInternos(this.value)" required>
                            <option value="">Seleccione tipo</option>
                            <option value="usuario">Usuario Directo</option>
                            <option value="area">Área / Oficina</option>
                            <option value="programa">Programa de Estudios</option>
                            <option value="rol">Rol del Sistema</option>
                            <option value="cargo">Cargo Específico</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Seleccionar Destino</label>
                        <select name="id_destino" id="id_destino" class="form-control input-unica" required>
                            <option value="">Primero seleccione tipo...</option>
                        </select>
                    </div>
                </div>

                <div id="panel_multiple" class="mt-3 bg-light p-3 border rounded" style="display: none;">
                    <label class="font-weight-bold text-dark mb-3"><i class="fas fa-filter"></i> Filtro de Usuarios</label>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <select id="filtro_area" class="form-control form-control-sm">
                                <option value="">Todas las Áreas...</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <select id="filtro_cargo" class="form-control form-control-sm">
                                <option value="">Todos los Cargos...</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <select id="filtro_programa" class="form-control form-control-sm">
                                <option value="">Todos los Programas...</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <button type="button" class="btn btn-sm btn-info w-100" onclick="buscarUsuariosMultiple()">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    
                    <div id="contenedor_resultados_multiple" style="display:none;">
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="font-weight-bold">Usuarios Encontrados:</span>
                            <button type="button" class="btn btn-xs btn-outline-secondary" onclick="marcarTodos(true)">Marcar Todos</button>
                        </div>
                        <div id="lista_checkbox_usuarios" class="row px-3" style="max-height: 200px; overflow-y: auto;">
                            </div>
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
document.addEventListener("DOMContentLoaded", () => {
    cargarTiposDocumento();
    cargarFiltrosMultiple();
});

function cargarTiposDocumento() {
    fetch('../../ajax/ajax_utilitarios.php?accion=listar_tipos_doc_internos')
    .then(res => res.json())
    .then(data => {
        let sel = document.getElementById('id_tipo_doc');
        sel.innerHTML = '<option value="">Seleccione tipo...</option>';
        data.forEach(t => sel.innerHTML += `<option value="${t.id_tipo}">${t.nombre}</option>`);
    });
}

function cargarDestinosInternos(tipo) {
    const sel = document.getElementById('id_destino');
    if(!tipo) { sel.innerHTML = '<option value="">Primero seleccione tipo...</option>'; return; }
    fetch(`../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=${tipo}`)
    .then(res => res.json())
    .then(data => {
        sel.innerHTML = '<option value="">Seleccione el destinatario</option>';
        data.forEach(i => sel.innerHTML += `<option value="${i.id}">${i.nombre}</option>`);
    });
}


function cambiarModalidad(modo) {
    const pUnica = document.getElementById('panel_unica');
    const pMultiple = document.getElementById('panel_multiple');
    const inputsUnica = document.querySelectorAll('.input-unica');

    if(modo === 'unica') {
        pUnica.style.display = 'flex';
        pMultiple.style.display = 'none';
        inputsUnica.forEach(i => i.required = true);
    } else {
        pUnica.style.display = 'none';
        pMultiple.style.display = 'block';
        inputsUnica.forEach(i => i.required = false);
    }
}

function cargarFiltrosMultiple() {
    fetch('../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=area').then(r=>r.json()).then(d => {
        d.forEach(i => document.getElementById('filtro_area').innerHTML += `<option value="${i.id}">${i.nombre}</option>`);
    });
    fetch('../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=cargo').then(r=>r.json()).then(d => {
        d.forEach(i => document.getElementById('filtro_cargo').innerHTML += `<option value="${i.id}">${i.nombre}</option>`);
    });
    fetch('../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=programa').then(r=>r.json()).then(d => {
        d.forEach(i => document.getElementById('filtro_programa').innerHTML += `<option value="${i.id}">${i.nombre}</option>`);
    });
}

function buscarUsuariosMultiple() {
    let id_area = document.getElementById('filtro_area').value;
    let id_cargo = document.getElementById('filtro_cargo').value;
    let id_programa = document.getElementById('filtro_programa').value;

    fetch(`../../ajax/ajax_utilitarios.php?accion=buscar_usuarios_avanzado&area=${id_area}&cargo=${id_cargo}&programa=${id_programa}`)
    .then(res => res.json())
    .then(data => {
        document.getElementById('contenedor_resultados_multiple').style.display = 'block';
        let divList = document.getElementById('lista_checkbox_usuarios');
        divList.innerHTML = '';

        if(data.length === 0) {
            divList.innerHTML = '<span class="text-danger w-100">No se encontraron usuarios con esos filtros.</span>';
            return;
        }

        data.forEach(user => {
            // Nota el name="destinatarios_multiples[]" -> Esto crea el Array en PHP
            divList.innerHTML += `
                <div class="col-md-4 mb-2">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input chk-user" id="usr_${user.id_usuario}" name="destinatarios_multiples[]" value="${user.id_usuario}" checked>
                        <label class="custom-control-label" for="usr_${user.id_usuario}">${user.nombres}</label>
                    </div>
                </div>`;
        });
    });
}

function marcarTodos(estado) {
    document.querySelectorAll('.chk-user').forEach(chk => chk.checked = estado);
}

// --- ENVÍO DEL FORMULARIO ---
document.getElementById("formNuevoDocumento").onsubmit = function(e) {
    e.preventDefault();
    
    // Validación extra para múltiple
    let modo = document.querySelector('input[name="modalidad_envio"]:checked').value;
    if(modo === 'multiple') {
        let marcados = document.querySelectorAll('.chk-user:checked');
        if(marcados.length === 0) {
            Swal.fire('Atención', 'Debe buscar y seleccionar al menos un usuario para la atención múltiple.', 'warning');
            return;
        }
    }

    const btn = document.getElementById("btnEnviarDoc");
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

    fetch('../../ajax/ajax_gestion_documentos.php?accion=registrar_documento_interno', {
        method: 'POST', body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Registrar y Enviar Documento';
        
        if (data.status === "ok") {
            Swal.fire('¡Éxito!', data.mensaje, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    });
};
</script>
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-folder-open text-primary"></i> Mis Expedientes
</h1>

<div class="container-fluid">
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tabla-mis-expedientes">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>Asunto</th>
                            <th>Privacidad</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th class="text-center">Administrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarExpediente" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Expediente</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formEditarExpediente">
                <div class="modal-body">
                    <input type="hidden" name="id_expediente" id="edit_id_expediente">
                    
                    <div class="form-group">
                        <label>Asunto / Título</label>
                        <input type="text" name="asunto" id="edit_asunto" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Estado del Expediente</label>
                            <select name="estado" id="edit_estado" class="form-control" required>
                                <option value="activo">Activo (Recibiendo docs)</option>
                                <option value="en_proceso">En Proceso (Evaluación)</option>
                                <option value="finalizado">Finalizado (Resuelto)</option>
                                <option value="archivado">Archivado (Cerrado)</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Privacidad</label>
                            <select name="tipo" id="edit_tipo" class="form-control" required>
                                <option value="privado">Privado (Solo yo)</option>
                                <option value="compartido">Compartido (Usuarios específicos)</option>
                                <option value="publico">Público (Visible para todos)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetallesExpediente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-layer-group"></i> Detalles del Expediente</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <ul class="nav nav-tabs bg-light pl-3 pt-2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" data-toggle="tab" href="#tab-documentos" role="tab">
                            <i class="fas fa-file-pdf"></i> Documentos y Versiones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" data-toggle="tab" href="#tab-historial" role="tab">
                            <i class="fas fa-history"></i> Historial de Auditoría
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-4">
                    <div class="tab-pane fade show active" id="tab-documentos" role="tabpanel">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="font-weight-bold text-dark">Documentos Adjuntos</h6>
                            <button class="btn btn-sm btn-success" onclick="$('#formNuevaVersion').slideToggle();">
                                <i class="fas fa-plus"></i> Agregar Documento / Versión
                            </button>
                        </div>

                        <div id="formNuevaVersion" class="bg-light p-3 mb-3 border rounded" style="display: none;">
                            <form id="formSubirVersion" enctype="multipart/form-data">
                                <input type="hidden" name="id_expediente" id="ver_id_expediente">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Documento Lógico</label>
                                        <select name="id_documento" id="select_documentos_exp" class="form-control" required>
                                            <option value="nuevo">-- CREAR NUEVO DOCUMENTO --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group" id="div_nuevo_nombre">
                                        <label>Nombre del Nuevo Documento</label>
                                        <input type="text" name="nuevo_nombre" id="input_nuevo_nombre" class="form-control" placeholder="Ej: Plano Estructural">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Archivo Físico</label>
                                        <input type="file" name="archivo" class="form-control-file" accept=".pdf,.doc,.docx,.zip,.rar,.7z" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Comentario de la Versión</label>
                                    <input type="text" name="comentario" class="form-control form-control-sm" placeholder="¿Qué cambió en esta versión?" required>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Subir Archivo</button>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tabla-versiones">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Documento</th>
                                        <th>Versión</th>
                                        <th>Archivo</th>
                                        <th>Comentario</th>
                                        <th>Subido Por</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-historial" role="tabpanel">
                        <div id="timeline-expediente" class="pl-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAccesosExpediente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-users-cog"></i> Administrar Accesos</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body p-4">
                <input type="hidden" id="acc_id_expediente">
                
                <div id="seccion-solicitudes" style="display:none;">
                    <h6 class="font-weight-bold text-warning border-bottom pb-2"><i class="fas fa-bell"></i> Solicitudes de Acceso Pendientes</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm" id="tabla-solicitudes">
                            <thead class="bg-warning text-dark">
                                <tr>
                                    <th>Usuario Solicitante</th>
                                    <th>Mensaje / Motivo</th>
                                    <th>Fecha</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <h6 class="font-weight-bold text-success border-bottom pb-2"><i class="fas fa-user-plus"></i> Otorgar Nuevo Permiso</h6>
                <form id="formAgregarAcceso" class="bg-light p-3 mb-4 border rounded">
                    <div class="row align-items-end">
                        <div class="col-md-3 form-group mb-0">
                            <label class="small">Tipo Destino</label>
                            <select name="tipo_acceso" class="form-control form-control-sm" onchange="cargarDestinosExpediente(this)" required>
                                <option value="">Seleccione...</option>
                                <option value="area">Área</option>
                                <option value="cargo">Cargo</option>
                                <option value="rol">Rol</option>
                                <option value="usuario">Usuario</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group mb-0">
                            <label class="small">Seleccionar</label>
                            <select name="id_referencia" class="form-control form-control-sm select-referencia" required>
                                <option value="">Primero elija tipo...</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group mb-0">
                            <label class="small">Permiso</label>
                            <select name="permiso" class="form-control form-control-sm" required>
                                <option value="lectura">Lectura</option>
                                <option value="edicion">Edición</option>
                                <option value="administrador">Administrador</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group mb-0">
                            <button type="submit" class="btn btn-sm btn-success w-100"><i class="fas fa-plus"></i> Añadir</button>
                        </div>
                    </div>
                </form>

                <h6 class="font-weight-bold text-dark border-bottom pb-2">Usuarios/Áreas con Acceso Actual</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-sm" id="tabla-accesos">
                        <thead class="thead-dark">
                            <tr>
                                <th>Tipo</th>
                                <th>Entidad / Nombre</th>
                                <th>Permiso</th>
                                <th>Fecha</th>
                                <th class="text-center">Revocar</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    cargarMisExpedientes();
});

// 1. CARGAR TABLA PRINCIPAL
function cargarMisExpedientes() {
    fetch('../../ajax/ajax_expedientes.php?accion=listar_mis_expedientes')
    .then(res => res.json())
    .then(response => {
        const tbody = document.querySelector("#tabla-mis-expedientes tbody");
        tbody.innerHTML = "";
        
        if (!response.data || response.data.length === 0) {
            // Mensaje actualizado
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No tienes expedientes creados ni compartidos contigo aún.</td></tr>`;
            return;
        }

        response.data.forEach(exp => {
            let badgeEstado = 'success';
            if (exp.estado === 'en_proceso') badgeEstado = 'warning';
            if (exp.estado === 'finalizado') badgeEstado = 'info';
            if (exp.estado === 'archivado') badgeEstado = 'dark';

            let iconPrivacidad = exp.tipo === 'privado' ? '<i class="fas fa-lock text-danger"></i>' : 
                                (exp.tipo === 'publico' ? '<i class="fas fa-globe text-primary"></i>' : '<i class="fas fa-users text-success"></i>');

            // =====================================
            // LÓGICA INTELIGENTE DE BOTONES
            // =====================================
            let btnAccesos = '';
            let btnEditar = '';

            // Si es dueño o administrador, tiene control total
            if (exp.mi_permiso === 'propietario' || exp.mi_permiso === 'administrador') {
                
                // 🔥 EL ARREGLO: Desbloqueamos el botón si es Compartido O Público
                if (exp.tipo === 'compartido' || exp.tipo === 'publico') {
                    btnAccesos = `<button class="btn btn-success btn-sm" onclick="abrirAccesos(${exp.id_expediente})" title="Administrar Accesos y Solicitudes"><i class="fas fa-users-cog"></i></button>`;
                } else {
                    btnAccesos = `<button class="btn btn-secondary btn-sm" disabled title="No aplica para expedientes Privados"><i class="fas fa-users-slash"></i></button>`;
                }
                
                btnEditar = `<button class="btn btn-warning btn-sm" onclick="abrirEditarExpediente(${exp.id_expediente}, '${exp.asunto}', '${exp.estado}', '${exp.tipo}')" title="Editar Expediente"><i class="fas fa-edit"></i></button>`;
            } else {
                // Si es solo Lectura o Edición, bloqueamos la administración total
                btnAccesos = `<button class="btn btn-secondary btn-sm" disabled title="Solo administradores pueden gestionar accesos"><i class="fas fa-lock"></i></button>`;
                btnEditar = `<button class="btn btn-secondary btn-sm" disabled title="Solo administradores pueden editar el expediente"><i class="fas fa-lock"></i></button>`;
            }

            tbody.innerHTML += `
                <tr>
                    <td><strong>${exp.codigo_expediente}</strong></td>
                    <td>${exp.asunto} <br><span class="badge badge-light border text-muted small"><i class="fas fa-user-tag"></i> Tu acceso: ${exp.mi_permiso.toUpperCase()}</span></td>
                    <td>${iconPrivacidad} <span class="text-capitalize">${exp.tipo}</span></td>
                    <td><span class="badge badge-${badgeEstado}">${exp.estado.toUpperCase()}</span></td>
                    <td>${exp.fecha_creacion}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm" onclick="verDetallesExpediente(${exp.id_expediente})" title="Documentos y Versiones">
                                <i class="fas fa-layer-group"></i>
                            </button>
                            ${btnAccesos}
                            ${btnEditar}
                        </div>
                    </td>
                </tr>`;
        });
    })
    .catch(err => console.error("Error cargando expedientes:", err));
}

function abrirEditarExpediente(id, asunto, estado, tipo) {
    document.getElementById('edit_id_expediente').value = id;
    document.getElementById('edit_asunto').value = asunto;
    document.getElementById('edit_estado').value = estado;
    document.getElementById('edit_tipo').value = tipo; // <-- Nuevo campo
    $('#modalEditarExpediente').modal('show');
}

// 2. DETALLES Y VERSIONES
function verDetallesExpediente(id_expediente) {
    document.getElementById('ver_id_expediente').value = id_expediente;
    $('#formNuevaVersion').hide();
    
    fetch(`../../ajax/ajax_expedientes.php?accion=obtener_detalles&id=${id_expediente}`)
    .then(res => res.json())
    .then(data => {
        const tbodyV = document.querySelector("#tabla-versiones tbody");
        const selectDoc = document.getElementById("select_documentos_exp");
        tbodyV.innerHTML = "";
        selectDoc.innerHTML = '<option value="nuevo">-- CREAR NUEVO DOCUMENTO LÓGICO --</option>';

        let docsVistos = [];

        if (data.versiones.length === 0) {
            tbodyV.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No hay documentos en este expediente.</td></tr>`;
        } else {
            data.versiones.forEach(v => {
                tbodyV.innerHTML += `
                    <tr>
                        <td class="align-middle text-primary font-weight-bold">${v.nombre_documento}</td>
                        <td class="align-middle text-center"><span class="badge badge-dark">V${v.version}</span></td>
                        <td class="align-middle"><a href="../../${v.ruta_archivo}" target="_blank" class="btn btn-xs btn-outline-danger"><i class="fas fa-download"></i> Descargar</a></td>
                        <td class="align-middle"><em>${v.comentario}</em></td>
                        <td class="align-middle small">${v.subido_por}</td>
                        <td class="align-middle small">${v.fecha_subida}</td>
                    </tr>`;
                
                if(!docsVistos.includes(v.id_documento)){
                    selectDoc.innerHTML += `<option value="${v.id_documento}">Actualizar: ${v.nombre_documento}</option>`;
                    docsVistos.push(v.id_documento);
                }
            });
        }

        const timeline = document.getElementById('timeline-expediente');
        timeline.innerHTML = "";
        data.historial.forEach(h => {
            let color = h.tipo_evento === 'creado' ? 'success' : (h.tipo_evento === 'acceso_asignado' ? 'info' : 'primary');
            timeline.innerHTML += `
                <div class="border-left pl-3 pb-3 position-relative border-${color}">
                    <div class="position-absolute bg-${color}" style="left:-6px; top:0; width:11px; height:11px; border-radius:50%"></div>
                    <small class="text-muted">${h.fecha} - <b>${h.nombres_usuario}</b></small><br>
                    <strong class="text-${color} text-uppercase">${h.tipo_evento.replace('_', ' ')}</strong><br>
                    <span>${h.observacion}</span>
                </div>`;
        });

        $('#modalDetallesExpediente').modal('show');
    });
}

// Ocultar campo si elegimos subir versión a un doc existente
document.getElementById('select_documentos_exp').addEventListener('change', function(){
    if(this.value === 'nuevo') {
        document.getElementById('div_nuevo_nombre').style.display = 'block';
        document.getElementById('input_nuevo_nombre').required = true;
    } else {
        document.getElementById('div_nuevo_nombre').style.display = 'none';
        document.getElementById('input_nuevo_nombre').required = false;
    }
});

// Guardar nueva versión
document.getElementById('formSubirVersion').onsubmit = function(e) {
    e.preventDefault();
    fetch('../../ajax/ajax_expedientes.php?accion=subir_version', { method: 'POST', body: new FormData(this) })
    .then(res => res.json())
    .then(data => {
        if (data.status === "ok") {
            Swal.fire('Éxito', data.mensaje, 'success');
            this.reset();
            document.getElementById('div_nuevo_nombre').style.display = 'block';
            verDetallesExpediente(document.getElementById('ver_id_expediente').value);
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    });
};

// 3. ADMINISTRACIÓN DE ACCESOS Y SOLICITUDES
function abrirAccesos(id_expediente) {
    document.getElementById('acc_id_expediente').value = id_expediente;
    cargarListaAccesos(id_expediente);
    $('#modalAccesosExpediente').modal('show');
}

function cargarDestinosExpediente(selectTipo) {
    const tipo = selectTipo.value;
    const selectReferencia = selectTipo.closest('.row').querySelector('.select-referencia');
    if(!tipo) { 
        selectReferencia.innerHTML = '<option value="">Primero elija tipo...</option>'; return; 
    }
    fetch(`../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=${tipo}`)
    .then(res => res.json())
    .then(data => {
        selectReferencia.innerHTML = '<option value="">Seleccione...</option>';
        data.forEach(item => { selectReferencia.innerHTML += `<option value="${item.id}">${item.nombre}</option>`; });
    });
}

function cargarListaAccesos(id_expediente) {
    // Lista de Accesos
    fetch(`../../ajax/ajax_expedientes.php?accion=obtener_accesos&id=${id_expediente}`)
    .then(res => res.json())
    .then(data => {
        const tbody = document.querySelector("#tabla-accesos tbody");
        tbody.innerHTML = "";
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Nadie tiene acceso a este expediente.</td></tr>`;
        } else {
            data.forEach(acc => {
                let color = acc.permiso === 'administrador' ? 'danger' : (acc.permiso === 'edicion' ? 'warning' : 'info');
                tbody.innerHTML += `
                    <tr>
                        <td class="text-capitalize">${acc.tipo_acceso}</td>
                        <td class="font-weight-bold">${acc.nombre_destino}</td>
                        <td><span class="badge badge-${color}">${acc.permiso.toUpperCase()}</span></td>
                        <td class="small">${acc.fecha_asignacion}</td>
                        <td class="text-center">
                            <button class="btn btn-outline-danger btn-sm" onclick="revocarAcceso(${acc.id_acceso}, ${id_expediente})"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>`;
            });
        }
    });

    // Lista de Solicitudes
    fetch(`../../ajax/ajax_expedientes.php?accion=listar_solicitudes&id=${id_expediente}`)
    .then(res => res.json())
    .then(data => {
        const divSolicitudes = document.getElementById("seccion-solicitudes");
        const tbodySol = document.querySelector("#tabla-solicitudes tbody");
        tbodySol.innerHTML = "";
        
        if (data.length > 0) {
            divSolicitudes.style.display = 'block';
            data.forEach(sol => {
                tbodySol.innerHTML += `
                    <tr>
                        <td class="font-weight-bold">${sol.nombres_usuario}</td>
                        <td><em>${sol.mensaje}</em></td>
                        <td class="small">${sol.fecha_solicitud}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm" onclick="procesarSolicitud(${sol.id_solicitud}, ${id_expediente}, 'aprobado')" title="Aprobar (Lectura)"><i class="fas fa-check"></i></button>
                                <button class="btn btn-danger btn-sm" onclick="procesarSolicitud(${sol.id_solicitud}, ${id_expediente}, 'rechazado')" title="Rechazar"><i class="fas fa-times"></i></button>
                            </div>
                        </td>
                    </tr>`;
            });
        } else {
            divSolicitudes.style.display = 'none';
        }
    });
}

// Agregar Permiso Manual
document.getElementById('formAgregarAcceso').onsubmit = function(e) {
    e.preventDefault();
    let fd = new FormData(this);
    fd.append('id_expediente', document.getElementById('acc_id_expediente').value);
    
    fetch('../../ajax/ajax_expedientes.php?accion=agregar_acceso', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        if (data.status === "ok") {
            this.reset();
            cargarListaAccesos(fd.get('id_expediente'));
        } else Swal.fire('Error', data.mensaje, 'error');
    });
};

function revocarAcceso(id_acceso, id_expediente) {
    if(confirm('¿Seguro de revocar este acceso?')) {
        let fd = new FormData();
        fd.append('id_acceso', id_acceso);
        fd.append('id_expediente', id_expediente);
        fetch('../../ajax/ajax_expedientes.php?accion=revocar_acceso', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            if (data.status === "ok") cargarListaAccesos(id_expediente);
            else Swal.fire('Error', data.mensaje, 'error');
        });
    }
}

function procesarSolicitud(id_solicitud, id_expediente, accion_estado) {
    let fd = new FormData();
    fd.append('id_solicitud', id_solicitud);
    fd.append('id_expediente', id_expediente);
    fd.append('estado', accion_estado);

    fetch('../../ajax/ajax_expedientes.php?accion=procesar_solicitud', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        if (data.status === "ok") {
            Swal.fire('Completado', data.mensaje, 'success');
            cargarListaAccesos(id_expediente);
        } else Swal.fire('Error', data.mensaje, 'error');
    });
}
// ==========================================
// GUARDAR EDICIÓN DE EXPEDIENTE
// ==========================================
document.getElementById('formEditarExpediente').onsubmit = function(e) {
    e.preventDefault(); // 🔥 ESTO ES LO QUE EVITA QUE TE MANDE AL HOME

    let btn = this.querySelector('button[type="submit"]');
    let textoOriginal = btn.innerHTML;
    
    // Bloqueamos el botón mientras carga
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    fetch('../../ajax/ajax_expedientes.php?accion=editar_expediente', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        // Restauramos el botón
        btn.disabled = false;
        btn.innerHTML = textoOriginal;

        if(data.status === 'ok') {
            Swal.fire('Actualizado', data.mensaje, 'success');
            $('#modalEditarExpediente').modal('hide'); // Cierra la ventana
            cargarMisExpedientes(); // Actualiza la tabla en tiempo real
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = textoOriginal;
        console.error("Error Fetch:", err);
        Swal.fire('Error', 'Problema de conexión con el servidor.', 'error');
    });
};
</script>
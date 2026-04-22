<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-globe text-primary"></i> Directorio de Expedientes Públicos
</h1>

<div class="container-fluid">
    <div class="card shadow mb-4 border-left-info">
        <div class="card-body">
            <p class="text-muted">Aquí puedes consultar los expedientes de acceso público de la institución. Si requieres subir documentación, solicita acceso de edición al responsable.</p>
            
            <div class="table-responsive">
                <table class="table table-hover" id="tabla-publicos">
                    <thead class="thead-light">
                        <tr>
                            <th>Código</th>
                            <th>Asunto / Descripción</th>
                            <th>Responsable</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetallesReadonly" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-search"></i> Explorador de Expediente</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <ul class="nav nav-tabs bg-light pl-3 pt-2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" data-toggle="tab" href="#tab-docs-ro" role="tab">
                            <i class="fas fa-file-pdf"></i> Documentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" data-toggle="tab" href="#tab-hist-ro" role="tab">
                            <i class="fas fa-shoe-prints"></i> Seguimiento
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-4">
                    <div class="tab-pane fade show active" id="tab-docs-ro" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tabla-versiones-ro">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Documento</th>
                                        <th>Versión</th>
                                        <th>Archivo Físico</th>
                                        <th>Comentario de Versión</th>
                                        <th>Subido Por</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-hist-ro" role="tabpanel">
                        <div id="timeline-expediente-ro" class="pl-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSolicitarAcceso" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-key"></i> Solicitar Permiso de Edición</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formSolicitarAcceso">
                <div class="modal-body">
                    <input type="hidden" name="id_expediente" id="sol_id_expediente">
                    <div class="alert alert-info small">
                        El responsable evaluará tu solicitud. Una vez aprobada, este expediente aparecerá en tu pestaña de "Mis Expedientes" con los permisos correspondientes.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Motivo de la Solicitud</label>
                        <textarea name="mensaje" class="form-control" rows="3" placeholder="Ej: Necesito subir la resolución firmada y los anexos correspondientes..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnEnviarSolicitud" class="btn btn-warning text-dark"><i class="fas fa-paper-plane"></i> Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    cargarExpedientesPublicos();
});

// ==========================================
// 1. CARGAR LA TABLA DE EXPEDIENTES PÚBLICOS
// ==========================================
function cargarExpedientesPublicos() {
    fetch('../../ajax/ajax_expedientes.php?accion=listar_publicos')
    .then(res => res.json())
    .then(response => {
        const tbody = document.querySelector("#tabla-publicos tbody");
        tbody.innerHTML = "";
        
        if (!response.data || response.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No hay expedientes públicos disponibles.</td></tr>`;
            return;
        }

        response.data.forEach(exp => {
            let badgeEstado = 'success';
            if (exp.estado === 'en_proceso') badgeEstado = 'warning';
            if (exp.estado === 'finalizado') badgeEstado = 'info';
            if (exp.estado === 'archivado') badgeEstado = 'dark';

            // LÓGICA INTELIGENTE DE BOTONES
            let btnAcceso = '';
            
            if (exp.ya_tengo_acceso == 1) {
                btnAcceso = `<button class="btn btn-secondary btn-sm" disabled title="Ya tienes acceso a este expediente"><i class="fas fa-check-circle"></i> Eres Miembro</button>`;
            } else if (exp.solicitud_pendiente > 0) {
                btnAcceso = `<button class="btn btn-warning btn-sm" disabled title="Tu solicitud está en revisión"><i class="fas fa-hourglass-half"></i> Solicitud Pendiente</button>`;
            } else {
                btnAcceso = `<button class="btn btn-primary btn-sm" onclick="abrirSolicitud(${exp.id_expediente})" title="Solicitar permiso de edición"><i class="fas fa-hand-paper"></i> Pedir Acceso</button>`;
            }

            let btnParticipantes = `<button class="btn btn-info btn-sm" onclick="verParticipantesPublico(${exp.id_expediente}, '${exp.responsable}')" title="Ver Participantes"><i class="fas fa-users"></i></button>`;
            let btnVerDocs = `<button class="btn btn-success btn-sm" onclick="verDetallesPublicos(${exp.id_expediente})" title="Explorar Archivos"><i class="fas fa-folder-open"></i></button>`;

            tbody.innerHTML += `
                <tr>
                    <td class="align-middle"><strong>${exp.codigo_expediente}</strong></td>
                    <td class="align-middle">${exp.asunto}</td>
                    <td class="align-middle small text-muted"><i class="fas fa-user-tie"></i> ${exp.responsable}</td>
                    <td class="align-middle"><span class="badge badge-${badgeEstado}">${exp.estado.toUpperCase()}</span></td>
                    <td class="align-middle small">${exp.fecha_creacion}</td>
                    <td class="text-center align-middle">
                        <div class="btn-group">
                            ${btnVerDocs}
                            ${btnParticipantes}
                            ${btnAcceso}
                        </div>
                    </td>
                </tr>`;
        });
    });
}

// ==========================================
// 2. EXPLORADOR READ-ONLY (VER DETALLES)
// ==========================================
function verDetallesPublicos(id_expediente) {
    fetch(`../../ajax/ajax_expedientes.php?accion=obtener_detalles&id=${id_expediente}`)
    .then(res => res.json())
    .then(data => {
        const tbodyV = document.querySelector("#tabla-versiones-ro tbody");
        tbodyV.innerHTML = "";

        if (data.versiones.length === 0) {
            tbodyV.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No se han subido archivos a este expediente.</td></tr>`;
        } else {
            data.versiones.forEach(v => {
                tbodyV.innerHTML += `
                    <tr>
                        <td class="align-middle text-primary font-weight-bold">${v.nombre_documento}</td>
                        <td class="align-middle text-center"><span class="badge badge-dark">V${v.version}</span></td>
                        <td class="align-middle text-center"><a href="../../${v.ruta_archivo}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> Ver</a></td>
                        <td class="align-middle"><em>${v.comentario}</em></td>
                        <td class="align-middle small">${v.subido_por}</td>
                        <td class="align-middle small">${v.fecha_subida}</td>
                    </tr>`;
            });
        }

        const timeline = document.getElementById('timeline-expediente-ro');
        timeline.innerHTML = "";
        data.historial.forEach(h => {
            let color = h.tipo_evento === 'creado' ? 'success' : 'primary';
            timeline.innerHTML += `
                <div class="border-left pl-3 pb-3 position-relative border-${color}">
                    <div class="position-absolute bg-${color}" style="left:-6px; top:0; width:11px; height:11px; border-radius:50%"></div>
                    <small class="text-muted">${h.fecha} - <b>${h.nombres_usuario}</b></small><br>
                    <strong class="text-${color} text-uppercase">${h.tipo_evento.replace('_', ' ')}</strong><br>
                    <span>${h.observacion}</span>
                </div>`;
        });

        $('#modalDetallesReadonly').modal('show');
    });
}

// ==========================================
// 3. TRANSPARENCIA (VER PARTICIPANTES)
// ==========================================
function verParticipantesPublico(id_expediente, responsable) {
    fetch(`../../ajax/ajax_expedientes.php?accion=obtener_accesos&id=${id_expediente}`)
    .then(res => res.json())
    .then(data => {
        let listaHTML = `<div class="text-left mb-3"><strong><i class="fas fa-crown text-warning"></i> Creador/Responsable:</strong> ${responsable}</div>`;
        listaHTML += `<ul class="list-group text-left text-sm">`;
        
        if (data.length === 0) {
            listaHTML += `<li class="list-group-item text-muted">Aún no hay otros participantes asignados a este expediente.</li>`;
        } else {
            data.forEach(acc => {
                listaHTML += `<li class="list-group-item">
                    <b>${acc.nombre_destino}</b> <br>
                    <small class="text-muted text-capitalize">Tipo: ${acc.tipo_acceso} | Permiso: ${acc.permiso}</small>
                </li>`;
            });
        }
        listaHTML += `</ul>`;

        Swal.fire({
            title: 'Participantes del Expediente',
            html: listaHTML,
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    });
}

// ==========================================
// 4. SOLICITAR ACCESO (CON CONTROL DE FREEZE)
// ==========================================
function abrirSolicitud(id_expediente) {
    document.getElementById('sol_id_expediente').value = id_expediente;
    document.getElementById('formSolicitarAcceso').reset();
    $('#modalSolicitarAcceso').modal('show');
}

document.getElementById('formSolicitarAcceso').onsubmit = function(e) {
    e.preventDefault();
    let btn = document.getElementById('btnEnviarSolicitud');
    
    // Bloqueamos para evitar doble clic
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

    fetch('../../ajax/ajax_expedientes.php?accion=enviar_solicitud_acceso', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        // Restauramos el botón SIEMPRE
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';

        if (data.status === "ok") {
            Swal.fire('¡Enviado!', data.mensaje, 'success');
            $('#modalSolicitarAcceso').modal('hide');
            this.reset();
            cargarExpedientesPublicos(); // Actualiza la tabla para mostrar "Solicitud Pendiente"
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';
        console.error("Error de Fetch:", err);
        Swal.fire('Error', 'Problema de conexión con el servidor.', 'error');
    });
};
</script>
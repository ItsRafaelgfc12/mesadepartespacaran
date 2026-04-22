<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-folder text-primary"></i> Gestión de Documentos
</h1>

<div class="container-fluid">
    <ul class="nav nav-tabs mb-4" id="tabsDocumentos">
        <li class="nav-item">
            <a class="nav-link active" href="#" onclick="mostrar('recibidos', this)">
                <i class="fas fa-inbox"></i> Recibidos <span class="badge badge-danger" id="count-recibidos">0</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="mostrar('archivo', this)">
                <i class="fas fa-archive"></i> Archivo
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="mostrar('atendidos', this)">
                <i class="fas fa-check-circle"></i> Atendidos
            </a>
        </li>
    </ul>

    <div id="recibidos" class="seccion">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h5 class="text-primary mb-3"><i class="fas fa-inbox"></i> Documentos Recibidos</h5>
                <div class="table-responsive">
                    <table class="table table-hover" id="tabla-recibidos">
                        <thead class="thead-dark">
                            <tr>
                                <th>Código</th>
                                <th>Asunto</th>
                                <th>Fecha</th>
                                <th>Remitente</th>
                                <th>Vía de Recepción</th> 
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="pendientes" class="seccion d-none">
    </div>

    <div id="archivo" class="seccion d-none">
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="text-dark mb-3"><i class="fas fa-archive"></i> Expedientes Archivados</h5>
            <div class="table-responsive">
                <table class="table table-hover" id="tabla-archivo">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>Asunto</th>
                            <th>Fecha Cierre</th>
                            <th>Archivado por</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <div id="atendidos" class="seccion d-none">
        <div class="card shadow mb-4 border-left-success">
            <div class="card-body">
                <h5 class="text-success mb-3"><i class="fas fa-check-circle"></i> Documentos Atendidos / En Proceso</h5>
                <div class="table-responsive">
                    <table class="table table-hover" id="tabla-atendidos">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Asunto</th>
                                <th>Fecha Emisión</th>
                                <th>Remitente</th>
                                <th>Vía de Recepción</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>



<div class="modal fade" id="modalDerivar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-share-square"></i> Derivar Documento</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formDerivar" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_documento" id="derivar_id_doc">
                    <input type="hidden" name="id_derivacion_padre" id="derivar_id_parent">
                    
                    <div class="form-group">
                        <label>Tipo de Destino</label>
                        <select name="tipo_destino" id="tipo_destino" class="form-control" onchange="cargarDestinos(this.value)">
                            <option value="">Seleccione...</option>
                            <option value="area">Área / Oficina</option>
                            <option value="cargo">Cargo Específico</option>
                            <option value="usuario">Usuario Directo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Seleccionar Destinatario</label>
                        <select name="id_destino" id="id_destino" class="form-control" required>
                            <option value="">Primero elija tipo...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Instrucciones / Observación</label>
                        <textarea name="observacion" class="form-control" rows="3" placeholder="Ej: Para su revisión y visación..."></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label><i class="fas fa-paperclip"></i> Adjuntar Anexo/Documento (Opcional)</label>
                        <input type="file" name="archivo_anexo" id="archivo_anexo" class="form-control-file" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <small class="text-muted">Si el FUT requiere un informe o anexo extra para la siguiente área, súbalo aquí.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Derivación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalArchivar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-archive"></i> Enviar al Archivo</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formArchivar" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_documento" id="archivar_id_doc">
                    <p class="text-center font-weight-bold">¿Desea finalizar este trámite?</p>
                    
                    <div class="form-group">
                        <label>Mensaje / Motivo de archivamiento</label>
                        <textarea name="mensaje" id="archivar_mensaje" class="form-control" rows="3" required placeholder="Ej: Trámite concluido satisfactoriamente..."></textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label><i class="fas fa-file-upload"></i> Adjuntar Documento Final (Opcional)</label>
                        <input type="file" name="archivo_final" id="archivo_final" class="form-control-file" accept=".pdf,.doc,.docx">
                        <small class="text-muted">Este archivo se guardará como el sustento del cierre.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark">Archivar Documento</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAtender" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-concierge-bell"></i> Atender Documento</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formAtender">
                <div class="modal-body">
                    <input type="hidden" name="id_documento" id="atender_id_doc">
                    
                    <input type="hidden" name="id_derivacion" id="atender_id_derivacion">
                    
                    <p>Indique el estado del avance para que el solicitante pueda visualizarlo:</p>
                    <div class="form-group">
                        <label>Mensaje de Avance / Proveído</label>
                        <textarea name="mensaje" class="form-control" rows="3" required placeholder="Ej: Se procede a la elaboración de la constancia solicitada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar Atención</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalSeguimiento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-history"></i> Seguimiento del Expediente</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="timeline-container" class="p-3">
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
/**
 * --- Lógica de Navegación de Tabs ---
 */
function mostrar(seccionId, elemento) {
    document.querySelectorAll('.seccion').forEach(sec => sec.classList.add('d-none'));
    document.getElementById(seccionId).classList.remove('d-none');
    document.querySelectorAll('#tabsDocumentos .nav-link').forEach(tab => tab.classList.remove('active'));
    elemento.classList.add('active');
    
    // Carga de datos centralizada
    if(seccionId === 'recibidos') cargarRecibidos();
    if(seccionId === 'atendidos') cargarAtendidos();
    if(seccionId === 'archivo') cargarArchivo();
}

/**
 * --- Carga de Tablas ---
 */
function cargarRecibidos() {
    fetch('../../ajax/ajax_gestion_documentos.php?accion=listar_recibidos')
    .then(res => res.json())
    .then(response => {
        if(response.error) return;
        let data = response.data; 
        const tbody = document.querySelector("#tabla-recibidos tbody");
        const contador = document.getElementById('count-recibidos');
        tbody.innerHTML = "";
        contador.innerText = data.length;
        let cargosDebug = String(response.debug.buscando_cargos || "0");

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No hay documentos pendientes.</td></tr>`;
            return;
        }

        data.forEach(doc => {
            let btnFut = cargosDebug.split(',').includes("6") 
                ? `<a href="../../pdf/generar_pdf_fut.php?id=${doc.id_documento}" target="_blank" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i></a>` : '';
            let url_ver = doc.ultimo_archivo ? `../../${doc.ultimo_archivo}` : `../../pdf/generar_pdf_fut.php?id=${doc.id_documento}`;

            tbody.innerHTML += `
                <tr>
                    <td><strong>${doc.codigo_documento}</strong></td>
                    <td>${doc.asunto}</td>
                    <td>${doc.fecha_emision}</td>
                    <td>${doc.remitente}</td>
                    <td><span class="badge badge-secondary">${doc.tipo_destino}</span></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="${url_ver}" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            ${btnFut}
                            <button class="btn btn-warning btn-sm" onclick="verSeguimiento(${doc.id_documento})"><i class="fas fa-history"></i></button>
                            <button class="btn btn-success btn-sm" onclick="abrirAtender(${doc.id_documento}, ${doc.id_derivacion})"><i class="fas fa-pen-nib"></i></button>
                            <button class="btn btn-primary btn-sm" onclick="abrirDerivar(${doc.id_documento}, ${doc.id_derivacion})"><i class="fas fa-share"></i></button>
                        </div>
                    </td>
                </tr>`;
        });
    });
}

function cargarAtendidos() {
    fetch('../../ajax/ajax_gestion_documentos.php?accion=listar_atendidos')
    .then(res => res.json())
    .then(response => {
        const tbody = document.querySelector("#tabla-atendidos tbody");
        tbody.innerHTML = "";
        
        if (!response.data || response.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No tienes documentos asignados.</td></tr>`;
            return;
        }

        response.data.forEach(doc => {
            let url_ver = doc.ultimo_archivo ? `../../${doc.ultimo_archivo}` : `../../pdf/generar_pdf_fut.php?id=${doc.id_documento}`;

            tbody.innerHTML += `
                <tr>
                    <td><strong>${doc.codigo_documento}</strong></td>
                    <td>${doc.asunto}</td>
                    <td>${doc.fecha_emision}</td>
                    <td>${doc.remitente}</td>
                    <td>
                        <span class="badge badge-info">${doc.tipo_destino.toUpperCase()}</span><br>
                        <small class="font-weight-bold">${doc.via_exacta}</small>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="${url_ver}" target="_blank" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye"></i></a>
                            
                            <button class="btn btn-outline-secondary btn-sm" onclick="liberarDocumento(${doc.id_documento}, ${doc.id_derivacion})" title="Liberar para el Área">
                                <i class="fas fa-undo"></i>
                            </button>

                            <button class="btn btn-warning btn-sm" onclick="verSeguimiento(${doc.id_documento})"><i class="fas fa-history"></i></button>
                            <button class="btn btn-primary btn-sm" onclick="abrirDerivar(${doc.id_documento}, ${doc.id_derivacion})" title="Derivar"><i class="fas fa-share"></i></button>
                            <button class="btn btn-dark btn-sm" onclick="abrirArchivar(${doc.id_documento})" title="Archivar"><i class="fas fa-archive"></i></button>
                        </div>
                    </td>
                </tr>`;
        });
    });
}

function liberarDocumento(idDoc, idDer) {
    Swal.fire({
        title: '¿Liberar documento?',
        text: "El documento volverá a estar disponible para que cualquier compañero del área pueda tomarlo.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        confirmButtonText: 'Sí, liberar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_documento', idDoc);
            formData.append('id_derivacion', idDer);

            fetch('../../ajax/ajax_gestion_documentos.php?accion=liberar_documento', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "ok") {
                    Swal.fire('Liberado', data.mensaje, 'success');
                    cargarAtendidos();
                    cargarRecibidos();
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            });
        }
    });
}

function cargarArchivo() {
    fetch('../../ajax/ajax_gestion_documentos.php?accion=listar_archivados')
    .then(res => res.json())
    .then(response => {
        const tbody = document.querySelector("#tabla-archivo tbody");
        tbody.innerHTML = "";
        if (!response.data || response.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No hay documentos archivados.</td></tr>`;
            return;
        }
        response.data.forEach(doc => {
            let btnDescargarFinal = doc.ruta_archivo_final 
                ? `<a href="../../${doc.ruta_archivo_final}" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-file-pdf"></i> PDF FINAL</a>` 
                : '<span class="badge badge-secondary">Sin archivo final</span>';

            tbody.innerHTML += `
                <tr>
                    <td><strong>${doc.codigo_documento}</strong></td>
                    <td>${doc.asunto}</td>
                    <td>${doc.fecha_archivado}</td>
                    <td>${doc.archivado_por}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" onclick="verSeguimiento(${doc.id_documento})"><i class="fas fa-history"></i></button>
                            ${btnDescargarFinal}
                        </div>
                    </td>
                </tr>`;
        });
    });
}

/**
 * --- Modales y Seguimiento ---
 */
function verSeguimiento(idDoc) {
    fetch(`../../ajax/ajax_gestion_documentos.php?accion=obtener_seguimiento&id=${idDoc}`)
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('timeline-container');
        container.innerHTML = "";
        let timeline = [];

        // 1. Procesar Historial (Eventos individuales de cada docente)
        // Dentro de tu fetch de seguimiento, actualiza el procesamiento del historial:
        if (data.historial) {
            data.historial.forEach(item => {
                // Lógica de prioridad de archivos:
                let archivoAMostrar = null;
                
                if (item.tipo_evento === 'creado') {
                    archivoAMostrar = item.archivo_principal; // El documento que originó todo
                } else {
                    archivoAMostrar = item.archivo_final || item.archivo_anexo;
                }

                timeline.push({
                    fecha: item.fecha,
                    titulo: item.tipo_evento === 'creado' ? 'DOCUMENTO INICIAL' : item.tipo_evento.toUpperCase(),
                    obs: item.observacion, // Aquí ahora saldrá la descripción real
                    usuario: item.nombres_usuario,
                    archivo: archivoAMostrar,
                    color: item.tipo_evento === 'creado' ? 'success' : 
                        (item.tipo_evento === 'atendido' ? 'warning' : 
                        (item.tipo_evento === 'archivado' ? 'danger' : 'info'))
                });
            });
        }

        // 2. Procesar Derivaciones (Flujo del sistema)
        if (data.derivaciones) {
            data.derivaciones.forEach(item => {
                timeline.push({
                    fecha: item.fecha_envio,
                    titulo: `DERIVADO A: ${item.destino_nombre}`,
                    obs: `Estado: ${item.estado.toUpperCase()}`,
                    usuario: 'Sistema',
                    archivo: item.archivo_adjunto, 
                    color: 'primary'
                });
            });
        }

        // 3. Ordenar por fecha descendente (Lo más reciente arriba)
        timeline.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

        // 4. Renderizar el HTML
        timeline.forEach(item => {
            let htmlArchivo = item.archivo 
                ? `<div class="mt-2">
                    <a href="../../${item.archivo}" target="_blank" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-file-pdf"></i> Ver Documento Adjunto
                    </a>
                   </div>` 
                : '';

            container.innerHTML += `
                <div class="border-left pl-4 pb-4 position-relative border-${item.color}" style="margin-left: 10px;">
                    <div class="position-absolute bg-${item.color}" style="left:-8px; top:0; width:15px; height:15px; border-radius:50%"></div>
                    <small class="text-muted font-weight-bold">${item.fecha} ${item.usuario ? '- ' + item.usuario : ''}</small><br>
                    <strong class="text-${item.color}">${item.titulo}</strong><br>
                    <span class="text-dark"><em>${item.obs || ''}</em></span>
                    ${htmlArchivo}
                </div>`;
        });

        $('#modalSeguimiento').modal('show');
    });
}

function abrirDerivar(idDoc, idDer) {
    document.getElementById('derivar_id_doc').value = idDoc;
    document.getElementById('derivar_id_parent').value = idDer;
    $('#modalDerivar').modal('show');
}

function abrirArchivar(idDoc) {
    document.getElementById('archivar_id_doc').value = idDoc;
    document.getElementById('archivar_mensaje').value = ""; // Limpiar
    $('#modalArchivar').modal('show');
}

function abrirAtender(idDoc, idDer) {
    document.getElementById('atender_id_doc').value = idDoc;
    document.getElementById('atender_id_derivacion').value = idDer; 
    $('#modalAtender').modal('show');
}

function cargarDestinos(tipo) {
    const select = document.getElementById('id_destino');
    if(!tipo) { select.innerHTML = '<option value="">Primero elija tipo...</option>'; return; }
    fetch(`../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=${tipo}`)
    .then(res => res.json())
    .then(data => {
        select.innerHTML = '';
        data.forEach(item => { select.innerHTML += `<option value="${item.id}">${item.nombre}</option>`; });
    });
}

/**
 * --- Inicialización y Eventos ---
 */
document.addEventListener("DOMContentLoaded", () => {
    cargarRecibidos();

    // 1. Derivar
    document.getElementById("formDerivar").onsubmit = function(e) {
        e.preventDefault();
        fetch('../../ajax/ajax_gestion_documentos.php?accion=procesar_derivacion', { method: 'POST', body: new FormData(this) })
        .then(res => res.json()).then(data => {
            if (data.status === "ok") {
                Swal.fire('Éxito', data.mensaje, 'success');
                $('#modalDerivar').modal('hide');
                cargarRecibidos(); cargarAtendidos();
            }
        });
    };

    // 2. Archivar (SOLO UNA DECLARACIÓN)
    document.getElementById("formArchivar").onsubmit = function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;

        fetch('../../ajax/ajax_gestion_documentos.php?accion=archivar_documento', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            if (data.status === "ok") {
                Swal.fire('¡Archivado!', data.mensaje, 'success');
                $('#modalArchivar').modal('hide');
                this.reset();
                cargarRecibidos(); cargarAtendidos(); cargarArchivo();
            } else {
                Swal.fire('Error', data.mensaje, 'error');
            }
        });
    };

    // 3. Atender
    document.getElementById("formAtender").onsubmit = function(e) {
        e.preventDefault();
        fetch('../../ajax/ajax_gestion_documentos.php?accion=atender_documento', { method: 'POST', body: new FormData(this) })
        .then(res => res.json()).then(data => {
            if (data.status === "ok") {
                Swal.fire('Atendido', data.mensaje, 'success');
                $('#modalAtender').modal('hide');
                cargarRecibidos(); cargarAtendidos();
            }
        });
    };
});
function cargarRecibidos() {
    fetch('../../ajax/ajax_gestion_documentos.php?accion=listar_recibidos')
    .then(res => res.json())
    .then(response => {
        const tbody = document.querySelector("#tabla-recibidos tbody");
        const contador = document.getElementById('count-recibidos');
        tbody.innerHTML = "";
        
        if (response.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No hay documentos pendientes.</td></tr>`;
            contador.innerText = "0";
            return;
        }

        contador.innerText = response.data.length;
        response.data.forEach(doc => {
            let url_ver = doc.ultimo_archivo ? `../../${doc.ultimo_archivo}` : `../../pdf/generar_pdf_fut.php?id=${doc.id_documento}`;

            tbody.innerHTML += `
                <tr>
                    <td><strong>${doc.codigo_documento}</strong></td>
                    <td>${doc.asunto}</td>
                    <td>${doc.fecha_emision}</td>
                    <td>${doc.remitente}</td>
                    <td>
                        <span class="badge badge-info">${doc.tipo_destino.toUpperCase()}</span><br>
                        <small class="font-weight-bold text-dark">${doc.via_exacta}</small>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="${url_ver}" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            <button class="btn btn-warning btn-sm" onclick="verSeguimiento(${doc.id_documento})"><i class="fas fa-history"></i></button>
                            <button class="btn btn-success btn-sm" onclick="abrirAtender(${doc.id_documento}, ${doc.id_derivacion})" title="Tomar y Atender">
                                <i class="fas fa-hand-holding"></i>
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="abrirDerivar(${doc.id_documento}, ${doc.id_derivacion})"><i class="fas fa-share"></i></button>
                        </div>
                    </td>
                </tr>`;
        });
    });
}
</script>
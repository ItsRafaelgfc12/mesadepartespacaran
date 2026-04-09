
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-clock text-primary"></i> Historial de documentos
</h1>

<div class="container-fluid">
   <div id="historial" class="card shadow mb-4 border-left-primary">
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-primary m-0">
                    <i class="fas fa-history"></i> Mis Documentos Enviados
                </h5>
                
                <div class="input-group" style="width: 350px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" id="buscadorEnviados" class="form-control" placeholder="Buscar código, asunto, área...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="tabla-historial-enviados">
                    <thead class="thead-dark">
                        <tr>
                            <th>Código</th>
                            <th>Asunto</th>
                            <th>Fecha Envío</th>
                            <th>Destino Actual</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
   </div>
</div>

<div class="modal fade" id="modalSeguimiento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-history"></i> Seguimiento del Documento</h5>
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
 * --- Lógica de Navegación ---
 */
function mostrar(seccionId, elemento) {
    // Ocultar todas las secciones que tengan la clase .seccion
    document.querySelectorAll('.seccion').forEach(sec => sec.classList.add('d-none'));
    
    // Mostrar la sección solicitada
    const target = document.getElementById(seccionId);
    if(target) {
        target.classList.remove('d-none');
    }

    // Actualizar estilo visual de la pestaña activa
    document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));
    if(elemento) elemento.classList.add('active');
    
    // Cargar datos según corresponda
    if(seccionId === 'recibidos') cargarRecibidos();
    if(seccionId === 'atendidos') cargarAtendidos();
    if(seccionId === 'archivo')   cargarArchivo();
    if(seccionId === 'historial') cargarHistorialEnviados(); 
}

/**
 * --- Cargar Historial de Enviados ---
 */
function cargarHistorialEnviados() {
    const tbody = document.querySelector("#tabla-historial-enviados tbody");
    
    fetch('../../ajax/ajax_gestion_documentos.php?accion=listar_historial_enviados')
    .then(res => res.json())
    .then(response => {
        tbody.innerHTML = "";
        
        if (!response.data || response.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No has enviado documentos aún.</td></tr>`;
            return;
        }

        response.data.forEach(doc => {
            let badgeClass = (doc.estado === 'archivado') ? 'dark' : (doc.estado === 'en_proceso' ? 'warning' : 'primary');

            tbody.innerHTML += `
                <tr>
                    <td><strong>${doc.codigo_documento}</strong></td>
                    <td>${doc.asunto}</td>
                    <td>${doc.fecha_emision}</td>
                    <td><span class="badge badge-light border text-dark">${doc.destino_actual || 'Enviado'}</span></td>
                    <td><span class="badge badge-${badgeClass}">${doc.estado.toUpperCase()}</span></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" onclick="verSeguimiento(${doc.id_documento})" title="Ver Seguimiento">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="../../${doc.ruta_archivo}" target="_blank" class="btn btn-info btn-sm">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>`;
        });
    });
}
/**
 * --- Función Unificada de Seguimiento ---
 * Carga el historial de eventos y derivaciones del documento
 */
function verSeguimiento(idDoc) {
    fetch(`../../ajax/ajax_gestion_documentos.php?accion=obtener_seguimiento&id=${idDoc}`)
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('timeline-container');
        if(!container) return; // Seguridad
        
        container.innerHTML = "";
        let timelineCompleto = [];

        // 1. Procesar Eventos (Creado, Atendido, Archivado)
        if (data.historial) {
            data.historial.forEach(item => {
                timelineCompleto.push({
                    es_derivacion: false,
                    fecha: item.fecha,
                    tipo: item.tipo_evento,
                    obs: item.observacion,
                    usuario: item.nombres_usuario || 'Sistema',
                    archivo: item.archivo_historial // Viene del modelo corregido
                });
            });
        }

        // 2. Procesar Derivaciones
        if (data.derivaciones) {
            data.derivaciones.forEach(item => {
                timelineCompleto.push({
                    es_derivacion: true,
                    fecha: item.fecha_envio,
                    tipo: 'DERIVADO',
                    destino: item.destino_nombre,
                    estado: item.estado,
                    archivo: item.archivo_adjunto // Viene del modelo corregido
                });
            });
        }

        // 3. Ordenar: Lo más nuevo arriba
        timelineCompleto.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

        // 4. Pintar el HTML
        if (timelineCompleto.length === 0) {
            container.innerHTML = "<p class='text-center text-muted'>No hay seguimiento disponible.</p>";
        } else {
            timelineCompleto.forEach(item => {
                let color = item.es_derivacion ? 'primary' : (item.tipo === 'atendido' ? 'warning' : (item.tipo === 'archivado' ? 'dark' : 'info'));
                
                let btnAdjunto = item.archivo 
                    ? `<div class='mt-2'><a href='../../${item.archivo}' target='_blank' class='btn btn-xs btn-outline-danger'><i class='fas fa-file-pdf'></i> Ver Adjunto</a></div>` 
                    : '';

                container.innerHTML += `
                    <div class="border-left pl-4 pb-4 position-relative border-${color}" style="margin-left: 10px;">
                        <div class="position-absolute bg-${color}" style="left:-8px; top:0; width:15px; height:15px; border-radius:50%"></div>
                        <small class="text-muted font-weight-bold">${item.fecha}</small><br>
                        <strong class="text-${color} text-uppercase">${item.es_derivacion ? 'DERIVADO A: ' + item.destino : item.tipo}</strong><br>
                        <span class="text-dark"><em>${item.obs || ''}</em></span>
                        ${item.usuario ? '<br><small class=' + 'text-primary' + '>Por: ' + item.usuario + '</small>' : ''}
                        ${btnAdjunto}
                    </div>`;
            });
        }

        // Mostrar el modal
        $('#modalSeguimiento').modal('show');
    })
    .catch(err => console.error("Error cargando seguimiento:", err));
}
// Inicializar carga si el div historial está visible por defecto
document.addEventListener("DOMContentLoaded", () => {
    const divHistorial = document.getElementById('historial');
    if(divHistorial && !divHistorial.classList.contains('d-none')){
        cargarHistorialEnviados();
    }
});

const inputBuscadorEnviados = document.getElementById("buscadorEnviados");

if (inputBuscadorEnviados) {
    inputBuscadorEnviados.addEventListener("input", function() {
        let textoBuscado = this.value.toLowerCase();
        let filas = document.querySelectorAll("#tabla-historial-enviados tbody tr");

        filas.forEach(fila => {
            // Obtenemos todo el texto de la fila (Código, asunto, destino, estado, etc)
            let contenidoFila = fila.textContent.toLowerCase();
            
            // Si el texto de la fila incluye lo que escribimos, se muestra, sino se oculta
            if (contenidoFila.includes(textoBuscado)) {
                fila.style.display = "";
            } else {
                fila.style.display = "none";
            }
        });
    });
}
</script>
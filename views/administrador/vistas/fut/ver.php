<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-folder-open text-primary"></i> Mis FUTs
</h1>

<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-body">

            <h5 class="text-center font-weight-bold mb-4">
                LISTADO DE FUT ENVIADOS
            </h5>

            <!-- BUSCADOR -->
            <div class="mb-3">
                <input type="text" id="buscadorFut" class="form-control" placeholder="Buscar FUT...">
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover" id="tablaFuts">

                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Asunto</th>
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

<!-- MODAL -->
<div class="modal fade" id="modalHistorial">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-stream"></i> Seguimiento del FUT
                </h5>
                <button class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <!-- 🔥 TIMELINE -->
                <div class="timeline" id="timelineHistorial"></div>

            </div>

        </div>
    </div>
</div>

<!-- 🔥 CSS TIMELINE -->
<style>
.timeline {
    position: relative;
    padding-left: 30px;
    border-left: 3px solid #007bff;
}
.timeline-item {
    position: relative;
    margin-bottom: 25px;
}
.timeline-item::before {
    content: "";
    position: absolute;
    left: -38px;
    top: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #007bff;
    border: 2px solid #fff;
}
/* Colores de los puntos */
.timeline-item.primary::before { background: #4e73df; }
.timeline-item.success::before { background: #1cc88a; }
.timeline-item.warning::before { background: #f6c23e; }
.timeline-item.danger::before { background: #e74a3b; }
.timeline-item.info::before { background: #36b9cc; }
.timeline-item.dark::before { background: #5a5c69; }
.timeline-item.secondary::before { background: #858796; }

.timeline-content {
    background: #f8f9fa;
    padding: 12px 18px;
    border-radius: 8px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
}
.btn-adjunto {
    margin-top: 10px;
    display: inline-block;
}
</style>

<script>

// ==========================
// 📦 CARGAR FUTS
// ==========================
function cargarFuts(){

    fetch('../../ajax/ajax_fut.php?accion=listar_mis_futs')
    .then(res => res.json())
    .then(data => {

        console.log(data);

        let tabla = document.querySelector("#tablaFuts tbody");
        tabla.innerHTML = "";

        if(!Array.isArray(data)){
            console.error("Respuesta inválida:", data);
            return;
        }

        data.forEach((row, i) => {

            tabla.innerHTML += `
                <tr>
                    <td>${i+1}</td>
                    <td><strong>${row.codigo_documento}</strong></td>
                    <td>${row.asunto}</td>
                    <td><span class="badge badge-${badgeEstado(row.estado)}">${textoEstado(row.estado)}</span></td>
                    <td>${row.fecha_emision}</td>
                    <td class="text-center">
                        <button class="btn btn-info btn-sm" onclick="verHistorial(${row.id_documento})" title="Ver Seguimiento">
                            <i class="fas fa-eye"></i>
                        </button>
                        
                        <a href="../../pdf/generar_pdf_fut.php?id=${row.id_documento}" target="_blank" class="btn btn-danger btn-sm" title="Descargar PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </td>
                </tr>
            `;
        });

    });
}

// ==========================
// 👁️ VER HISTORIAL (TIMELINE)
// ==========================
function verHistorial(id){
    fetch('../../ajax/ajax_fut.php?accion=historial&id=' + id)
    .then(res => res.json())
    .then(data => {
        // Unificamos y pintamos
        pintarTimeline(data.historial, data.derivaciones);
        $('#modalHistorial').modal('show');
    });
}

// ==========================
// 🎯 PINTAR TIMELINE UNIFICADO
// ==========================
function pintarTimeline(historial, derivaciones){
    let cont = document.getElementById("timelineHistorial");
    cont.innerHTML = "";
    
    let combinedTimeline = [];

    // 1. Mapeamos Eventos del Historial
    historial.forEach(item => {
        let color = "secondary";
        if(item.tipo_evento === 'creado') color = "primary";
        if(item.tipo_evento === 'enviado') color = "info";
        if(item.tipo_evento === 'atendido') color = "warning";
        if(item.tipo_evento === 'archivado') color = "dark";

        combinedTimeline.push({
            fecha: item.fecha,
            color: color,
            titulo: item.tipo_evento.toUpperCase(),
            descripcion: item.observacion ?? '',
            usuario: item.nombres_usuario ?? 'Sistema',
            archivo: item.ruta_archivo_final // Archivo final si es 'archivado'
        });
    });

    // 2. Mapeamos Derivaciones
    derivaciones.forEach(item => {
        combinedTimeline.push({
            fecha: item.fecha_envio,
            color: "info",
            titulo: "DERIVADO",
            descripcion: `${item.tipo_destino.toUpperCase()} → ${item.destino_nombre} <br> <span class="badge badge-secondary">${item.estado}</span>`,
            usuario: "Sistema",
            archivo: item.ruta_anexo // Anexo de derivación si existe
        });
    });

    // 3. Ordenamos por fecha (Lo más nuevo arriba)
    combinedTimeline.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

    // 4. Renderizamos
    combinedTimeline.forEach(item => {
        // Si hay archivo, preparamos el botón
        let btnArchivo = item.archivo 
            ? `<div class="btn-adjunto">
                <a href="../../${item.archivo}" target="_blank" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Ver Documento Adjunto
                </a>
               </div>` 
            : '';

        cont.innerHTML += `
            <div class="timeline-item ${item.color}">
                <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                        <strong>${item.titulo}</strong>
                        <small class="text-muted">${item.fecha}</small>
                    </div>
                    <p class="mb-1 text-dark">${item.descripcion}</p>
                    <small class="text-primary font-weight-bold"><i class="fas fa-user-edit"></i> ${item.usuario}</small>
                    ${btnArchivo}
                </div>
            </div>
        `;
    });
}
// ==========================
// 🎨 ESTADOS
// ==========================
function badgeEstado(estado){

    estado = estado.toLowerCase().trim();

    switch(estado){
        case 'borrador': return 'secondary';
        case 'enviado': return 'primary';
        case 'en_proceso': return 'warning';
        case 'finalizado': return 'success';
        case 'archivado': return 'dark';
        default: return 'light';
    }
}

function textoEstado(estado){

    estado = estado.toLowerCase().trim();

    switch(estado){
        case 'borrador': return 'Borrador';
        case 'enviado': return 'Enviado';
        case 'en_proceso': return 'En proceso';
        case 'finalizado': return 'Finalizado';
        case 'archivado': return 'Archivado';
        default: return estado;
    }
}

// ==========================
// 🔍 BUSCADOR + INIT
// ==========================
document.addEventListener("DOMContentLoaded", function(){

    cargarFuts();

    document.getElementById("buscadorFut").addEventListener("input", function(){

        let texto = this.value.toLowerCase();

        document.querySelectorAll("#tablaFuts tbody tr").forEach(fila => {
            fila.style.display = fila.innerText.toLowerCase().includes(texto) ? "" : "none";
        });

    });

});
</script>
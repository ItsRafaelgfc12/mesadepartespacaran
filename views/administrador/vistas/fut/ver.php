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
    margin-bottom: 20px;
}

.timeline-item::before {
    content: "";
    position: absolute;
    left: -10px;
    top: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #007bff;
}

.timeline-item.success::before { background: #28a745; }
.timeline-item.warning::before { background: #ffc107; }
.timeline-item.info::before { background: #17a2b8; }
.timeline-item.secondary::before { background: #6c757d; }

.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 8px;
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
                    <td>
                        <span class="badge badge-${badgeEstado(row.estado)}">
                            ${textoEstado(row.estado)}
                        </span>
                    </td>
                    <td>${row.fecha_emision}</td>
                    <td class="text-center">
                        <button class="btn btn-info btn-sm"
                            onclick="verHistorial(${row.id_documento})">
                            <i class="fas fa-eye"></i>
                        </button>
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

        pintarTimeline(data.historial, data.derivaciones);

        $('#modalHistorial').modal('show');
    });
}

// ==========================
// 🎯 TIMELINE
// ==========================
function pintarTimeline(historial, derivaciones){

    let cont = document.getElementById("timelineHistorial");
    cont.innerHTML = "";

    // HISTORIAL
    historial.forEach(item => {

        let color = "secondary";

        if(item.tipo_evento === 'creado') color = "primary";
        if(item.tipo_evento === 'enviado') color = "info";
        if(item.tipo_evento === 'recibido') color = "warning";
        if(item.tipo_evento === 'finalizado') color = "success";

        cont.innerHTML += `
            <div class="timeline-item ${color}">
                <div class="timeline-content">
                    <strong>${item.tipo_evento.toUpperCase()}</strong><br>
                    ${item.observacion ?? ''}
                    <br>
                    <small>${item.fecha} - ${item.nombres_usuario ?? ''}</small>
                </div>
            </div>
        `;
    });

    // DERIVACIONES
    derivaciones.forEach(item => {

        cont.innerHTML += `
            <div class="timeline-item info">
                <div class="timeline-content">
                    <i class="fas fa-share text-primary"></i>
                    <strong>DERIVADO</strong><br>
                    ${item.tipo_destino.toUpperCase()} → ${item.destino_nombre}
                    <br>
                    <span class="badge badge-secondary">${item.estado}</span>
                    <br>
                    <small>${item.fecha_envio}</small>
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
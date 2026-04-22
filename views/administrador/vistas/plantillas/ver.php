<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-copy text-primary"></i> Directorio de Plantillas</h1>
    <a href="index.php?vista=plantillas/subir" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Subir Nueva
    </a>
</div>

<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-body">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" id="filtroPlantilla" class="form-control" placeholder="Buscar por título o descripción..." onkeyup="filtrarPlantillas()">
            </div>
        </div>
    </div>

    <div class="row" id="contenedorPlantillas">
        </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    cargarPlantillas();
});

function cargarPlantillas() {
    fetch('../../ajax/ajax_plantillas.php?accion=listar')
    .then(res => res.json())
    .then(response => {
        const contenedor = document.getElementById("contenedorPlantillas");
        contenedor.innerHTML = "";

        if (!response.data || response.data.length === 0) {
            contenedor.innerHTML = `<div class="col-12 text-center text-muted p-5">
                <i class="fas fa-folder-open fa-3x mb-3"></i><br>No hay plantillas disponibles actualmente.
            </div>`;
            return;
        }

        response.data.forEach(p => {
            let fechaFormat = p.fecha_creacion ? p.fecha_creacion.split(' ')[0] : 'Sin fecha';

            // 🔥 LÓGICA DE PERMISOS PARA EL BOTÓN ELIMINAR
            let esPropietario = (p.id_usuario == response.id_usuario_actual);
            let esAdmin = (response.id_rol_actual == 1); // 1 = Administrador
            
            let btnEliminar = '';
            let claseBtnDescargar = 'w-100'; // Por defecto, descarga ocupa todo

            // Si es dueño o admin, achicamos descarga y agregamos el botón eliminar
            if (esPropietario || esAdmin) {
                claseBtnDescargar = 'w-75 mr-1'; 
                btnEliminar = `
                <button class="btn btn-outline-danger btn-sm w-25" onclick="eliminarPlantilla(${p.id_plantilla})" title="Eliminar Plantilla">
                    <i class="fas fa-trash"></i>
                </button>`;
            }

            contenedor.innerHTML += `
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4 card-plantilla">
                    <div class="card shadow h-100 border-bottom-primary">
                        <img src="../../${p.url_imagen}" class="card-img-top border-bottom" alt="${p.titulo}" style="height: 180px; object-fit: cover; background:#f8f9fa;">
                        
                        <div class="card-body">
                            <h6 class="font-weight-bold text-primary titulo-p">${p.titulo}</h6>
                            <p class="card-text small text-muted desc-p">${p.descripcion}</p>
                            <div class="small mt-3">
                                <strong><i class="fas fa-user"></i> Subido por:</strong> ${p.autor}<br>
                                <strong><i class="fas fa-calendar-alt"></i> Fecha:</strong> ${fechaFormat}
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white d-flex justify-content-between">
                            <a href="../../${p.ruta_archivo}" target="_blank" class="btn btn-success btn-sm ${claseBtnDescargar}" download>
                                <i class="fas fa-download"></i> Descargar
                            </a>
                            ${btnEliminar}
                        </div>
                    </div>
                </div>`;
        });
    })
    .catch(error => {
        console.error("Error al cargar plantillas:", error);
        document.getElementById("contenedorPlantillas").innerHTML = `<div class="col-12 text-center text-danger p-5">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br>Ocurrió un error al cargar las plantillas. Revisa la consola.
        </div>`;
    });
}

function filtrarPlantillas() {
    let busqueda = document.getElementById('filtroPlantilla').value.toLowerCase();
    let cards = document.querySelectorAll('.card-plantilla');

    cards.forEach(card => {
        let titulo = card.querySelector('.titulo-p').innerText.toLowerCase();
        let desc = card.querySelector('.desc-p').innerText.toLowerCase();
        if (titulo.includes(busqueda) || desc.includes(busqueda)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

function eliminarPlantilla(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "La plantilla ya no será visible en el directorio.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let fd = new FormData();
            fd.append('id', id);

            fetch('../../ajax/ajax_plantillas.php?accion=eliminar', {
                method: 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'ok') {
                    Swal.fire('Eliminado', data.mensaje, 'success');
                    cargarPlantillas();
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            });
        }
    });
}
</script>
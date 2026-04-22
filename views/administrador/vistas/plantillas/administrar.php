<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-folder-open text-primary"></i> Administrar Plantillas
</h1>

<div class="container-fluid">

    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="w-50">
                    <label class="mb-1 font-weight-bold text-gray-700">
                        <i class="fas fa-search"></i> Búsqueda rápida
                    </label>
                    <input type="text" id="buscador" class="form-control form-control-sm" placeholder="Buscar por nombre, descripción o autor...">
                </div>
                <div>
                    <a href="index.php?vista=plantillas/listar" class="btn btn-secondary btn-sm mr-2">
                        <i class="fas fa-th-large"></i> Ver Galería
                    </a>
                    <a href="index.php?vista=plantillas/subir" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload"></i> Nueva Plantilla
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle" id="tablaPlantillas" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="30%">Plantilla</th>
                            <th width="20%">Autor y Fecha</th>
                            <th width="10%" class="text-center">Recursos</th>
                            <th width="10%" class="text-center">Estado</th>
                            <th width="25%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaPlantillas">
                        <tr>
                            <td colspan="6" class="text-center p-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Cargando plantillas...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    cargarTablaPlantillas();
});

// 1. Cargar datos desde el Backend
function cargarTablaPlantillas() {
    fetch('../../ajax/ajax_plantillas.php?accion=listar')
    .then(res => res.json())
    .then(response => {
        const tbody = document.getElementById("cuerpoTablaPlantillas");
        tbody.innerHTML = "";

        if (!response.data || response.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted p-4">No hay plantillas registradas.</td></tr>`;
            return;
        }

        response.data.forEach(p => {
            // Formatear Fecha
            let fechaFormat = p.fecha_creacion ? p.fecha_creacion.split(' ')[0] : '-';
            
            // Formatear Estado (Color de Badge)
            let badgeEstado = p.estado === 'activo' 
                ? '<span class="badge badge-success p-2"><i class="fas fa-check-circle"></i> Activo</span>' 
                : '<span class="badge badge-secondary p-2"><i class="fas fa-times-circle"></i> Inactivo</span>';

            // Validar Permisos (Para mostrar/ocultar botones de Editar/Eliminar)
            let esPropietario = (p.id_usuario == response.id_usuario_actual);
            let esAdmin = (response.id_rol_actual == 1); // 1 = Administrador
            
            let btnEditar = '';
            let btnEliminar = '';

            if (esPropietario || esAdmin) {
                btnEditar = `
                    <a href="index.php?vista=plantillas/editar&id=${p.id_plantilla}" class="btn btn-warning btn-sm shadow-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>`;
                btnEliminar = `
                    <button class="btn btn-danger btn-sm shadow-sm" title="Eliminar" onclick="eliminarPlantilla(${p.id_plantilla})">
                        <i class="fas fa-trash"></i>
                    </button>`;
            }

            // Construir Fila
            let fila = `
                <tr>
                    <td class="font-weight-bold text-gray-600">#${p.id_plantilla}</td>
                    <td>
                        <strong class="text-primary">${p.titulo}</strong><br>
                        <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">${p.descripcion}</small>
                    </td>
                    <td>
                        <div class="small">
                            <i class="fas fa-user text-gray-500"></i> ${p.autor}<br>
                            <i class="fas fa-calendar-alt text-gray-500"></i> ${fechaFormat}
                        </div>
                    </td>
                    <td class="text-center">
                        <a href="../../${p.url_imagen}" target="_blank" class="btn btn-outline-info btn-sm" title="Ver Portada">
                            <i class="fas fa-image"></i>
                        </a>
                        <a href="../../${p.ruta_archivo}" target="_blank" class="btn btn-outline-success btn-sm" download title="Descargar">
                            <i class="fas fa-file-download"></i>
                        </a>
                    </td>
                    <td class="text-center align-middle">${badgeEstado}</td>
                    <td class="text-center align-middle">
                        <div class="btn-group" role="group">
                            ${btnEditar}
                            ${btnEliminar}
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += fila;
        });
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("cuerpoTablaPlantillas").innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error de conexión.</td></tr>`;
    });
}

// 2. Buscador en Tiempo Real (Filtrado de JS)
const buscador = document.getElementById("buscador");
buscador.addEventListener("input", () => {
    const filtro = buscador.value.toLowerCase();
    document.querySelectorAll("#cuerpoTablaPlantillas tr").forEach(fila => {
        const texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});

// 3. Función Eliminar (Reutilizada)
function eliminarPlantilla(id) {
    Swal.fire({
        title: '¿Mover a la papelera?',
        text: "La plantilla pasará a estado inactivo y no se verá en el directorio.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
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
                    Swal.fire('Eliminada', data.mensaje, 'success');
                    cargarTablaPlantillas(); // Recargar la tabla
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            });
        }
    });
}
</script>
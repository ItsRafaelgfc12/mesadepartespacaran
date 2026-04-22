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
                    <a href="home.php?vista=plantillas/ver" class="btn btn-secondary btn-sm mr-2">
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

<div class="modal fade" id="modalEditarPlantilla" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit"></i> Editar Plantilla</h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarPlantilla" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_plantilla" id="edit_id_plantilla">
                    
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label>Título</label>
                            <input type="text" name="titulo" id="edit_titulo" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Estado</label>
                            <select name="estado" id="edit_estado" class="form-control" required>
                                <option value="activo">Activo (Visible)</option>
                                <option value="inactivo">Inactivo (Oculto)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="row bg-light p-2 rounded mb-3 border">
                        <div class="col-md-6 form-group mb-0">
                            <label class="text-primary font-weight-bold">Permiso de Acceso</label>
                            <select name="tipo_acceso" id="edit_tipo_acceso" class="form-control" onchange="cargarDestinosEditar(this)" required>
                                <option value="publico">Público (Todos)</option>
                                <option value="rol">Por Rol</option>
                                <option value="area">Por Área</option>
                                <option value="cargo">Por Cargo</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-0" id="div_referencia_edit" style="display:none;">
                            <label class="text-primary font-weight-bold">Destino Específico</label>
                            <select name="id_referencia" id="select_referencia_edit" class="form-control">
                                </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Cambiar Imagen (Opcional)</label>
                            <input type="file" name="imagen" id="edit_imagen" class="form-control-file border p-1 rounded" accept="image/*">
                            <div class="text-center mt-2">
                                <img id="preview_edit" src="#" alt="Portada actual" style="max-height: 120px; object-fit: contain;" class="border p-1 bg-light shadow-sm">
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Cambiar Archivo (Opcional)</label>
                            <input type="file" name="archivo" id="edit_archivo" class="form-control-file border p-1 rounded" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf">
                            <div class="mt-3">
                                <strong>Archivo actual:</strong><br>
                                <a id="link_archivo_edit" href="#" target="_blank" class="badge badge-success p-2 mt-1">
                                    <i class="fas fa-file-download"></i> Ver / Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning font-weight-bold" id="btnGuardarEdicion">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    cargarTablaPlantillas();
});

// 1. Cargar Tabla
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
            let fechaFormat = p.fecha_creacion ? p.fecha_creacion.split(' ')[0] : '-';
            let badgeEstado = p.estado === 'activo' 
                ? '<span class="badge badge-success p-2"><i class="fas fa-check-circle"></i> Activo</span>' 
                : '<span class="badge badge-secondary p-2"><i class="fas fa-times-circle"></i> Inactivo</span>';

            let esPropietario = (p.id_usuario == response.id_usuario_actual);
            let esAdmin = (response.id_rol_actual == 1);
            
            let btnEditar = '';
            let btnEliminar = '';

            // 🔥 AQUÍ CAMBIAMOS EL ENLACE POR UN BOTÓN QUE ABRE EL MODAL
            if (esPropietario || esAdmin) {
                btnEditar = `
                    <button class="btn btn-warning btn-sm shadow-sm mr-1" title="Editar" onclick="abrirModalEditar(${p.id_plantilla})">
                        <i class="fas fa-edit"></i>
                    </button>`;
                btnEliminar = `
                    <button class="btn btn-danger btn-sm shadow-sm" title="Eliminar" onclick="eliminarPlantilla(${p.id_plantilla})">
                        <i class="fas fa-trash"></i>
                    </button>`;
            }

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

// 2. Buscador en Tiempo Real
const buscador = document.getElementById("buscador");
buscador.addEventListener("input", () => {
    const filtro = buscador.value.toLowerCase();
    document.querySelectorAll("#cuerpoTablaPlantillas tr").forEach(fila => {
        const texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});

// 3. Eliminar
function eliminarPlantilla(id) {
    Swal.fire({
        title: '¿Mover a la papelera?',
        text: "La plantilla pasará a estado inactivo.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            let fd = new FormData();
            fd.append('id', id);
            fetch('../../ajax/ajax_plantillas.php?accion=eliminar', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'ok') {
                    Swal.fire('Eliminada', data.mensaje, 'success');
                    cargarTablaPlantillas();
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            });
        }
    });
}

// ==========================================
// LÓGICA DEL MODAL DE EDICIÓN
// ==========================================

// 4. Abrir Modal y cargar datos
function abrirModalEditar(id_plantilla) {
    // Limpiamos el formulario antes de abrirlo
    document.getElementById('formEditarPlantilla').reset();
    
    fetch(`../../ajax/ajax_plantillas.php?accion=obtener&id=${id_plantilla}`)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'ok') {
            const data = response.data;
            
            // Llenar campos de texto
            document.getElementById('edit_id_plantilla').value = data.id_plantilla;
            document.getElementById('edit_titulo').value = data.titulo;
            document.getElementById('edit_descripcion').value = data.descripcion;
            document.getElementById('edit_estado').value = data.estado;
            document.getElementById('edit_tipo_acceso').value = data.tipo_acceso;
            
            // Llenar recursos visuales
            document.getElementById('preview_edit').src = `../../${data.url_imagen}`;
            document.getElementById('link_archivo_edit').href = `../../${data.ruta_archivo}`;

            // Cargar select dependiente si aplica
            if (data.tipo_acceso !== 'publico') {
                cargarDestinosEditar(document.getElementById('edit_tipo_acceso'), data.id_referencia);
            } else {
                document.getElementById('div_referencia_edit').style.display = 'none';
            }

            // Mostrar el modal (Usando jQuery que viene con Bootstrap)
            $('#modalEditarPlantilla').modal('show');
        } else {
            Swal.fire('Error', response.mensaje, 'error');
        }
    });
}

// 5. Cargar Destinos Dinámicos (Roles/Áreas)
function cargarDestinosEditar(selectElement, idSeleccionado = null) {
    const tipo = selectElement.value;
    const divRef = document.getElementById('div_referencia_edit');
    const selectRef = document.getElementById('select_referencia_edit');

    if (tipo === 'publico') {
        divRef.style.display = 'none';
        selectRef.required = false;
        return;
    }

    divRef.style.display = 'block';
    selectRef.required = true;

    fetch(`../../ajax/ajax_utilitarios.php?accion=listar_destinos&tipo=${tipo}`)
    .then(res => res.json())
    .then(data => {
        selectRef.innerHTML = '<option value="">Seleccione...</option>';
        data.forEach(item => {
            let selected = (item.id == idSeleccionado) ? 'selected' : '';
            selectRef.innerHTML += `<option value="${item.id}" ${selected}>${item.nombre}</option>`;
        });
    });
}

// 6. Previsualizar imagen nueva localmente en el modal
document.getElementById('edit_imagen').onchange = evt => {
    const [file] = evt.target.files;
    if (file) document.getElementById('preview_edit').src = URL.createObjectURL(file);
}

// 7. Enviar datos editados al Backend
document.getElementById('formEditarPlantilla').onsubmit = function(e) {
    e.preventDefault();
    let btn = document.getElementById('btnGuardarEdicion');
    let textoOriginal = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    fetch('../../ajax/ajax_plantillas.php?accion=editar', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = textoOriginal;

        if (data.status === "ok") {
            $('#modalEditarPlantilla').modal('hide'); // Cerramos el modal
            Swal.fire('¡Actualizado!', data.mensaje, 'success');
            cargarTablaPlantillas(); // Recargamos la tabla para ver los cambios
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = textoOriginal;
        Swal.fire('Error', 'Problema de conexión.', 'error');
    });
};
</script>
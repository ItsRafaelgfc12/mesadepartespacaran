<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-users text-primary"></i> Administrar Usuarios
</h1>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-primary m-0"><i class="fas fa-filter"></i> Filtros</h6>
                <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalUsuario">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </button>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label><i class="fas fa-search"></i> Buscar</label>
                    <input type="text" id="buscador" class="form-control" placeholder="Nombre, correo...">
                </div>
                <div class="form-group col-md-2">
                    <label><i class="fas fa-user-tag"></i> Rol</label>
                    <select id="filtroRol" class="form-control"><option value="">Todos</option></select>
                </div>
                <div class="form-group col-md-2">
                    <label><i class="fas fa-sitemap"></i> Área</label>
                    <select id="filtroArea" class="form-control"><option value="">Todas</option></select>
                </div>
                <div class="form-group col-md-2">
                    <label><i class="fas fa-user-tie"></i> Cargo</label>
                    <select id="filtroCargo" class="form-control"><option value="">Todos</option></select>
                </div>
                <div class="form-group col-md-3">
                    <label><i class="fas fa-graduation-cap"></i> Programa</label>
                    <select id="filtroPrograma" class="form-control"><option value="">Todos</option></select>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaUsuarios" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th><th>Usuario</th><th>Rol</th><th>Área</th><th>Cargo</th><th>Programa</th><th>Estado</th><th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaUsuarios">
                        <tr>
                            <td colspan="8" class="text-center p-4">
                                <div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Cargando...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="tituloModalUsuario"><i class="fas fa-user-plus"></i> Registrar Usuario</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUsuario">
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" id="id_usuario">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nombres</label><input type="text" name="nombres_usuario" id="nombres_usuario" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Apellidos</label><input type="text" name="apellidos_usuario" id="apellidos_usuario" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Correo Electrónico</label><input type="email" name="email_per" id="email_per" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Contraseña</label><input type="password" name="password" id="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                        </div>
                    </div>
                    <div class="row bg-light p-2 rounded border mt-2">
                        <div class="col-md-6 form-group">
                            <label>Rol <span class="text-danger">*</span></label>
                            <select name="id_rol" id="id_rol" class="form-control" required><option value="">Seleccione...</option></select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Área</label>
                            <select name="id_area" id="id_area" class="form-control"><option value="">Seleccione...</option></select>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label>Cargo</label>
                            <select name="id_cargo" id="id_cargo" class="form-control"><option value="">Seleccione...</option></select>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label>Programa</label>
                            <select name="id_programa" id="id_programa" class="form-control"><option value="">Seleccione...</option></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarUsuario"><i class="fas fa-save"></i> Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let todosLosCargos = []; // Variable global para guardar los cargos en memoria

document.addEventListener("DOMContentLoaded", () => {
    cargarListasDesplegables();
    cargarTablaUsuarios();

    // Limpiar el modal al cerrarlo
    const modalUsuario = document.getElementById('modalUsuario');
    if (modalUsuario) {
        modalUsuario.addEventListener('hidden.bs.modal', function () {
            document.getElementById('formUsuario').reset();
            document.getElementById('id_usuario').value = '';
            document.getElementById('tituloModalUsuario').innerHTML = '<i class="fas fa-user-plus"></i> Registrar Usuario';
            
            // Al limpiar, también reseteamos la lista de cargos en el modal
            llenarSelect('id_cargo', todosLosCargos, 'id', 'nombre', 'Seleccione Cargo...');
        });
    }
});

// ==========================================
// 1. CARGAR SELECTS DINÁMICOS
// ==========================================
function cargarListasDesplegables() {
    fetch('../../ajax/ajax_usuario.php?accion=ObtenerOpciones')
    .then(res => {
        if (!res.ok) throw new Error("Error en la red o archivo no encontrado");
        return res.json();
    })
    .then(response => {
        if(response.status === 'ok') {
            const d = response.data;
            todosLosCargos = d.cargos; // Guardamos los cargos para usarlos después
            
            // Llenar Filtros (Buscador)
            llenarSelect('filtroRol', d.roles, 'nombre', 'nombre', 'Todos');
            llenarSelect('filtroArea', d.areas, 'nombre', 'nombre', 'Todas');
            llenarSelect('filtroCargo', d.cargos, 'nombre', 'nombre', 'Todos');
            llenarSelect('filtroPrograma', d.programas, 'nombre', 'nombre', 'Todos');

            // Llenar Modal de Registro
            llenarSelect('id_rol', d.roles, 'id', 'nombre', 'Seleccione Rol...');
            llenarSelect('id_area', d.areas, 'id', 'nombre', 'Seleccione Área... (Opcional)');
            llenarSelect('id_cargo', d.cargos, 'id', 'nombre', 'Seleccione Cargo...');
            llenarSelect('id_programa', d.programas, 'id', 'nombre', 'Seleccione Programa...');

            // 🔥 SELECT DEPENDIENTE: Cuando cambia el Área, filtramos los Cargos
            document.getElementById('id_area').addEventListener('change', function() {
                const idAreaSeleccionada = this.value;
                if(idAreaSeleccionada) {
                    const cargosFiltrados = todosLosCargos.filter(c => c.id_area == idAreaSeleccionada);
                    llenarSelect('id_cargo', cargosFiltrados, 'id', 'nombre', 'Seleccione Cargo...');
                } else {
                    llenarSelect('id_cargo', todosLosCargos, 'id', 'nombre', 'Seleccione Cargo...');
                }
            });
        }
    })
    .catch(error => console.error("Error al cargar opciones (revisa la ruta):", error));
}

function llenarSelect(idElemento, arrayDatos, propiedadValor, propiedadTexto, textoDefault) {
    const select = document.getElementById(idElemento);
    if(!select) return;
    
    let html = `<option value="">${textoDefault}</option>`;
    arrayDatos.forEach(item => {
        html += `<option value="${item[propiedadValor]}">${item[propiedadTexto]}</option>`;
    });
    select.innerHTML = html;
}

// ==========================================
// 2. CARGAR TABLA
// ==========================================
function cargarTablaUsuarios() {
    fetch('../../ajax/ajax_usuario.php?accion=ListarUsuarios')
    .then(res => {
        if (!res.ok) throw new Error("Error en la red o archivo no encontrado");
        return res.json();
    })
    .then(response => {
        const tbody = document.getElementById("cuerpoTablaUsuarios");
        tbody.innerHTML = "";

        if (!response.data || response.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted p-4">No hay usuarios registrados.</td></tr>`;
            return;
        }

        response.data.forEach(u => {
            let badgeEstado = u.estado === 'activo' ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-secondary">Inactivo</span>';
            let badgeRol = u.rol ? `<span class="badge badge-primary">${u.rol}</span>` : '<span class="badge badge-dark">Sin Rol</span>';
            
            tbody.innerHTML += `
                <tr>
                    <td class="font-weight-bold text-gray-600">${u.id_usuario}</td>
                    <td><strong>${u.nombres_usuario} ${u.apellidos_usuario}</strong><br><small class="text-muted">${u.correo}</small></td>
                    <td>${badgeRol}</td>
                    <td>${u.area || '<span class="text-muted small">-</span>'}</td>
                    <td>${u.cargo || '<span class="text-muted small">-</span>'}</td>
                    <td>${u.programa || '<span class="text-muted small">-</span>'}</td>
                    <td>${badgeEstado}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm shadow-sm" title="Editar" onclick="editarUsuario(${u.id_usuario})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm shadow-sm" title="Eliminar" onclick="eliminarUsuario(${u.id_usuario})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
        });
    })
    .catch(error => {
        console.error("Error al cargar usuarios (revisa la ruta):", error);
        document.getElementById("cuerpoTablaUsuarios").innerHTML = `<tr><td colspan="8" class="text-center text-danger">Error de conexión. Verifica la consola.</td></tr>`;
    });
}

// ==========================================
// 3. GUARDAR USUARIO (Registro/Edición)
// ==========================================
document.getElementById('formUsuario').onsubmit = function(e) {
    e.preventDefault();
    let fd = new FormData(this);
    
    fetch('../../ajax/ajax_usuario.php?accion=RegistrarUsuario', {
        method: 'POST',
        body: fd
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'ok') {
            let btnCerrar = document.querySelector('#modalUsuario .close');
            if(btnCerrar) btnCerrar.click();

            Swal.fire('Éxito', data.mensaje, 'success');
            cargarTablaUsuarios();
            this.reset();
        } else {
            Swal.fire('Error', data.mensaje, 'error');
        }
    })
    .catch(err => Swal.fire('Error', 'No se pudo conectar al servidor.', 'error'));
};

// ==========================================
// 4. LÓGICA DE FILTROS 
// ==========================================
const buscador = document.getElementById("buscador");
const filtroRol = document.getElementById("filtroRol");
const filtroArea = document.getElementById("filtroArea");
const filtroCargo = document.getElementById("filtroCargo");
const filtroPrograma = document.getElementById("filtroPrograma");

function filtrarTabla() {
    const textoBuscador = buscador ? buscador.value.toLowerCase() : "";
    const textoRol = filtroRol ? filtroRol.value.toLowerCase() : "";
    const textoArea = filtroArea ? filtroArea.value.toLowerCase() : "";
    const textoCargo = filtroCargo ? filtroCargo.value.toLowerCase() : "";
    const textoPrograma = filtroPrograma ? filtroPrograma.value.toLowerCase() : "";

    document.querySelectorAll("#cuerpoTablaUsuarios tr").forEach(fila => {
        const celdas = fila.querySelectorAll("td");
        if (celdas.length < 8) return; 

        const usuarioTexto = celdas[1].innerText.toLowerCase();
        const rolTexto = celdas[2].innerText.toLowerCase();
        const areaTexto = celdas[3].innerText.toLowerCase();
        const cargoTexto = celdas[4].innerText.toLowerCase();
        const programaTexto = celdas[5].innerText.toLowerCase();

        const matchBuscador = textoBuscador === "" || usuarioTexto.includes(textoBuscador);
        const matchRol = textoRol === "" || rolTexto.includes(textoRol);
        const matchArea = textoArea === "" || areaTexto.includes(textoArea);
        const matchCargo = textoCargo === "" || cargoTexto.includes(textoCargo);
        const matchPrograma = textoPrograma === "" || programaTexto.includes(textoPrograma);

        fila.style.display = (matchBuscador && matchRol && matchArea && matchCargo && matchPrograma) ? "" : "none";
    });
}

[buscador, filtroRol, filtroArea, filtroCargo, filtroPrograma].forEach(el => {
    if (el) el.addEventListener("change", filtrarTabla); 
    if (el && el.id === 'buscador') el.addEventListener("input", filtrarTabla); 
});

// ==========================================
// 5. FUNCIÓN EDITAR
// ==========================================
function editarUsuario(id) {
    fetch(`../../ajax/ajax_usuario.php?accion=ObtenerUsuario&id=${id}`)
    .then(res => res.json())
    .then(data => {
        if(data.status === 'ok') {
            const u = data.data;
            
            document.getElementById('id_usuario').value = u.id_usuario;
            document.getElementById('nombres_usuario').value = u.nombres_usuario;
            document.getElementById('apellidos_usuario').value = u.apellidos_usuario;
            document.getElementById('email_per').value = u.email_per;
            document.getElementById('id_rol').value = u.id_rol || '';
            document.getElementById('id_programa').value = u.id_programa || '';
            document.getElementById('password').value = ''; // Contraseña en blanco por seguridad

            // Lógica para setear Área y Cargo
            const selectArea = document.getElementById('id_area');
            selectArea.value = u.id_area || '';
            
            // Disparamos el evento para que dibuje solo los cargos de esta área
            selectArea.dispatchEvent(new Event('change'));

            // Damos un pequeño respiro para que el DOM se actualice antes de seleccionar el cargo
            setTimeout(() => { 
                document.getElementById('id_cargo').value = u.id_cargo || ''; 
            }, 50);

            // Cambiamos el título y abrimos el modal
            document.getElementById('tituloModalUsuario').innerHTML = '<i class="fas fa-edit"></i> Editar Usuario';
            
            // Si la consola arroja "$ is not defined", es porque jQuery carga tarde. 
            // Usamos esto como alternativa nativa si no funciona el $('#modalUsuario').modal('show'):
            if (typeof $ !== 'undefined') {
                $('#modalUsuario').modal('show');
            } else {
                // Alternativa Bootstrap 5 Vanilla (si usas BS5)
                try {
                    let myModal = new bootstrap.Modal(document.getElementById('modalUsuario'));
                    myModal.show();
                } catch(e) { console.error("No se pudo abrir el modal programáticamente."); }
            }
        } else {
            Swal.fire('Error', 'No se pudieron obtener los datos.', 'error');
        }
    })
    .catch(error => console.error("Error al obtener usuario:", error));
}

// ==========================================
// 6. FUNCIÓN ELIMINAR (Inhabilitar)
// ==========================================
function eliminarUsuario(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "El usuario pasará a estado Inactivo y no podrá ingresar al sistema.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<i class="fas fa-ban"></i> Sí, inhabilitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let fd = new FormData();
            fd.append('id_usuario', id);
            
            fetch('../../ajax/ajax_usuario.php?accion=EliminarUsuario', { 
                method: 'POST', 
                body: fd 
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'ok') {
                    Swal.fire('Inhabilitado', data.mensaje, 'success');
                    cargarTablaUsuarios(); 
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Problema de conexión.', 'error'));
        }
    });
}
</script>
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-file-alt text-primary"></i> Crear FUT
</h1>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="text-center font-weight-bold mb-4">
                FORMATO ÚNICO DE TRÁMITE (FUT)
            </h5>
                <form id="formFut" enctype="multipart/form-data">
                <div class="form-group">
                    <label><i class="fas fa-building"></i> Dependencia o autoridad</label>
                    <input type="text" name="dependencia" class="form-control" placeholder="Ej: Sr. Director" required>
                </div>
                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-user"></i> 1. Datos del Solicitante
                    </h5>

                    <div class="form-row mt-3">
                        <div class="form-group col-md-6">
                            <label>Nombres</label>
                            <input type="text" id="nombres" class="form-control" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Apellidos</label>
                            <input type="text" id="apellidos" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-id-badge"></i> Tipo de Documento</label>
                            <select id="tipo_documento" class="form-control" disabled>
                                <option value="1">DNI</option>
                                <option value="2">Carné de Extranjería</option>
                                <option value="3">Pasaporte</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-hashtag"></i> Número de Documento</label>
                            <input type="text" id="numero_documento" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map"></i> Dirección</label>
                        <input type="text" id="direccion" class="form-control" readonly>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="text" id="telefono" class="form-control" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label><i class="fas fa-envelope"></i> Correo Electrónico</label>
                            <input type="email" id="correo" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                 <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-file-signature"></i> 2. Detalle de la Solicitud
                    </h5>

                    <div class="form-group mt-3">
                        <label><i class="fas fa-heading"></i> Asunto</label>
                        <input type="text" name="asunto" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Descripción</label>
                        <textarea name="descripcion" rows="5" class="form-control" placeholder="Detalle su solicitud..." required></textarea>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="text-primary border-bottom pb-2">
                        <i class="fas fa-paperclip"></i> Información Adicional
                    </h5>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-file-pdf"></i> Documentos anexados (PDF)
                        </label>

                        <small class="form-text text-muted">
                            Todos los documentos deben estar en un solo archivo PDF.
                            <a href="https://www.ilovepdf.com/es/unir_pdf" target="_blank">
                                Unir PDF aquí
                            </a>
                        </small>

                        <input type="file" name="doc_anexado" class="form-control-file">
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="fut_listado.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
    function cargarUsuario(){
    // 🔥 EL CAMBIO ESTÁ AQUÍ: Agregamos ?accion=perfil
    fetch('../../ajax/ajax_usuario.php?accion=DatosPersonalesFut')
    .then(res => res.json())
    .then(data => {

        document.getElementById("nombres").value = data.nombres_usuario;
        document.getElementById("apellidos").value = data.apellidos_usuario;
        document.getElementById("tipo_documento").value = data.tipo_documento;
        document.getElementById("numero_documento").value = data.numero_documento;
        document.getElementById("direccion").value = data.direccion_usuario;
        document.getElementById("telefono").value = data.celular_usuario;
        document.getElementById("correo").value = data.email_per;

    })
    .catch(error => console.error("Error al cargar perfil:", error)); // Siempre es buena práctica capturar errores
}

document.addEventListener("DOMContentLoaded", function(){

    cargarUsuario();
    
    document.getElementById("formFut").addEventListener("submit", function(e){
        e.preventDefault();

        let formData = new FormData(this);

        fetch('../../ajax/ajax_fut.php?accion=guardar', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {

            if(data.status){

                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'FUT enviado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });

                this.reset();
                cargarUsuario(); // Vuelve a llenar los datos del usuario tras limpiar el form

            } else {
                Swal.fire('Error', data.msg, 'error');
            }

        })
        .catch(error => {
            console.error("Error al enviar FUT:", error);
            Swal.fire('Error', 'Problema de conexión al enviar el formulario', 'error');
        });

    });

});
</script>
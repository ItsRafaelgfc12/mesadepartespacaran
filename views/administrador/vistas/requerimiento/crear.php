<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-clipboard-list text-primary"></i> Gestión de Requerimientos
</h1>

<div class="container-fluid">

    <!-- BOTONES -->
    <div class="text-center mb-4">
        <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#modalAlmacen">
            <i class="fas fa-box"></i> Requerimiento de Almacén
        </button>

        <button class="btn btn-success" data-toggle="modal" data-target="#modalEconomico">
            <i class="fas fa-money-bill-wave"></i> Requerimiento Económico
        </button>
    </div>

    <!-- LISTA -->
    <div class="card shadow">
        <div class="card-body">

            <h5><i class="fas fa-list"></i> Mis Requerimientos</h5>

            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Motivo</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>001</td>
                        <td><span class="badge badge-primary">Almacén</span></td>
                        <td>Compra equipos</td>
                        <td>20/03/2026</td>
                        <td>
                            <button class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>

</div>

<div class="modal fade" id="modalAlmacen" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST" action="guardar_almacen.php">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-box"></i> Requerimiento de Almacén
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Motivo</label>
                        <input type="text" name="motivo" class="form-control">
                    </div>

                    <table class="table table-bordered" id="tablaAlmacen">
                        <thead class="thead-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Descripción</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" class="btn btn-secondary" onclick="agregarFilaAlmacen()">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </button>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modalEconomico" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST" action="guardar_economico.php">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-money-bill-wave"></i> Requerimiento Económico
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Motivo</label>
                        <input type="text" name="motivo" class="form-control">
                    </div>

                    <table class="table table-bordered" id="tablaEconomico">
                        <thead class="thead-dark">
                            <tr>
                                <th>Concepto</th>
                                <th>Cantidad</th>
                                <th>Monto</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" class="btn btn-secondary mb-2" onclick="agregarFilaEconomico()">
                        <i class="fas fa-plus"></i> Agregar Concepto
                    </button>

                    <h5 class="text-right">
                        Total: S/ <span id="total">0.00</span>
                    </h5>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
/* ALMACÉN */
function agregarFilaAlmacen(){
    let fila = `
    <tr>
        <td><input type="text" name="producto[]" class="form-control"></td>
        <td><input type="number" name="cantidad[]" class="form-control"></td>
        <td><input type="text" name="descripcion[]" class="form-control"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>`;
    document.querySelector("#tablaAlmacen tbody").insertAdjacentHTML("beforeend", fila);
}

/* ECONÓMICO */
function agregarFilaEconomico(){
    let fila = `
    <tr>
        <td><input type="text" name="concepto[]" class="form-control"></td>
        <td><input type="number" name="cantidad[]" class="form-control" oninput="calcular(this)"></td>
        <td><input type="number" name="monto[]" class="form-control" oninput="calcular(this)"></td>
        <td><input type="text" class="form-control subtotal" readonly></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>`;
    document.querySelector("#tablaEconomico tbody").insertAdjacentHTML("beforeend", fila);
}

function calcular(input){
    let fila = input.closest("tr");
    let cantidad = fila.children[1].children[0].value || 0;
    let monto = fila.children[2].children[0].value || 0;

    let subtotal = cantidad * monto;
    fila.querySelector(".subtotal").value = subtotal.toFixed(2);

    calcularTotal();
}

function calcularTotal(){
    let total = 0;
    document.querySelectorAll(".subtotal").forEach(el => {
        total += parseFloat(el.value) || 0;
    });
    document.getElementById("total").innerText = total.toFixed(2);
}

function eliminarFila(btn){
    btn.closest("tr").remove();
    calcularTotal();
}
</script>
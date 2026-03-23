<h1 class="h3 mb-4 text-gray-800">Ver Expedientes</h1>

<div class="container">

    <!-- FILTROS -->
    <div class="card shadow-sm mb-3">
        <div class="card-body row g-2">
            <div class="col-md-3">
                <input type="text" id="buscadorExp" class="form-control" placeholder="Buscar expediente...">
            </div>
            <div class="col-md-3">
                <select id="filtroTipo" class="form-select">
                    <option value="">Tipo</option>
                    <option value="privado">Privado</option>
                    <option value="compartido">Compartido</option>
                    <option value="otros">De otros</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filtroEstado" class="form-select">
                    <option value="">Estado</option>
                    <option value="activo">Activo</option>
                    <option value="cerrado">Cerrado</option>
                </select>
            </div>
        </div>
    </div>

    <!-- TABLA DE EXPEDIENTES -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaExpedientes">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Asunto</th>
                            <th>Tipo</th>
                            <th>Responsable</th>
                            <th>Última actualización</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos cargados dinámicamente -->
                        <tr>
                            <td>001</td>
                            <td>EXP-2026-001</td>
                            <td>Informe Legal</td>
                            <td>Privado</td>
                            <td>Juan Pérez</td>
                            <td>20/03/2026</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalVerExp">
                                    <i class="bi bi-eye"></i> Ver
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerExp" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Detalles del Expediente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Código:</strong> EXP-2026-001</p>
                <p><strong>Asunto:</strong> Informe Legal</p>
                <p><strong>Tipo:</strong> Privado</p>
                <p><strong>Responsable:</strong> Juan Pérez</p>
                <p><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
            </div>
            <div class="col-md-6">
                <p><strong>Descripción:</strong></p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
            </div>
        </div>

        <hr>

        <h6>Participantes</h6>
        <ul>
            <li>Juan Pérez</li>
            <li>María López</li>
        </ul>

        <hr>

        <h6>Documentos Adjuntos</h6>
        <ul>
            <li><a href="#" target="_blank"><i class="bi bi-file-earmark-text"></i> Informe.pdf</a></li>
            <li><a href="#" target="_blank"><i class="bi bi-file-earmark-text"></i> Resumen.pdf</a></li>
        </ul>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalVerExp" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Detalles del Expediente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- Información principal del expediente -->
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Código:</strong> EXP-2026-001</p>
                <p><strong>Asunto:</strong> Informe Legal</p>
                <p><strong>Tipo:</strong> Privado</p>
                <p><strong>Responsable:</strong> Juan Pérez</p>
                <p><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
            </div>
            <div class="col-md-6">
                <p><strong>Descripción:</strong></p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
            </div>
        </div>

        <hr>

        <!-- Participantes -->
        <h6>Participantes</h6>
        <ul>
            <li>Juan Pérez</li>
            <li>María López</li>
        </ul>

        <hr>

        <!-- Historial de actualizaciones -->
        <h6>Historial de Actualizaciones</h6>
        <div class="accordion" id="historialExpediente">

          <!-- Ejemplo de actualización -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading1">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                20/03/2026 - Juan Pérez
              </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#historialExpediente">
              <div class="accordion-body">
                <p><strong>Notas:</strong> Documento inicial del expediente.</p>
                <ul>
                  <li><a href="#" target="_blank"><i class="bi bi-file-earmark-text"></i> Informe.pdf</a></li>
                  <li><a href="#" target="_blank"><i class="bi bi-file-earmark-text"></i> Resumen.pdf</a></li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Otra actualización -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading2">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                22/03/2026 - María López
              </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#historialExpediente">
              <div class="accordion-body">
                <p><strong>Notas:</strong> Se agregaron documentos complementarios.</p>
                <ul>
                  <li><a href="#" target="_blank"><i class="bi bi-file-earmark-text"></i> Anexo1.pdf</a></li>
                  <li><a href="#" target="_blank"><i class="bi bi-file-earmark-text"></i> Anexo2.pdf</a></li>
                </ul>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>
<script>
const buscadorExp = document.getElementById("buscadorExp");
const filtroTipo = document.getElementById("filtroTipo");
const filtroEstado = document.getElementById("filtroEstado");

function filtrarExpedientes() {
    const texto = buscadorExp.value.toLowerCase();
    const tipo = filtroTipo.value.toLowerCase();
    const estado = filtroEstado.value.toLowerCase();

    document.querySelectorAll("#tablaExpedientes tbody tr").forEach(tr => {
        const contenido = tr.innerText.toLowerCase();
        let mostrar = contenido.includes(texto) &&
                     (tipo === "" || contenido.includes(tipo)) &&
                     (estado === "" || contenido.includes(estado));
        tr.style.display = mostrar ? "" : "none";
    });
}

buscadorExp.addEventListener("keyup", filtrarExpedientes);
filtroTipo.addEventListener("change", filtrarExpedientes);
filtroEstado.addEventListener("change", filtrarExpedientes);
</script>
<?php
function programaNombre($id) {
    $programas = [
        1 => "Enfermería Técnica",
        2 => "Producción Agropecuaria",
    ];
    return $programas[$id] ?? "Sin programa";
}
?>
<h1 class="h3 mb-4 text-gray-800">Bienvenido administrador</h1>
<p>Contenido inicial...</p>
<div class="card shadow mb-4">
    <div class="card-body d-flex align-items-center">

        <div class="mr-4">
            <img class="rounded-circle border shadow-sm" width="100" height="100" style="object-fit: cover;"
                src="<?php 
                    $foto = $_SESSION["foto"] ?? '';
                    echo (!empty($foto)) ? "../../uploads/usuarios/$foto" : "../../img/undraw_profile_1.svg"; 
                ?>">
        </div>

        <div>
            <h4 class="mb-1 text-primary font-weight-bold">
                ¡Bienvenido, <?php echo $_SESSION["nombre"]; ?>!
            </h4>
            <p class="text-muted mb-0">
                <i class="fas fa-id-badge mr-1"></i> 
                <strong>Cargo:</strong> 
                <?php 
                    echo !empty($_SESSION['cargos']) ? implode(" / ", $_SESSION['cargos']) : 'Usuario del Sistema'; 
                ?>
            </p>
        </div>

    </div>
</div>
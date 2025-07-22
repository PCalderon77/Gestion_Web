<!-- ../vistas/no_autorizado.php -->

<?php 
require_once '../funciones/verificar_login.php'; // Verifica si el usuario está logueado
require_once '../funciones/config.php';
include '../includes/header.php';

?>

<main class="content">
    <div class="container text-center mt-5">
        <h1 class="text-danger">Acceso denegado</h1>
        <p>No tenés permiso para acceder a esta sección.</p>
        <a href="../vistas/dashboard.php" class="btn btn-primary mt-3">Volver al inicio</a>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
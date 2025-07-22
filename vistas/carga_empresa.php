<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/config.php';
require_once '../funciones/conexion.php';
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: ../vistas/no_autorizado.php');
    exit;
}
$conn = conectar();

// Traer listado de países
$sql_paises = "SELECT Id, Denominacion FROM paises ORDER BY Denominacion";
$resultado_paises = $conn->query($sql_paises);
?>

<?php include '../includes/header.php'; ?>

<main class="content">
    <div class="container-fluid p-0">

        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Cargar Nueva Empresa</h1>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <?php if (isset($_GET['error']) || isset($_GET['ok'])): ?>

                            <?php if (isset($_GET['ok']) && $_GET['ok'] == 1): ?>
                                <h4 class="text-success">
                                    <i class="align-middle" data-feather="check-square"></i> Registro cargado correctamente.
                                </h4>
                            <?php endif; ?>

                            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                                <h4 class="text-info">
                                    Los campos con <i class="align-middle me-2" data-feather="command"></i> son obligatorios.
                                </h4>
                            <?php endif; ?>

                            <?php if (isset($_GET['error']) && $_GET['error'] == 2): ?>
                                <h4 class="text-danger">
                                    <i class="align-middle me-2" data-feather="alert-circle"></i> No se pudo guardar la empresa.
                                </h4>
                            <?php endif; ?>


                        <?php endif; ?>

                    </div>

                    <form action="../funciones/procesar_carga_empresa.php" method="POST">
                        <div class="card">
                            <div class="card-body">

                                <div class="mb-3">
                                    <h5 class="card-title mb-0">Denominación <i class="align-middle me-2" data-feather="command"></i></h5>
                                    <input type="text" name="denominacion" class="form-control" placeholder="Ingrese el nombre">
                                </div>

                                <div class="mb-3">
                                    <h5 class="card-title mb-0">País <i class="align-middle me-2" data-feather="command"></i></h5>
                                    <select name="pais" class="form-control" >
                                        <option value="">Elige un pais</option>
                                        <?php while ($p = $resultado_paises->fetch_assoc()): ?>
                                            <option value="<?= $p['Id'] ?>"><?= htmlspecialchars($p['Denominacion']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                            </div>

                            <div class="card-body">
                                <h5 class="card-title mb-0">Observaciones</h5>
                                <textarea class="form-control" rows="2" placeholder="Comentarios generales..."></textarea>
                            </div>

                            <input type="submit" class="btn btn-primary" value="Registrar Datos" />
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
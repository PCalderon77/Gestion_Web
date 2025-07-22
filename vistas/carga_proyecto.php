<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/config.php';
require_once '../funciones/conexion.php';

$conn = conectar();

// Traer listado de empresas
$sql_empresas = "SELECT Id, Denominacion FROM empresas ORDER BY Denominacion";
$resultado_empresas = $conn->query($sql_empresas);


// Traer listado de lideres
$sql_lideres = "SELECT Id, apellido, nombre FROM usuarios WHERE idRol='2' ORDER BY apellido"; 
$resultado_lideres = $conn->query($sql_lideres);
?>


<?php include '../includes/header.php'; ?>

<main class="content">
    <div class="container-fluid p-0">

        <div class="mb-3">
            <h1 class="h3 mb-3"><strong>Proyectos</strong> Cargar nuevo. </h1>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <?php if (isset($_GET['error']) || isset($_GET['ok'])): ?>
                            <div class="card-header">
                                <?php if (isset($_GET['ok']) && $_GET['ok'] == 1): ?>
                                    <h4 class="text-success">
                                        <i class="align-middle" data-feather="check-square"></i> Registro cargado correctamente.
                                    </h4>
                                <?php endif; ?>

                                <?php if (isset($_GET['error']) && $_GET['error'] == 2): ?>
                                    <h4 class="text-danger">
                                        <i class="align-middle me-2" data-feather="alert-circle"></i> No se pudo guardar el proyecto.
                                    </h4>
                                <?php endif; ?>
                                <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                                    <h4 class="text-info">
                                        Los campos con <i class="align-middle me-2" data-feather="command"></i> son obligatorios.
                                    </h4>
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <form action="../funciones/procesar_carga_proyecto.php" method="POST">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-0">Denominación <i class="align-middle me-2" data-feather="command"></i></h5>
                                <input type="text" class="form-control" name="denominacion" placeholder="Ingresa el nombre del Proyecto" required>
                            </div>
    
                            <div class="card-body">
                                <h5 class="card-title mb-0">Empresa <i class="align-middle me-2" data-feather="command"></i></h5>
                                <select class="form-select mb-3" name="empresa" required>
                                    <option value="">Para quien trabajaremos...</option>
                                    <?php while ($p = $resultado_empresas->fetch_assoc()): ?>
                                        <option value="<?= $p['Id'] ?>"><?= htmlspecialchars($p['Denominacion']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
    
    
                            <div class="card-body">
                                <h5 class="card-title mb-0">Líder <i class="align-middle me-2" data-feather="command"></i></h5>
                                <select name="lider" class="form-select mb-3" required>
                                    <?php if ($_SESSION['rol'] === 'Lider'): ?>
                                        <!-- Solo el líder logueado puede seleccionarse a sí mismo -->
                                        <option value="<?= $_SESSION['id'] ?>" selected>
                                            <?= htmlspecialchars($_SESSION['nombre']) ?>
                                        </option>
                                    <?php else: ?>
                                        <option selected disabled>Selecciona una opción</option>
                                        <?php while ($p = $resultado_lideres->fetch_assoc()): ?>
                                            <option value="<?= $p['Id'] ?>"><?= htmlspecialchars($p['apellido']) ?> <?= htmlspecialchars($p['nombre']) ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

    
                            <div class="card-body">
                                <h5 class="card-title mb-0">Observaciones</h5>
                                <textarea class="form-control" name="observaciones" rows="2" placeholder="Observaciones del tema..."></textarea>
                            </div>
                            <div class="card-body">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="prioridad" value="1">
                                    <span class="form-check-label">
                                        Tildar si es solicitado con prioridad
                                    </span>
                                </label>
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
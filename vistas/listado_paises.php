<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/config.php';
require_once '../funciones/conexion.php';
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: ../vistas/no_autorizado.php');
    exit;
}
$conn = conectar();
$sql = "SELECT Id, Denominacion, CodigoImg FROM paises ORDER BY Denominacion";
$resultado = $conn->query($sql);
?>

<?php include '../includes/header.php'; ?>

<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Paises con que trabajamos.</strong> Listado general. </h1>
        <div class="row">
            <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h4 class="text-info">Visualizando <?= $resultado->num_rows ?> registros</h4>
                        <hr />
                    </div>

                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Denominación</th>
                                <th class="d-none d-md-table-cell">País</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : null; ?>
                            <?php $i = 1; while ($row = $resultado->fetch_assoc()): ?>
                                <?php if ($edit_id === (int)$row['Id']): ?>
                                    <!-- Fila en modo edición -->
                                    <form method="post" action="../funciones/guardar_pais.php">
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td>
                                                <input type="text" name="denominacion" class="form-control" value="<?= htmlspecialchars($row['Denominacion']) ?>" required>
                                            </td>
                                            <td>
                                                <img src="../img/countries/<?= $row['CodigoImg'] ?>.jpg" width="36" height="36" class="rounded-circle me-2">
                                            </td>
                                            <td>
                                                <input type="hidden" name="id" value="<?= $row['Id'] ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                                <a href="listado_paises.php" class="btn btn-secondary btn-sm">Cancelar</a>
                                            </td>
                                        </tr>
                                    </form>
                                <?php else: ?>
                                    <!-- Fila normal -->
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['Denominacion']) ?></td>
                                        <td>
                                            <img src="../img/countries/<?= $row['CodigoImg'] ?>.jpg" width="36" height="36" class="rounded-circle me-2" alt="<?= $row['CodigoImg'] ?>.jpg">
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="listado_paises.php?edit=<?= $row['Id'] ?>"><span data-feather="edit"></span> Editar</a>
                                            <a class="btn btn-danger btn-sm" href="../funciones/borrar_pais.php?id=<?= $row['Id'] ?>" onclick="return confirm('¿Estás seguro de borrar este país?')"><span data-feather="trash"></span> Borrar</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
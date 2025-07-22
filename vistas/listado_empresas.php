<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/config.php';
require_once '../funciones/conexion.php';

$conn = conectar();

// Consulta para obtener empresas con país y usuario que las cargó
$sql = "
    SELECT e.Id, e.Denominacion, e.FechaCarga, 
           p.CodigoImg, 
           u.nombre, u.apellido, u.Foto
    FROM empresas e
    JOIN paises p ON e.Id_Pais = p.Id
    JOIN usuarios u ON e.Id_Usuario = u.Id
    ORDER BY e.FechaCarga DESC
";

$resultado = $conn->query($sql);

?>

<?php include '../includes/header.php'; ?>

<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Empresas</strong> Listado general. </h1>
        <div class="row">
            <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h4 class="text-info">Visualizando <?= $resultado->num_rows ?> registros</h4>
                    </div>

                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Denominación</th>
                                <th>Fecha de carga</th>
                                <th>Cargada por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : null; ?>
                            <?php $i = 1; while ($row = $resultado->fetch_assoc()): ?>
                                <?php if ($edit_id === (int)$row['Id']): ?>
                                    <form method="post" action="../funciones/actualizar_empresa.php" >
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td>
                                                <img src="../img/countries/<?= $row['CodigoImg'] ?>.jpg" width="36" height="36" class="rounded-circle me-2" alt="<?= $row['CodigoImg'] ?>.jpg" title="<?= $row['CodigoImg'] ?>.jpg">
                                                <input type="text" name="denominacion" class="form-control" value="<?= htmlspecialchars($row['Denominacion']) ?>" required>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($row['FechaCarga'])) ?></td>
                                            <td>
                                                <img src="../img/avatars/<?= $row['Foto'] ?>" width="36" height="36" class="rounded-circle me-2" alt="<?= $row['Foto'] ?>">
                                                <?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="id" value="<?= $row['Id'] ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                                <a href="listado_empresas.php" class="btn btn-secondary btn-sm">Cancelar</a>
                                            </td>
                                        </tr>
                                    </form>
                                <?php else: ?>
                                    <!-- Fila normal -->
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td>
                                            <img src="../img/countries/<?= $row['CodigoImg'] ?>.jpg" width="36" height="36" class="rounded-circle me-2" alt="<?= $row['CodigoImg'] ?>.jpg" title="<?= $row['CodigoImg'] ?>.jpg">
                                            <?= htmlspecialchars($row['Denominacion']) ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($row['FechaCarga'])) ?></td>
                                        <td>
                                            <img src="../img/avatars/<?= $row['Foto'] ?>" width="36" height="36" class="rounded-circle me-2" alt="<?= $row['Foto'] ?>">
                                            <?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?>
                                        </td>
                                        <td>
                                            <?php if ($_SESSION['rol'] === 'Administrador'): ?>
                                                <a class="btn btn-primary btn-sm" href="listado_empresas.php?edit=<?= $row['Id'] ?>"><span data-feather="edit"></span> Editar</a>
                                                <a class="btn btn-danger btn-sm" href="../funciones/borrar_empresa.php?id=<?= $row['Id'] ?>" onclick="return confirm('¿Estás seguro de borrar esta empresa?')"><span data-feather="trash"></span> Borrar</a>
                                            <?php else: ?>
                                                <span class="text-muted"></span>
                                            <?php endif; ?>
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
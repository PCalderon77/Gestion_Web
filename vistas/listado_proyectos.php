<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/config.php';
require_once '../funciones/conexion.php';

$conn = conectar();

// Consulta para obtener los proyectos con empresa, país y líder
$sql = "
    SELECT pr.Id, pr.Denominacion, pr.FechaCarga, pr.Id_Estado, pr.Id_Empresa,
           em.Denominacion AS Empresa, pa.CodigoImg,
           us.nombre, us.apellido, us.Foto,
           es.Denominacion AS estado
    FROM proyectos pr
    JOIN empresas em ON pr.Id_Empresa = em.Id
    JOIN paises pa ON em.Id_Pais = pa.Id
    JOIN usuarios us ON pr.Id_Lider = us.Id
    JOIN estados es ON pr.Id_Estado = es.Id
    ORDER BY pr.FechaCarga DESC
";

$resultado = $conn->query($sql);

// Obtener todas las empresas
$empresas = $conn->query("SELECT Id, Denominacion FROM empresas ORDER BY Denominacion");

// Obtener todos los estados
$estados = $conn->query("SELECT Id, Denominacion FROM estados ORDER BY Denominacion");

// Convertir a arrays asociativos
$lista_empresas = [];
while ($e = $empresas->fetch_assoc()) {
    $lista_empresas[$e['Id']] = $e['Denominacion'];
}

$lista_estados = [];
while ($e = $estados->fetch_assoc()) {
    $lista_estados[$e['Id']] = $e['Denominacion'];
}
?>

<?php include '../includes/header.php'; ?>

<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Proyectos</strong> Listado general. </h1>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'cancelado'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Proyecto cancelado correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

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
                                <th class="d-none d-md-table-cell">Fecha Carga</th>
                                <th class="d-none d-md-table-cell">Empresa</th>
                                <th>Estado</th>
                                <th class="d-none d-md-table-cell">Líder</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : null; ?>
                            <?php
                            $i = 1;
                            while ($row = $resultado->fetch_assoc()):
                                // Determinar clase de color según estado
                                $estado = strtoupper($row['estado']);
                                $badge = match ($estado) {
                                    'TERMINADO' => 'success',
                                    'EN DESARROLLO' => 'warning',
                                    'ANÁLISIS INICIADO' => 'info',
                                    'CANCELADO' => 'danger',
                                    default => 'secondary'
                                };
                            ?>
                                <?php if ($edit_id === (int)$row['Id']): ?>
                                    <!-- Fila en modo edición -->
                                    <form method="post" action="../funciones/guardar_proyecto.php">
                                        <td><?= $i++ ?></td>
                                        <td>
                                            <input type="text" name="denominacion" class="form-control" value="<?= htmlspecialchars($row['Denominacion']) ?>" required>
                                        </td>
                                        <td class="d-none d-md-table-cell"><?= date('d/m/Y', strtotime($row['FechaCarga'])) ?></td>
                                        <td class="d-none d-md-table-cell">
                                            <select name="empresa" class="form-select" required>
                                                <?php foreach ($lista_empresas as $id => $nombre): ?>
                                                    <option value="<?= $id ?>" <?= $id == $row['Id_Empresa'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($nombre) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="estado" class="form-select" required>
                                                <?php foreach ($lista_estados as $id => $nombre): ?>
                                                    <?php if ($_SESSION['rol'] === 'Lider' && $id == 4) continue; ?>
                                                    <option value="<?= $id ?>" <?= $id == $row['Id_Estado'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($nombre) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <img src="../img/avatars/<?= $row['Foto'] ?>" width="36" height="36" class="rounded-circle me-2">
                                            <?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="id" value="<?= $row['Id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                            <a href="listado_proyectos.php" class="btn btn-secondary btn-sm">Cancelar</a>
                                        </td>
                                    </form>
                                <?php else: ?>
                                    <tr>
                                        <!-- Fila normal -->
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($row['Denominacion']) ?></td>
                                        <td class="d-none d-md-table-cell"><?= date('d/m/Y', strtotime($row['FechaCarga'])) ?></td>
                                        <td class="d-none d-md-table-cell">
                                            <img src="../img/countries/<?= $row['CodigoImg'] ?>.jpg" width="36" height="36" class="rounded-circle me-2">
                                            <?= htmlspecialchars($row['Empresa']) ?>
                                        </td>
                                        <td><span class="badge bg-<?= $badge ?>"><?= $estado ?></span></td>
                                        <td class="d-none d-md-table-cell">
                                            <img src="../img/avatars/<?= $row['Foto'] ?>" width="36" height="36" class="rounded-circle me-2">
                                            <?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?>
                                        </td>
                                        <td>
                                            <?php if (!($_SESSION['rol'] === 'Lider' && strtoupper($row['estado']) === 'CANCELADO')): ?>
                                                <a class="btn btn-primary btn-sm" href="listado_proyectos.php?edit=<?= $row['Id'] ?>">
                                                    <span data-feather="edit"></span> Editar
                                                </a>
                                            <?php endif; ?>

                                            
                                            <?php if ($_SESSION['rol'] === 'Administrador'): ?>
                                                <a class="btn btn-warning btn-sm" href="../funciones/cancelar_proyecto.php?id=<?= $row['Id'] ?>"onclick="return confirm('¿Estás seguro de cancelar este proyecto?')"><span data-feather="alert-triangle"></span> Cancelar</a>
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

<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/config.php';
require_once '../funciones/conexion.php';
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: ../vistas/no_autorizado.php');
    exit;
}

// Obtener usuarios desde la base de datos
$conn = conectar();
$sql = "SELECT u.Id, u.Apellido, u.Nombre, u.Email, u.Foto, r.Denominacion as Rol 
        FROM usuarios u
        JOIN roles r ON u.IdRol = r.Id";
$resultado = $conn->query($sql);
?>

<?php include '../includes/header.php'; ?>

<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Usuarios</strong> Listado general</h1>

        <div class="row">
            <div class="col-12">
                <div class="card flex-fill">

                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Apellido y Nombre</th>
                                <th>Rol</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0): ?>
                                <?php $n = 1; ?>
                                <?php while ($row = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $n++ ?></td>
                                        <td>
                                            <img src="<?= BASE_URL ?>img/avatars/<?= htmlspecialchars($row['Foto']) ?>" width="36" height="36" class="rounded-circle me-2" alt="<?= htmlspecialchars($row['Foto']) ?>">
                                            <?= htmlspecialchars($row['Apellido'] . ' ' . $row['Nombre']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['Rol']) ?></td>
                                        <td><?= htmlspecialchars($row['Email']) ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="editar_usuario.php?id=<?= $row['Id'] ?>">
                                                <span data-feather="edit"></span> Editar
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="borrar_usuario.php?id=<?= $row['Id'] ?>" onclick="return confirm('¿Estás seguro que deseas borrar este usuario?');">
                                                <span data-feather="trash"></span> Borrar
                                            </a>
                                                                 
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">No hay usuarios cargados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
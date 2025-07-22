<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/conexion.php';
require_once '../funciones/config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: ../vistas/no_autorizado.php');
    exit;
}

$conn = conectar(); 

// --- Lógica para mostrar el formulario con los datos actuales ---
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario = $_GET['id'];

    $sql = "SELECT u.Id, u.Nombre, u.Apellido, u.Email,u.Foto, r.Denominacion as Rol FROM usuarios u
        JOIN roles r ON u.IdRol = r.ID
        WHERE u.Id = ?";
    
    
    $stmt = $conn->prepare($sql);

    

    if ($stmt === false) {
    
        die('Error al preparar la consulta SQL: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Traer listado de lideres
    $sql_roles = "SELECT Id, Denominacion FROM roles ORDER BY Denominacion"; 
    $resultado_roles = $conn->query($sql_roles);

    if ($resultado->num_rows == 1) {
        $usuario_a_editar = $resultado->fetch_assoc();
        ?>
        <?php include '../includes/header.php'; ?>
        
        <main class="content">
            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Editar Usuario</h1>
            </div>
            
            
            <form action="editar_usuario.php" method="POST">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="id" value="<?= $usuario_a_editar['Id'] ?>">
                        <h5 class="card-title mb-0">Nombre: <i class="align-middle me-2" data-feather="command"></i></h5>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario_a_editar['Nombre']) ?>" required><br>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-0">Apellido: <i class="align-middle me-2" data-feather="command"></i></h5>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="<?= htmlspecialchars($usuario_a_editar['Apellido']) ?>" required><br>   
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-0">Email: <i class="align-middle me-2" data-feather="command"></i></h5>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario_a_editar['Email']) ?>" required><br>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title mb-0">Rol: <i class="align-middle me-2" data-feather="command"></i></h5>
                        <select class="form-select mb-3" name="rol"> <option value="">Selecciona una opción</option> <?php
                            if ($resultado_roles->num_rows > 0) {
                                $resultado_roles->data_seek(0);
                            }
                            while ($p = $resultado_roles->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($p['Denominacion']) ?>"
                                    <?php if (isset($usuario_a_editar['Rol']) && $usuario_a_editar['Rol'] == $p['Denominacion']) echo 'selected'; ?>>
                                    <?= htmlspecialchars($p['Denominacion']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Guardar Cambios" />
                </div>
            </form>
            
            <a class="btn btn-primary" href="../vistas/listado_usuarios.php"><span data-feather="return"></span> Volver a la lista</a>
        </main>
        <?php include '../includes/footer.php'; ?>
        <?php
    } else {
        echo "Usuario no encontrado.";
        header('Location: ../vistas/lista_usuarios.php?mensaje=usuario_no_encontrado');
        exit;
    }
    $stmt->close();
}

// --- Lógica para procesar el envío del formulario (actualizar datos) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_usuario = $_POST['id'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $rol = $_POST['rol']; 

    $sql_get_rol_id = "SELECT ID FROM roles WHERE Denominacion = ?";
    $stmt_rol_id = $conn->prepare($sql_get_rol_id);
    if ($stmt_rol_id === false) {
        die('Error al preparar la consulta para obtener IdRol: ' . $conn->error);
    }
    $stmt_rol_id->bind_param("s", $rol);
    $stmt_rol_id->execute();
    $resultado_rol_id = $stmt_rol_id->get_result();
    $row_rol_id = $resultado_rol_id->fetch_assoc();
    $id_rol_para_actualizar = $row_rol_id['ID'];
    $stmt_rol_id->close();


    $sql_update = "UPDATE usuarios SET Nombre = ?, Apellido = ?, Email = ?, IdRol = ? WHERE Id = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update === false) {
        die('Error al preparar la consulta SQL de actualización: ' . $conn->error);
    }

    $stmt_update->bind_param("sssis", $nombre, $apellido, $email, $id_rol_para_actualizar, $id_usuario);
   

    if ($stmt_update->execute()) {
        header('Location: ../vistas/listado_usuarios.php?mensaje=edicion_exitosa');
        exit;
    } else {
        header('Location: ../vistas/listado_usuarios.php?mensaje=error_edicion');
        exit;
    }
    $stmt_update->close();
}

$conn->close();
?>
<?php
session_start();
require_once 'conexion.php';

if (!isset($_POST['usuario']) || !isset($_POST['clave'])) {
    header('Location: ../vistas/login.php?error=1');
    exit;
}

$usuario = trim($_POST['usuario']);
$clave = trim($_POST['clave']);

// Conectar a la base de datos
$conn = conectar();

$sql = "SELECT u.Id, u.Nombre, u.Apellido, u.Email, u.Foto, u.Password as password_hash_db, r.Denominacion as Rol
        FROM usuarios u
        JOIN roles r ON u.IdRol = r.Id
        WHERE u.Email = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 1) {
    $user = $resultado->fetch_assoc();

    
    if (password_verify($clave, $user['password_hash_db'])) {
        // Verificar si tiene permiso para ingresar al panel
        if ($user['Rol'] == 'Analista Funcional' || $user['Rol'] == 'Programador/a') {
            header('Location: ../vistas/login.php?error=2'); // Error para roles no permitidos
            exit;
        }

        // Si la contraseña es correcta y el rol es permitido, entonces guarda datos en sesión
        $_SESSION['id'] = $user['Id'];
        $_SESSION['usuario'] = $user['Email'];
        $_SESSION['nombre'] = $user['Nombre'] . ' ' . $user['Apellido'];
        $_SESSION['rol'] = $user['Rol'];
        $_SESSION['foto'] = $user['Foto'];
        

        // Redirigir al panel
        header('Location: ../vistas/dashboard.php');
        exit;
    } else {
        // Si password_verify falla, la contraseña es incorrecta
        header('Location: ../vistas/login.php?error=1'); // Error de credenciales inválidas
        exit;
    }
} else {
    // Si no se encontró el usuario (num_rows != 1)
    header('Location: ../vistas/login.php?error=1'); // Error de credenciales inválidas
    exit;
}
?>
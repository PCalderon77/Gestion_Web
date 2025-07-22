<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: ../vistas/no_autorizado.php');
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Conectar a la base de datos
    $conn = conectar(); // Asumiendo que tu función conectar() está en conexion.php

    // Preparar la consulta SQL para evitar inyecciones SQL
    $sql = "DELETE FROM usuarios WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario); // "i" indica que es un entero

    if ($stmt->execute()) {
        // Redirigir de vuelta a la página principal con un mensaje de éxito
        header('Location: ../vistas/listado_usuarios.php?mensaje=borrado_exito');
        exit;
    } else {
        // Redirigir con un mensaje de error si la eliminación falla
        header('Location: ../vistas/listado_usuarios.php?mensaje=error_borrar');
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    // Si no se proporcionó un ID, redirigir con un error
    header('Location: ../vistas/lista_usuarios.php?mensaje=id_invalido');
    exit;
}
?>

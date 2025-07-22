<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: ../vistas/no_autorizado.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn = conectar();

    // Eliminar el país con ese ID
    $stmt = $conn->prepare("DELETE FROM empresas WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: ../vistas/listado_empresas.php');
exit;

<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: ../vistas/no_autorizado.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $denominacion = trim($_POST['denominacion']);

    if ($id && $denominacion !== '') {
        $conn = conectar();
        $stmt = $conn->prepare("UPDATE paises SET Denominacion = ? WHERE Id = ?");
        $stmt->bind_param("si", $denominacion, $id);
        $stmt->execute();
    }
}

header('Location: ../vistas/listado_paises.php');
exit;

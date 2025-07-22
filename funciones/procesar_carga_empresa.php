<?php
require_once 'verificar_login.php';
require_once 'config.php';
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $denominacion = trim($_POST['denominacion']);
    $id_pais = intval($_POST['pais']);
    $id_usuario = $_SESSION['id']; 

    if (empty($denominacion) || $id_pais <= 0) {
        header('Location: ../vistas/carga_empresa.php?error=1');
        exit;
    }

    $conn = conectar();
    $stmt = $conn->prepare("INSERT INTO empresas (Denominacion, Id_Pais, FechaCarga, Id_Usuario) VALUES (?, ?, NOW(), ?)");

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("sii", $denominacion, $id_pais, $id_usuario);

    if ($stmt->execute()) {
        header('Location: ../vistas/carga_empresa.php?ok=1');
        exit;
    } else {
        header('Location: ../vistas/carga_empresa.php?error=2');
        exit;
    }

} else {
    header('Location: ../vistas/carga_empresa.php');
    exit;
}

<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/conexion.php';
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: ../vistas/no_autorizado.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // ID del estado "Cancelado" en la tabla 'estados' (ajustalo según tu base de datos)
    $estado_cancelado_id = 4;

    if ($id > 0) {
        $conn = conectar();
        $stmt = $conn->prepare("UPDATE proyectos SET Id_Estado = ? WHERE Id = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $estado_cancelado_id, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            error_log("Error al preparar la consulta: " . $conn->error);
        }
    }
}

header('Location: ../vistas/listado_proyectos.php?msg=cancelado');
exit;

<?php
require_once '../funciones/verificar_login.php';
require_once '../funciones/conexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $denominacion = trim($_POST['denominacion']);
    $id_estado = isset($_POST['estado']) ? (int)$_POST['estado'] : 0;
    $id_empresa = isset($_POST['empresa']) ? (int)$_POST['empresa'] : 0;

    if ($id > 0 && $denominacion !== '' && $id_estado > 0 && $id_empresa > 0) {
        $conn = conectar();
        
        $stmt = $conn->prepare("UPDATE proyectos SET Denominacion = ?, Id_Estado = ?, Id_Empresa = ? WHERE Id = ?");
        if ($stmt) {
            $stmt->bind_param("siii", $denominacion, $id_estado, $id_empresa, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Log de error en caso de fallo en la preparación
            error_log("Error al preparar la consulta: " . $conn->error);
        }
    }
}

header('Location: ../vistas/listado_proyectos.php');
exit;

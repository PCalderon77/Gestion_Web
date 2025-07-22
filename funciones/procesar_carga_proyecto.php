<?php
require_once 'verificar_login.php';
require_once 'config.php';
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $denominacion = trim($_POST['denominacion']);
    $id_empresa = intval($_POST['empresa']);
    $id_lider = ($_SESSION['rol'] === 'Lider') ? $_SESSION['id'] : intval($_POST['lider']);
    $descripcion = trim($_POST['observaciones']);
    $prioridad = isset($_POST['prioridad']) ? 1 : 0;
    $id_estado = 1; // Por defecto (Análisis iniciado)
    $fecha_carga = date('Y-m-d');

    if (empty($denominacion) || $id_empresa <= 0 || $id_lider <= 0) {
        header('Location: ../vistas/carga_proyecto.php?error=1');
        exit;
    }

    // Obtener Id_Pais desde empresa
    $conn = conectar();
    $stmt_empresa = $conn->prepare("SELECT Id_Pais FROM empresas WHERE Id = ?");
    $stmt_empresa->bind_param("i", $id_empresa);
    $stmt_empresa->execute();
    $stmt_empresa->bind_result($id_pais);
    $stmt_empresa->fetch();
    $stmt_empresa->close();

    

    // Insertar el proyecto
    $stmt = $conn->prepare("
        INSERT INTO proyectos (
            Denominacion, Id_Lider, Id_Empresa,
            Id_Pais, Id_Estado, Descripcion, FechaCarga, prioridad
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "siiiissi",
        $denominacion,
        $id_lider,
        $id_empresa,
        $id_pais,
        $id_estado,
        $descripcion,
        $fecha_carga,
        $prioridad
    );


    if ($stmt->execute()) {
        header('Location: ../vistas/listado_proyectos.php?ok=1');
        exit;
    } else {
        error_log("Error SQL: " . $stmt->error);
        header('Location: ../vistas/carga_proyecto.php?error=2');
        exit;
    }

} else {
    header('Location: ../vistas/carga_proyecto.php');
    exit;
}

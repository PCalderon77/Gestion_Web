<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../vistas/login.php?error=3');
    exit;
}

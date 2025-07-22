<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: vistas/dashboard.php');
} else {
    header('Location: vistas/login.php');
}
exit;

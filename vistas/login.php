<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit;
}

// Mensajes dinámicos
$mensaje = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] == '1') $mensaje = "Datos incorrectos, intenta de nuevo.";
    if ($_GET['error'] == '2') $mensaje = "No tienes permisos asignados para ingresar al panel.";
    if ($_GET['error'] == '3') $mensaje = "Debes iniciar sesión para acceder al panel.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Consultora</title>
    <link href="../css/app.css" rel="stylesheet">
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <div class="text-center mt-4">
                                        <img src="../img/avatars/login.png" width="150" height="150">
                                        <h1 class="h2">Ingresa tus datos</h1>
                                    </div>

                                    <?php if ($mensaje != "") : ?>
                                        <div class="alert alert-danger text-center" role="alert">
                                            <h4 class="text-danger"><?php echo $mensaje; ?></h4>
                                            
                                        </div>
                                    <?php endif; ?>

                                    <form action="../funciones/login.php" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input name="usuario" type="text" class="form-control form-control-lg" placeholder="Ingresa tu usuario" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input name="clave" type="password" class="form-control form-control-lg" placeholder="Ingresa tu contraseña" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <input class="btn btn-lg btn-primary" type="submit" value="Ingresar">
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../js/app.js"></script>

</body>

</html>

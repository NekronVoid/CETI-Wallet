<?php
session_start();

if(isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CETI Wallet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-container">

    <div class="card">
        <h1>CETI Wallet</h1>
        <p>Administración inteligente de gastos personales</p>

        <br>

        <a href="auth/login.php">
            <button>Iniciar Sesión</button>
        </a>

        <br><br>

        <a href="auth/register.php">
            <button>Registrarse</button>
        </a>
    </div>

</div>

</body>
</html>
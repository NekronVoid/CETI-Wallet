<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = trim($_POST["correo"]);
    $password = $_POST["password"];

    $sql = $pdo->prepare(
        "SELECT * FROM usuarios WHERE correo = ?"
    );

    $sql->execute([$correo]);

    $usuario = $sql->fetch();

    if ($usuario &&
        password_verify(
            $password,
            $usuario["password"]
        )
    ) {

        $_SESSION["usuario"] = $usuario["id"];
        $_SESSION["nombre"] = $usuario["nombre"];

        header("Location: ../dashboard.php");
        exit;

    } else {

        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login | CETI Wallet</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="auth-container">

    <div class="card">

        <h2>Iniciar Sesión</h2>

        <br>

        <?php if($error): ?>
            <p class="gasto"><?= $error ?></p>
            <br>
        <?php endif; ?>

        <form method="POST">

            <input
                type="email"
                name="correo"
                placeholder="Correo"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Contraseña"
                required
            >

            <button type="submit">
                Entrar
            </button>

        </form>

        <br>

        <a href="register.php">
            Crear cuenta
        </a>

    </div>

</div>

</body>
</html>
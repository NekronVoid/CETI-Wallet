<?php

session_start();
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $password = $_POST["password"];

    if (!empty($nombre) && !empty($correo) && !empty($password)) {

        $verificar = $pdo->prepare(
            "SELECT id FROM usuarios WHERE correo = ?"
        );

        $verificar->execute([$correo]);

        if ($verificar->rowCount() > 0) {

            $error = "El correo ya está registrado";

        } else {

            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = $pdo->prepare(
                "INSERT INTO usuarios(nombre, correo, password)
                VALUES(?,?,?)"
            );

            $sql->execute([
                $nombre,
                $correo,
                $hash
            ]);

            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro | CETI Wallet</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="auth-container">

    <div class="card">

        <h2>Crear Cuenta</h2>

        <br>

        <?php if($error): ?>
            <p class="gasto"><?= $error ?></p>
            <br>
        <?php endif; ?>

        <form method="POST">

            <input
                type="text"
                name="nombre"
                placeholder="Nombre completo"
                required
            >

            <input
                type="email"
                name="correo"
                placeholder="Correo electrónico"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Contraseña"
                required
            >

            <button type="submit">
                Registrarse
            </button>

        </form>

        <br>

        <a href="login.php">
            Ya tengo cuenta
        </a>

    </div>

</div>

</body>
</html>
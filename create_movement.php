
<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: auth/login.php");
    exit;
}

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tipo = $_POST["tipo"];
    $concepto = trim($_POST["concepto"]);
    $categoria = $_POST["categoria"];
    $monto = $_POST["monto"];
    $fecha = $_POST["fecha"];

    $sql = $pdo->prepare("
        INSERT INTO movimientos
        (
            usuario_id,
            tipo,
            concepto,
            categoria,
            monto,
            fecha
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $sql->execute([
        $_SESSION["usuario"],
        $tipo,
        $concepto,
        $categoria,
        $monto,
        $fecha
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nuevo Movimiento | CETI Wallet</title>

<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <br>

    <div class="card">

        <h2>Nuevo Movimiento</h2>

        <br>

        <form method="POST">

            <select name="tipo" required>

                <option value="">
                    Selecciona un tipo
                </option>

                <option value="ingreso">
                    Ingreso
                </option>

                <option value="gasto">
                    Gasto
                </option>

            </select>

            <input
                type="text"
                name="concepto"
                placeholder="Concepto"
                required
            >

            <select name="categoria" required>

                <option value="">
                    Selecciona una categoría
                </option>

                <option value="Comida">
                    Comida
                </option>

                <option value="Transporte">
                    Transporte
                </option>

                <option value="Educación">
                    Educación
                </option>

                <option value="Entretenimiento">
                    Entretenimiento
                </option>

                <option value="Salud">
                    Salud
                </option>

                <option value="Servicios">
                    Servicios
                </option>

                <option value="Otros">
                    Otros
                </option>

            </select>

            <input
                type="number"
                step="0.01"
                min="0"
                name="monto"
                placeholder="Monto"
                required
            >

            <input
                type="date"
                name="fecha"
                required
            >

            <button type="submit">
                Guardar Movimiento
            </button>

        </form>

        <br>

        <a href="dashboard.php">
            Volver al Dashboard
        </a>

    </div>

</div>

</body>
</html>


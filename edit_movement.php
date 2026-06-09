<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: auth/login.php");
    exit;
}

require_once "config/database.php";

$id = $_GET["id"] ?? 0;

$sql = $pdo->prepare("
    SELECT *
    FROM movimientos
    WHERE id = ?
    AND usuario_id = ?
");

$sql->execute([
    $id,
    $_SESSION["usuario"]
]);

$movimiento = $sql->fetch();

if (!$movimiento) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tipo = $_POST["tipo"];
    $concepto = trim($_POST["concepto"]);
    $categoria = trim($_POST["categoria"]);
    $monto = $_POST["monto"];
    $fecha = $_POST["fecha"];

    $update = $pdo->prepare("
        UPDATE movimientos
        SET
            tipo = ?,
            concepto = ?,
            categoria = ?,
            monto = ?,
            fecha = ?
        WHERE id = ?
        AND usuario_id = ?
    ");

    $update->execute([
        $tipo,
        $concepto,
        $categoria,
        $monto,
        $fecha,
        $id,
        $_SESSION["usuario"]
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Movimiento | CETI Wallet</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <br>

    <div class="card">

        <h2>Editar Movimiento</h2>

        <br>

        <form method="POST">

            <select name="tipo" required>

                <option value="ingreso"
                    <?= $movimiento["tipo"] === "ingreso" ? "selected" : "" ?>>
                    Ingreso
                </option>

                <option value="gasto"
                    <?= $movimiento["tipo"] === "gasto" ? "selected" : "" ?>>
                    Gasto
                </option>

            </select>

            <input
                type="text"
                name="concepto"
                value="<?= htmlspecialchars($movimiento["concepto"]) ?>"
                required
            >

            <input
                type="text"
                name="categoria"
                value="<?= htmlspecialchars($movimiento["categoria"]) ?>"
                required
            >

            <input
                type="number"
                step="0.01"
                min="0"
                name="monto"
                value="<?= $movimiento["monto"] ?>"
                required
            >

            <input
                type="date"
                name="fecha"
                value="<?= $movimiento["fecha"] ?>"
                required
            >

            <button type="submit">
                Actualizar
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
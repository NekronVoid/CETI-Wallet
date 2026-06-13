<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: auth/login.php");
    exit;
}

require_once "config/database.php";

$usuario_id = $_SESSION["usuario"];

/*
|--------------------------------------------------------------------------
| RESUMEN GENERAL
|--------------------------------------------------------------------------
*/

$ingresos = $pdo->prepare("
SELECT COALESCE(SUM(monto),0)
FROM movimientos
WHERE usuario_id = ?
AND tipo = 'ingreso'
");

$ingresos->execute([$usuario_id]);
$totalIngresos = $ingresos->fetchColumn();

$gastos = $pdo->prepare("
SELECT COALESCE(SUM(monto),0)
FROM movimientos
WHERE usuario_id = ?
AND tipo = 'gasto'
");

$gastos->execute([$usuario_id]);
$totalGastos = $gastos->fetchColumn();

$balance = $totalIngresos - $totalGastos;

$totalMovimientos = $pdo->prepare("
SELECT COUNT(*)
FROM movimientos
WHERE usuario_id = ?
");

$totalMovimientos->execute([$usuario_id]);
$cantidadMovimientos = $totalMovimientos->fetchColumn();

/*
|--------------------------------------------------------------------------
| CATEGORIA CON MAYOR GASTO
|--------------------------------------------------------------------------
*/

$mayorGasto = $pdo->prepare("
SELECT
    categoria,
    SUM(monto) total
FROM movimientos
WHERE usuario_id = ?
AND tipo = 'gasto'
GROUP BY categoria
ORDER BY total DESC
LIMIT 1
");

$mayorGasto->execute([$usuario_id]);

$categoriaTop = $mayorGasto->fetch();

/*
|--------------------------------------------------------------------------
| GASTOS POR CATEGORIA
|--------------------------------------------------------------------------
*/

$categorias = $pdo->prepare("
SELECT
    categoria,
    SUM(monto) total
FROM movimientos
WHERE usuario_id = ?
AND tipo = 'gasto'
GROUP BY categoria
ORDER BY total DESC
");

$categorias->execute([$usuario_id]);

/*
|--------------------------------------------------------------------------
| ULTIMOS MOVIMIENTOS
|--------------------------------------------------------------------------
*/

$ultimos = $pdo->prepare("
SELECT *
FROM movimientos
WHERE usuario_id = ?
ORDER BY fecha DESC, id DESC
LIMIT 10
");

$ultimos->execute([$usuario_id]);

?>

<!DOCTYPE html>

<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reportes | CETI Wallet</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<div class="layout">


<?php include "includes/sidebar.php"; ?>

<main class="main-content">

    <h1>Reportes Financieros</h1>

    <br>

    <div class="dashboard-grid">

        <div class="summary-card">

            <h3>Ingresos</h3>

            <h2 class="ingreso">
                $<?= number_format($totalIngresos, 2) ?>
            </h2>

        </div>

        <div class="summary-card">

            <h3>Gastos</h3>

            <h2 class="gasto">
                $<?= number_format($totalGastos, 2) ?>
            </h2>

        </div>

        <div class="summary-card">

            <h3>Balance</h3>

            <h2>
                $<?= number_format($balance, 2) ?>
            </h2>

        </div>

    </div>

    <br>

    <div class="card">

        <h2>Información General</h2>

        <br>

        <p>
            Total de movimientos:
            <strong>
                <?= $cantidadMovimientos ?>
            </strong>
        </p>

        <br>

        <?php if($categoriaTop): ?>

            <p>
                Categoría con mayor gasto:
                <strong>
                    <?= htmlspecialchars(
                        $categoriaTop["categoria"]
                    ) ?>
                </strong>

                ($<?= number_format(
                    $categoriaTop["total"],
                    2
                ) ?>)
            </p>

        <?php endif; ?>

    </div>

    <br>

    <div class="card">

        <h2>Gastos por Categoría</h2>

        <br>

        <table class="table">

            <thead>

                <tr>

                    <th>Categoría</th>
                    <th>Total</th>

                </tr>

            </thead>

            <tbody>

            <?php while($row = $categorias->fetch()): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars(
                            $row["categoria"]
                        ) ?>
                    </td>

                    <td class="gasto">

                        $<?= number_format(
                            $row["total"],
                            2
                        ) ?>

                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

    <br>

    <div class="card">

        <h2>Últimos 10 Movimientos</h2>

        <br>

        <table class="table">

            <thead>

                <tr>

                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th>Categoría</th>
                    <th>Monto</th>
                    <th>Fecha</th>

                </tr>

            </thead>

            <tbody>

            <?php while($mov = $ultimos->fetch()): ?>

                <tr>

                    <td>
                        <?= ucfirst(
                            $mov["tipo"]
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $mov["concepto"]
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $mov["categoria"]
                        ) ?>
                    </td>

                    <td>

                        <span class="<?= $mov["tipo"] ?>">

                            $<?= number_format(
                                $mov["monto"],
                                2
                            ) ?>

                        </span>

                    </td>

                    <td>
                        <?= $mov["fecha"] ?>
                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</main>


</div>

<script src="assets/js/app.js"></script>

</body>
</html>

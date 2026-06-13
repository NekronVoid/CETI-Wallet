<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: auth/login.php");
    exit;
}

require_once "config/database.php";

$usuario_id = $_SESSION["usuario"];

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

$movimientos = $pdo->prepare("
    SELECT *
    FROM movimientos
    WHERE usuario_id = ?
    ORDER BY fecha DESC, id DESC
");
$movimientos->execute([$usuario_id]);

$grafica = $pdo->prepare("
    SELECT tipo, SUM(monto) total
    FROM movimientos
    WHERE usuario_id = ?
    GROUP BY tipo
");

$grafica->execute([$usuario_id]);

$ingresoGrafica = 0;
$gastoGrafica = 0;

while ($dato = $grafica->fetch()) {

    if ($dato["tipo"] === "ingreso") {
        $ingresoGrafica = $dato["total"];
    }

    if ($dato["tipo"] === "gasto") {
        $gastoGrafica = $dato["total"];
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | CETI Wallet</title>

<link rel="stylesheet" href="assets/css/style.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="layout">

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">

        <h1>Panel Financiero</h1>

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

            <h2>Distribución Financiera</h2>

            <div class="chart-container">

                <canvas id="financeChart"></canvas>

            </div>

        </div>

        <br>

        <div class="card">

            <h2>Historial de Movimientos</h2>

            <br>

            <table class="table">

                <thead>

                    <tr>
                        <th>Tipo</th>
                        <th>Concepto</th>
                        <th>Categoría</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                <?php while ($row = $movimientos->fetch()): ?>

                    <tr>

                        <td>
                            <?= ucfirst($row["tipo"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row["concepto"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row["categoria"]) ?>
                        </td>

                        <td>

                            <span class="<?= $row["tipo"] ?>">

                                $<?= number_format(
                                    $row["monto"],
                                    2
                                ) ?>

                            </span>

                        </td>

                        <td>
                            <?= $row["fecha"] ?>
                        </td>

                        <td>

                            <a href="edit_movement.php?id=<?= $row["id"] ?>">
                                Editar
                            </a>

                            |

                            <a
                                href="delete_movement.php?id=<?= $row["id"] ?>"
                                onclick="return confirm('¿Eliminar movimiento?')"
                            >
                                Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </main>

</div>

<script>

const ctx = document.getElementById('financeChart');

new Chart(ctx, {

    type: 'doughnut',

    data: {

        labels: [
            'Ingresos',
            'Gastos'
        ],

        datasets: [{

            data: [
                <?= $ingresoGrafica ?>,
                <?= $gastoGrafica ?>
            ],

            backgroundColor: [
                '#a6e3a1',
                '#f38ba8'
            ],

            borderWidth: 2

        }]
    },

    options: {

        responsive: true,

        maintainAspectRatio: true,

        plugins: {

            legend: {

                labels: {

                    color: '#cdd6f4'

                }
            }
        }
    }
});

</script>

    <script src="assets/js/app.js"></script>
</body>
</html>


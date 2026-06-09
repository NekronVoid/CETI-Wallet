<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: auth/login.php");
    exit;
}

require_once "config/database.php";

$id = $_GET["id"] ?? 0;

$sql = $pdo->prepare("
    DELETE FROM movimientos
    WHERE id = ?
    AND usuario_id = ?
");

$sql->execute([
    $id,
    $_SESSION["usuario"]
]);

header("Location: dashboard.php");
exit;
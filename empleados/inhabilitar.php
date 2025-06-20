<?php
session_start();
require '../conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("UPDATE empleados SET activo = 0 WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: listar.php");
exit();

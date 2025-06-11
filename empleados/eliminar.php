<?php
require '../conexion.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: listar.php");
exit();

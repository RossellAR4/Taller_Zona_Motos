<?php
session_start();
require '../conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$vales = $pdo->query("
    SELECT v.*, e.nombre 
    FROM vales v
    JOIN empleados e ON v.empleado_id = e.id
    ORDER BY v.fecha_hora DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Vales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4 text-center">📋 Vales Registrados</h2>
    <a href="index.php" class="btn btn-secondary mb-3">⬅ Volver</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Empleado</th>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vales as $vale): ?>
                <tr>
                    <td><?= htmlspecialchars($vale['nombre']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($vale['fecha_hora'])) ?></td>
                    <td>$<?= number_format($vale['monto'], 2) ?></td>
                    <td><?= htmlspecialchars($vale['motivo']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

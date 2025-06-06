<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal - Taller Zona Motos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">🚀 Panel Principal - Taller Zona Motos</h2>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <a href="empleados/registrar.php" class="btn btn-outline-success w-100 py-3">👤 Registrar Empleado</a>
        </div>
        <div class="col-md-4">
            <a href="trabajos/registrar.php" class="btn btn-outline-primary w-100 py-3">🛠 Registrar Trabajo</a>
        </div>
        <div class="col-md-4">
            <a href="vales/registrar.php" class="btn btn-outline-warning w-100 py-3">💵 Registrar Vale</a>
        </div>
        <div class="col-md-4">
            <a href="trabajos/vista_diaria.php" class="btn btn-outline-dark w-100 py-3">📅 Vista Diaria</a>
        </div>
        <div class="col-md-4">
            <a href="trabajos/vista_semanal.php" class="btn btn-outline-info w-100 py-3">📊 Vista Semanal</a>
        </div>
        <div class="col-md-4">
            <a href="auth/logout.php" class="btn btn-danger w-100 py-3">🚪 Cerrar sesión</a>
        </div>
    </div>
</div>
</body>
</html>

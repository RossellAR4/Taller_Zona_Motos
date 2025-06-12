<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vales - Taller Zona Motos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">💵 Gestión de Vales</h2>
    <div class="row g-4 justify-content-center">
        <div class="col-md-5">
            <a href="registrar.php" class="btn btn-success w-100 py-3">➕ Registrar Vale</a>
        </div>
        <div class="col-md-5">
            <a href="listar.php" class="btn btn-primary w-100 py-3">📋 Listar Vales</a>
        </div>
    </div>
    <a href="../dashboard.php" class="btn btn-secondary mt-3">🔙 Volver al Menú Principal</a>

</div>
</body>
</html>

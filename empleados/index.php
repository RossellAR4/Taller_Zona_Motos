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
    <title>Gestionar Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4 text-center">👥 Gestión de Empleados</h3>

    <div class="row justify-content-center g-3">
        <div class="col-md-4">
            <a href="registrar.php" class="btn btn-outline-primary w-100 py-3">➕ Registrar Empleado</a>
        </div>
        <div class="col-md-4">
            <a href="listar.php" class="btn btn-outline-dark w-100 py-3">📋 Listar Empleados</a>
        </div>
        <div class="col-md-4">
            <a href="../dashboard.php" class="btn btn-secondary w-100 py-3">🔙 Volver al Menú</a>
        </div>
    </div>
</div>
</body>
</html>

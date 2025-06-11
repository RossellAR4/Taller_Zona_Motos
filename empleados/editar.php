<?php
require '../conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) exit("ID no proporcionado.");

$stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = ?");
$stmt->execute([$id]);
$empleado = $stmt->fetch();

if (!$empleado) exit("Empleado no encontrado.");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>✏️ Editar Empleado</h3>
    <form action="actualizar.php" method="POST">
        <input type="hidden" name="id" value="<?= $empleado['id'] ?>">
        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($empleado['nombre']) ?>">
        </div>
        <div class="mb-3">
            <label>Número de Identidad:</label>
            <input type="text" name="numero_identidad" class="form-control" required value="<?= htmlspecialchars($empleado['numero_identidad']) ?>">
        </div>
        <div class="mb-3">
            <label>Fecha de Nacimiento:</label>
            <input type="date" name="fecha_nacimiento" class="form-control" value="<?= $empleado['fecha_nacimiento'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        <a href="listar.php" class="btn btn-secondary">🔙 Volver</a>
    </form>
</div>
</body>
</html>

<?php
require '../conexion.php';

$stmt = $pdo->query("SELECT * FROM empleados");
$empleados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>👨‍🔧 Empleados Registrados</h3>
    <a href="registrar.php" class="btn btn-success mb-3">➕ Nuevo Empleado</a>
    <a href="index.php" class="btn btn-secondary mb-3 ms-2">🔙 Volver</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Identidad</th>
                <th>Fecha Nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado): ?>
                <tr>
                    <td><?= $empleado['id'] ?></td>
                    <td><?= htmlspecialchars($empleado['nombre']) ?></td>
                    <td><?= htmlspecialchars($empleado['numero_identidad']) ?></td>
                    <td><?= $empleado['fecha_nacimiento'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $empleado['id'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                        <a href="eliminar.php?id=<?= $empleado['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este empleado?');">🗑 Eliminar</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>

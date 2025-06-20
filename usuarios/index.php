<?php
session_start();
require '../conexion.php';
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h3>👥 Usuarios</h3>
    <a href="registrar.php" class="btn btn-success mb-3">➕ Nuevo Usuario</a>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['usuario']) ?></td>
                <td><?= $u['rol'] ?></td>
                <td><?= $u['activo'] ? 'Activo' : 'Inhabilitado' ?></td>
                <td>
                    <?php if ($u['activo']): ?>
                        <a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                        <a href="inhabilitar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas inhabilitar este usuario?')">🚫 Inhabilitar</a>
                    <?php else: ?>
                        <span class="text-muted">Sin acciones</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../dashboard.php" class="btn btn-secondary">🔙 Volver</a>
</body>
</html>

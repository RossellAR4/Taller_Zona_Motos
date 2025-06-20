<?php
require '../conexion.php';
$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h3>✏️ Editar Usuario</h3>
    <form action="actualizar.php" method="POST">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="usuario" class="form-control" value="<?= htmlspecialchars($usuario['usuario']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Nueva Contraseña (dejar vacío si no desea cambiarla)</label>
            <input type="password" name="contrasena" class="form-control">
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-select">
                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="cajero" <?= $usuario['rol'] === 'cajero' ? 'selected' : '' ?>>Cajero</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>

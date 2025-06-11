<?php
session_start();
require '../conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID no válido.";
    exit();
}

$mensaje = '';
$error = '';

$stmt = $pdo->prepare("SELECT * FROM trabajos WHERE id = ?");
$stmt->execute([$id]);
$trabajo = $stmt->fetch();

if (!$trabajo) {
    echo "Trabajo no encontrado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente_nombre'];
    $descripcion = $_POST['descripcion'];
    $valor = $_POST['valor_cobrado'];

    if ($cliente && $descripcion && $valor !== '') {
        $stmt = $pdo->prepare("UPDATE trabajos SET cliente_nombre = ?, descripcion = ?, valor_cobrado = ? WHERE id = ?");
        if ($stmt->execute([$cliente, $descripcion, $valor, $id])) {
            $mensaje = "✅ Registro actualizado correctamente.";
          
            $trabajo['cliente_nombre'] = $cliente;
            $trabajo['descripcion'] = $descripcion;
            $trabajo['valor_cobrado'] = $valor;
        } else {
            $error = "❌ Error al actualizar.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">✏️ Editar Trabajo</h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= $mensaje ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <input type="text" name="cliente_nombre" class="form-control" value="<?= htmlspecialchars($trabajo['cliente_nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($trabajo['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Valor cobrado</label>
            <input type="number" name="valor_cobrado" class="form-control" value="<?= $trabajo['valor_cobrado'] ?>" required>
        </div>
        <button type="submit" class="btn btn-success">💾 Guardar Cambios</button>
        <a href="vista_mensual.php" class="btn btn-secondary">🔙 Volver</a>
    </form>
</div>

</body>
</html>

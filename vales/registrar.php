<?php
session_start();
require '../conexion.php';
date_default_timezone_set('America/Tegucigalpa');
if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleado_id = $_POST['empleado_id'];
    $monto = $_POST['monto'];
    $motivo = $_POST['motivo'];
    $fecha_hora = date('Y-m-d H:i:s');

    if ($empleado_id && $monto && $motivo) {
        $sql = "INSERT INTO vales (empleado_id, fecha_hora, monto, motivo) VALUES (:empleado_id, :fecha_hora, :monto, :motivo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':empleado_id' => $empleado_id,
            ':fecha_hora' => $fecha_hora,
            ':monto' => $monto,
            ':motivo' => $motivo
        ]);
        $mensaje = "Vale registrado correctamente.";
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}

$empleados = $pdo->query("SELECT id, nombre FROM empleados ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Vale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4 text-center">➕ Registrar Vale</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="empleado_id" class="form-label">Empleado</label>
            <select name="empleado_id" id="empleado_id" class="form-select" required>
                <option value="">Seleccione un empleado</option>
                <?php foreach ($empleados as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="monto" class="form-label">Monto</label>
            <input type="number" step="0.01" name="monto" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="motivo" class="form-label">Motivo</label>
            <textarea name="motivo" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Guardar Vale</button>
        <a href="index.php" class="btn btn-secondary w-100 mt-2">Volver</a>
    </form>
</div>
</body>
</html>

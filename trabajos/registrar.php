<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../conexion.php';
date_default_timezone_set('America/Tegucigalpa');

if (isset($_POST['guardar'])) {
    $cliente_nombre = $_POST['cliente_nombre'] ?? '';
    $empleado_id = $_POST['empleado_id'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $valor_cobrado = $_POST['valor_cobrado'] ?? '';
    $fecha_hora = date('Y-m-d H:i:s');
    $valor_empresa = $valor_cobrado * 0.5;

    if ($cliente_nombre && $empleado_id && $descripcion && $valor_cobrado) {
        $sql = "INSERT INTO trabajos (
                    cliente_nombre, empleado_id, fecha_hora, descripcion, valor_cobrado, valor_empresa
                ) VALUES (
                    :cliente_nombre, :empleado_id, :fecha_hora, :descripcion, :valor_cobrado, :valor_empresa
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cliente_nombre' => $cliente_nombre,
            ':empleado_id' => $empleado_id,
            ':fecha_hora' => $fecha_hora,
            ':descripcion' => $descripcion,
            ':valor_cobrado' => $valor_cobrado,
            ':valor_empresa' => $valor_empresa
        ]);

        echo "<script>alert('✅ Trabajo registrado correctamente'); window.location.href='registrar.php';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Todos los campos son obligatorios');</script>";
    }
}


$empleados = $pdo->query("SELECT id, nombre FROM empleados")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">🛠️ Registrar Trabajo Realizado</h3>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre del cliente</label>
            <input type="text" class="form-control" name="cliente_nombre" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Empleado</label>
            <select class="form-select" name="empleado_id" required>
                <option value="">Seleccione un empleado</option>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?= $empleado['id'] ?>"><?= htmlspecialchars($empleado['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción del trabajo</label>
            <textarea class="form-control" name="descripcion" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Valor cobrado (L)</label>
            <input type="number" step="0.01" class="form-control" name="valor_cobrado" required>
        </div>

        <button type="submit" name="guardar" class="btn btn-primary">Guardar Trabajo</button>
        <a href="../dashboard.php" class="btn btn-secondary">Volver al menú</a>
    </form>
</div>
</body>
</html>

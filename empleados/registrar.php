<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../conexion.php';

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $numero_identidad = $_POST['numero_identidad'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';

    if ($nombre && $numero_identidad && $fecha_nacimiento) {
        try {
            $sql = "INSERT INTO empleados (nombre, numero_identidad, fecha_nacimiento) 
                    VALUES (:nombre, :numero_identidad, :fecha_nacimiento)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':numero_identidad' => $numero_identidad,
                ':fecha_nacimiento' => $fecha_nacimiento
            ]);

            echo "<script>alert('✅ Empleado registrado correctamente'); window.location.href='registrar.php';</script>";
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000 && str_contains($e->getMessage(), 'numero_identidad')) {
                echo "<script>alert('⚠️ Ya existe un empleado con ese número de identidad'); window.history.back();</script>";
            } else {
                echo "<script>alert('❌ Error inesperado: " . $e->getMessage() . "'); window.history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('❌ Todos los campos son obligatorios');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">👤 Registrar Nuevo Empleado</h3>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Número de Identidad</label>
            <input type="text" class="form-control" name="numero_identidad" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de nacimiento</label>
            <input type="date" class="form-control" name="fecha_nacimiento" required>
        </div>
        <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Volver al menú</a>
    </form>
</div>
</body>
</html>

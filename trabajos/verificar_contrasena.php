<?php
session_start();
require '../conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID de trabajo no válido.";
    exit();
}

$error = '';

// Solo procesamos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contrasena_ingresada = $_POST['contrasena'] ?? '';

    // Traemos los datos del usuario actual
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->execute(['usuario' => $_SESSION['usuario']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena_ingresada, $usuario['contraseña'])) {
        // Contraseña correcta, redirigir a editar
        header("Location: editar.php?id=$id");
        exit();
    } else {
        $error = "❌ Contraseña incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h4 class="mb-4">🔐 Verificación de Contraseña</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="contrasena" class="form-label">Introduce tu contraseña para editar el registro</label>
            <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Verificar</button>
        <a href="vista_mensual.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>

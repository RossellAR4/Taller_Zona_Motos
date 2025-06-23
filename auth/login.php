<?php
session_start();
require '../conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';

    if (empty($usuario) || empty($contraseña)) {
        echo "<script>alert('Por favor complete todos los campos'); window.location='login.php';</script>";
        exit();
    }

    
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND activo = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario' => $usuario]);
    $usuarioBD = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioBD && password_verify($contraseña, $usuarioBD['contraseña'])) {

        $_SESSION['usuario'] = $usuarioBD['usuario'];
        $_SESSION['rol'] = $usuarioBD['rol'];  

        header("Location: ../dashboard.php");
        exit();
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Login - Taller Zona Motos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 400px;">
    <h3 class="mb-4 text-center">Iniciar Sesión</h3>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" id="usuario" name="usuario" class="form-control" required autofocus />
        </div>
        <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña</label>
            <input type="password" id="contraseña" name="contraseña" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>
</body>
</html>

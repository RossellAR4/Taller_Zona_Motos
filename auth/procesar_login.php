<?php
session_start();
require '../conexion.php';

$usuario = $_POST['usuario'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';

$sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario' => $usuario]);
$usuarioBD = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuarioBD && password_verify($contraseña, $usuarioBD['contraseña'])) {
    $_SESSION['usuario'] = $usuarioBD['usuario'];
    header("Location: ../dashboard.php"); 
} else {
    echo "<script>alert('Credenciales incorrectas'); window.location='login.php';</script>";
}
?>

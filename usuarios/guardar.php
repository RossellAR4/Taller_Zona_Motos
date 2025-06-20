<?php
require '../conexion.php';

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$rol = $_POST['rol'] ?? 'cajero';

if ($usuario && $contrasena) {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, contraseña, rol, activo) VALUES (:usuario, :contrasena, :rol, 1)");

    try {
        $stmt->execute([
            ':usuario' => $usuario,
            ':contrasena' => $hash,
            ':rol' => $rol
        ]);
        header("Location: index.php");
    } catch (PDOException $e) {
        echo "<script>alert('Error: el usuario ya existe'); window.location='registrar.php';</script>";
    }
}
?>

<?php
require '../conexion.php';

$id = $_POST['id'] ?? null;
$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$rol = $_POST['rol'] ?? 'cajero';

if ($id && $usuario) {
    if ($contrasena) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET usuario = :usuario, contraseña = :contrasena, rol = :rol WHERE id = :id";
        $params = [':usuario' => $usuario, ':contrasena' => $hash, ':rol' => $rol, ':id' => $id];
    } else {
        $sql = "UPDATE usuarios SET usuario = :usuario, rol = :rol WHERE id = :id";
        $params = [':usuario' => $usuario, ':rol' => $rol, ':id' => $id];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    header("Location: index.php");
}
?>

<?php
require '../conexion.php';

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$numero_identidad = $_POST['numero_identidad'];
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?: null;

$stmt = $pdo->prepare("UPDATE empleados SET nombre = ?, numero_identidad = ?, fecha_nacimiento = ? WHERE id = ?");
$stmt->execute([$nombre, $numero_identidad, $fecha_nacimiento, $id]);

header("Location: listar.php");
exit();

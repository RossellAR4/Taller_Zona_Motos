<?php
session_start();
require '../conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Lista de meses en español
$meses_es = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

$mes = $_GET['mes'] ?? date('m');
$anio = $_GET['anio'] ?? date('Y');

$mes_num = intval($mes); // Para obtener el nombre del mes en español
$mes_nombre = $meses_es[$mes_num] ?? '';

$stmt = $pdo->prepare("
    SELECT t.*, e.nombre AS empleado
    FROM trabajos t
    JOIN empleados e ON t.empleado_id = e.id
    WHERE MONTH(t.fecha_hora) = ? AND YEAR(t.fecha_hora) = ?
    ORDER BY t.fecha_hora DESC
");
$stmt->execute([$mes, $anio]);
$trabajos = $stmt->fetchAll();

$total_mes = array_sum(array_column($trabajos, 'valor_cobrado'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Mensual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3 class="mb-4 text-center">📅 Vista Mensual de Trabajos - <?= $mes_nombre ?> <?= $anio ?></h3>

    <form class="row g-2 mb-4" method="get">
        <div class="col-md-3">
            <select name="mes" class="form-select">
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    $m_val = str_pad($m, 2, '0', STR_PAD_LEFT);
                    $nombre_mes = $meses_es[$m];
                    echo "<option value='$m_val'" . ($mes == $m_val ? ' selected' : '') . ">$nombre_mes</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="anio" class="form-select">
                <?php
                for ($y = date('Y'); $y >= 2023; $y--) {
                    echo "<option value='$y'" . ($anio == $y ? ' selected' : '') . ">$y</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
        <div class="col-md-3">
            <a href="../dashboard.php" class="btn btn-secondary w-100">🔙 Menú Principal</a>
        </div>
    </form>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Empleado</th>
                <th>Cliente</th>
                <th>Descripción</th>
                <th>Valor</th>
                <th>Empresa (50%)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($trabajos): ?>
                <?php foreach ($trabajos as $t): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($t['fecha_hora'])) ?></td>
                        <td><?= htmlspecialchars($t['empleado']) ?></td>
                        <td><?= htmlspecialchars($t['cliente_nombre']) ?></td>
                        <td><?= htmlspecialchars($t['descripcion']) ?></td>
                        <td>L.<?= number_format($t['valor_cobrado'], 0, ',', '.') ?></td>
                        <td>L.<?= number_format($t['valor_cobrado'] * 0.5, 0, ',', '.') ?></td>
                        <td>
                            <a href="verificar_contrasena.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No hay trabajos registrados este mes.</td></tr>
            <?php endif; ?>
        </tbody>
        <?php if ($trabajos): ?>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">💰 Total del mes:</th>
                <th>L.<?= number_format($total_mes, 0, ',', '.') ?></th>
                <th>L.<?= number_format($total_mes * 0.5, 0, ',', '.') ?> (empresa)</th>
                <th></th>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</div>
</body>
</html>

<?php
session_start();
date_default_timezone_set('America/Tegucigalpa');
if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../conexion.php';

$hoy = new DateTime();
$diaSemana = $hoy->format('N'); 
$lunes = clone $hoy;
$lunes->modify('-' . ($diaSemana - 1) . ' days'); 

$dias = [];
for ($i = 0; $i < 6; $i++) {
    $dia = clone $lunes;
    $dia->modify("+$i days");
    $dias[] = $dia;
}


$fecha = $_GET['fecha'] ?? $hoy->format('Y-m-d');


$sql = "
    SELECT 
        e.nombre AS empleado_nombre,
        t.descripcion,
        t.valor_cobrado,
        t.valor_empresa,
        t.fecha_hora
    FROM trabajos t
    JOIN empleados e ON t.empleado_id = e.id
    WHERE DATE(t.fecha_hora) = :fecha
    ORDER BY e.nombre, t.fecha_hora
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':fecha' => $fecha]);
$trabajos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$datos_por_empleado = [];
foreach ($trabajos as $t) {
    $empleado = $t['empleado_nombre'];
    if (!isset($datos_por_empleado[$empleado])) {
        $datos_por_empleado[$empleado] = [
            'trabajos' => [],
            'total_valor' => 0,
            'total_empresa' => 0
        ];
    }
    $datos_por_empleado[$empleado]['trabajos'][] = $t;
    $datos_por_empleado[$empleado]['total_valor'] += $t['valor_cobrado'];
    $datos_por_empleado[$empleado]['total_empresa'] += $t['valor_empresa'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Diaria de Trabajos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3 class="mb-4">📆 Vista Diaria (Semana Actual)</h3>

    <div class="mb-4 d-flex flex-wrap gap-2">
        <?php
        $nombres_dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        foreach ($dias as $i => $dia): 
            $fecha_btn = $dia->format('Y-m-d');
            $activo = ($fecha_btn === $fecha) ? 'btn-primary' : 'btn-outline-primary';
        ?>
            <a href="?fecha=<?= $fecha_btn ?>" class="btn <?= $activo ?>">
                <?= $nombres_dias[$i] ?> <br><small><?= $fecha_btn ?></small>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($datos_por_empleado)): ?>
        <div class="alert alert-info">No hay trabajos registrados para este día.</div>
    <?php else: ?>
        <?php foreach ($datos_por_empleado as $empleado => $datos): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong><?= htmlspecialchars($empleado) ?></strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Descripción</th>
                                <th>Valor cobrado (L)</th>
                                <th>50% Empresa (L)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['trabajos'] as $trabajo): ?>
                                <tr>
                                    <td><?= date('H:i', strtotime($trabajo['fecha_hora'])) ?></td>
                                    <td><?= htmlspecialchars($trabajo['descripcion']) ?></td>
                                    <td>L.<?= number_format($trabajo['valor_cobrado'], 2) ?></td>
                                    <td>L.<?= number_format($trabajo['valor_empresa'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th colspan="2">Total por empleado</th>
                                <th>L.<?= number_format($datos['total_valor'], 2) ?></th>
                                <th>L.<?= number_format($datos['total_empresa'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="../dashboard.php" class="btn btn-secondary mt-4">⬅ Volver al menú</a>
</div>
</body>
</html>

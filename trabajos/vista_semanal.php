<?php
require_once '../conexion.php';
date_default_timezone_set('America/Tegucigalpa');

$hoy = date('Y-m-d');
$lunes = date('Y-m-d', strtotime('monday this week', strtotime($hoy)));
$sabado = date('Y-m-d', strtotime('saturday this week', strtotime($hoy)));

// Obtener totales por día de trabajo
$sql = "SELECT 
            e.nombre AS empleado,
            DATE(t.fecha_hora) AS dia,
            SUM(t.valor_cobrado) AS total_dia
        FROM trabajos t
        JOIN empleados e ON t.empleado_id = e.id
        WHERE DATE(t.fecha_hora) BETWEEN :lunes AND :sabado
        GROUP BY e.id, DATE(t.fecha_hora)
        ORDER BY e.nombre, dia";

$stmt = $pdo->prepare($sql);
$stmt->execute([':lunes' => $lunes, ':sabado' => $sabado]);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por empleado y día
$empleados = [];
$dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$dias_es = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

foreach ($registros as $fila) {
    $nombre = $fila['empleado'];
    $dia = date('l', strtotime($fila['dia']));
    $empleados[$nombre]['trabajos'][$dia] = $fila['total_dia'];
}

// Obtener total de vales por empleado durante la semana
$sql_vales = "SELECT 
                e.nombre AS empleado,
                SUM(v.monto) AS total_vales
              FROM vales v
              JOIN empleados e ON v.empleado_id = e.id
              WHERE DATE(v.fecha_hora) BETWEEN :lunes AND :sabado
              GROUP BY e.id";

$stmt_vales = $pdo->prepare($sql_vales);
$stmt_vales->execute([':lunes' => $lunes, ':sabado' => $sabado]);
$vales = $stmt_vales->fetchAll(PDO::FETCH_ASSOC);

foreach ($vales as $fila) {
    $empleados[$fila['empleado']]['vales'] = $fila['total_vales'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Semanal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Resumen Semanal: <?= date('d M Y', strtotime($lunes)) ?> al <?= date('d M Y', strtotime($sabado)) ?></h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Empleado</th>
                <?php foreach ($dias_es as $dia): ?>
                    <th><?= $dia ?></th>
                <?php endforeach; ?>
                <th>Total</th>
                <th>50%</th>
                <th>Vales</th>
                <th>Pago Final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $nombre => $data): 
                $total = 0;
                $vales = $data['vales'] ?? 0;
            ?>
                <tr>
                    <td><?= htmlspecialchars($nombre) ?></td>
                    <?php foreach ($dias as $dia_en): 
                        $valor = $data['trabajos'][$dia_en] ?? 0;
                        $total += $valor;
                    ?>
                        <td><?= number_format($valor, 2) ?></td>
                    <?php endforeach; ?>
                    <td><strong><?= number_format($total, 2) ?></strong></td>
                    <td><?= number_format($total * 0.5, 2) ?></td>
                    <td class="text-danger"><?= number_format($vales, 2) ?></td>
                    <td class="text-success fw-bold">
                        <?= number_format(($total * 0.5) - $vales, 2) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="../dashboard.php" class="btn btn-secondary">Volver al Menú</a>
</body>
</html>

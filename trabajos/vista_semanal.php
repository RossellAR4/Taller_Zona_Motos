<?php
require_once '../conexion.php';
date_default_timezone_set('America/Tegucigalpa');


$hoy = date('Y-m-d');
$lunes = date('Y-m-d', strtotime('monday this week', strtotime($hoy)));
$sabado = date('Y-m-d', strtotime('saturday this week', strtotime($hoy)));


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


$empleados = [];
$dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$dias_es = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

foreach ($registros as $fila) {
    $nombre = $fila['empleado'];
    $dia = date('l', strtotime($fila['dia'])); 
    $empleados[$nombre][$dia] = $fila['total_dia'];
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

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Empleado</th>
                <?php foreach ($dias_es as $dia): ?>
                    <th><?= $dia ?></th>
                <?php endforeach; ?>
                <th>Total</th>
                <th>50% para Empleado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $nombre => $dias_trabajo): 
                $total = 0;
            ?>
                <tr>
                    <td><?= htmlspecialchars($nombre) ?></td>
                    <?php foreach ($dias as $dia_en): 
                        $valor = $dias_trabajo[$dia_en] ?? 0;
                        $total += $valor;
                    ?>
                        <td><?= number_format($valor, 2) ?></td>
                    <?php endforeach; ?>
                    <td><strong><?= number_format($total, 2) ?></strong></td>
                    <td><?= number_format($total * 0.5, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="../dashboard.php" class="btn btn-secondary">Volver al Menú</a>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #222; }
        h1   { font-size: 20px; margin-bottom: 4px; }
        p.rango { font-size: 11px; color: #555; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #2d6a4f; color: #fff; padding: 7px 10px; text-align: left; }
        td { padding: 6px 10px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) td { background: #f4f4f4; }
        tr.total td { font-weight: bold; background: #e8f5e9; }
        .empty { color: #888; font-style: italic; margin-top: 10px; }
    </style>
</head>
<body>

    <h1><?= htmlspecialchars($titulo) ?></h1>
    <p class="rango">Período: <?= htmlspecialchars($inicio) ?> — <?= htmlspecialchars($fin) ?></p>

    <?php if (empty($datos)): ?>
        <p class="empty">No hay datos en el rango seleccionado.</p>

    <?php elseif ($tipo === 'reservas'): ?>
        <?php $totRes = 0; $totCupos = 0; ?>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Total Reservas</th>
                <th>Total Cupos</th>
            </tr>
            <?php foreach ($datos as $row): ?>
                <?php $totRes += $row['total_reservas']; $totCupos += $row['total_cupos']; ?>
                <tr>
                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                    <td><?= $row['total_reservas'] ?></td>
                    <td><?= $row['total_cupos'] ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td>TOTAL</td>
                <td><?= $totRes ?></td>
                <td><?= $totCupos ?></td>
            </tr>
        </table>

    <?php elseif ($tipo === 'compras'): ?>
        <?php $totCompras = 0; $totIngresos = 0.0; ?>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Total Compras</th>
                <th>Total Ingresos</th>
            </tr>
            <?php foreach ($datos as $row): ?>
                <?php $totCompras += $row['total_compras']; $totIngresos += $row['total_ingresos']; ?>
                <tr>
                    <td><?= htmlspecialchars($row['fecha']) ?></td>
                    <td><?= $row['total_compras'] ?></td>
                    <td>Bs <?= number_format($row['total_ingresos'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td>TOTAL</td>
                <td><?= $totCompras ?></td>
                <td>Bs <?= number_format($totIngresos, 2) ?></td>
            </tr>
        </table>

    <?php elseif ($tipo === 'guias'): ?>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Observaciones</th>
            </tr>
            <?php foreach ($datos as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fecha_reporte']) ?></td>
                    <td><?= htmlspecialchars($row['observaciones'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>

</body>
</html>
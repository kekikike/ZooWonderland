<?php
/** @var array $reportes */
/** @var \App\Models\Usuario $user */
$mensajeExito = $_SESSION['mensaje_exito'] ?? null;
if ($mensajeExito) unset($_SESSION['mensaje_exito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reportes - ZooWonderland</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --verde-selva:   #2e7d32;
            --verde-oscuro:  #1b5e20;
            --amarillo-sol:  #ffca28;
            --gris-claro:    #f8faf8;
            --oscuro:        #0d3a1f;
            --blanco:        #ffffff;
            --sombra:        0 10px 30px rgba(0,0,0,0.08);
        }
        body { font-family: 'Open Sans', sans-serif; background: var(--gris-claro); padding: 2rem; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { font-family: 'Montserrat', sans-serif; color: var(--oscuro); margin-bottom: 2rem; text-align: center; }
        .alert { padding: 1rem; border-radius: 12px; margin-bottom: 2rem; background: #e8f5e9; color: var(--verde-oscuro); border: 1px solid #c8e6c9; font-weight: 700; }
        .reporte-card { background: white; border-radius: 18px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: var(--sombra); border-left: 6px solid var(--amarillo-sol); }
        .reporte-top { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.85rem; color: #666; font-weight: 700; }
        .recorrido-title { font-size: 1.2rem; margin-bottom: 1rem; color: var(--verde-selva); font-weight: 800; font-family: 'Montserrat', sans-serif; }
        .obs-text { line-height: 1.8; color: #444; background: #f9fbf9; padding: 1rem; border-radius: 10px; border: 1px solid #eee; font-style: italic; }
        .empty-state { text-align: center; padding: 5rem; background: white; border-radius: 20px; color: #bbb; }
    </style>
</head>
<body>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <a href="index.php?r=guias/dashboard" style="color: #666; text-decoration: none; font-weight: 700;">
            <i class="fa-solid fa-house"></i> Ir al Panel
        </a>
        <div style="font-weight: 800; color: var(--verde-selva);">GUÍA: <?= htmlspecialchars($user->getNombreParaMostrar()) ?></div>
    </div>

    <h1>Historial de Reportes Generados</h1>

    <?php if ($mensajeExito): ?>
        <div class="alert"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($mensajeExito) ?></div>
    <?php endif; ?>

    <?php if (empty($reportes)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-clipboard-list fa-4x" style="margin-bottom: 1rem;"></i>
            <h2>No has registrado reportes todavía.</h2>
            <p>Los reportes son obligatorios al finalizar cada recorrido.</p>
        </div>
    <?php else: ?>
        <?php foreach ($reportes as $rep): ?>
            <div class="reporte-card">
                <div class="reporte-top">
                    <span><i class="fa-solid fa-calendar"></i> Recorrido del: <?= date('d/m/Y', strtotime($rep['fecha_asignacion'])) ?></span>
                    <span><i class="fa-solid fa-clock"></i> Reportado el: <?= date('d/m/Y H:i', strtotime($rep['fecha_reporte'])) ?></span>
                </div>
                <div class="recorrido-title"><?= htmlspecialchars($rep['recorrido_nombre']) ?></div>
                <div class="obs-text">
                    "<?= htmlspecialchars($rep['observaciones']) ?>"
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// app/Views/guias/horarios.php
declare(strict_types=1);
$currentTab = 'horarios';  // ← activa esta pestaña
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Horarios - ZooWonderland</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f5f0;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        header {
            background: #a3712a;
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-radius: 0 0 12px 12px;
        }
        .nav-tabs {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 1.5rem 0;
        }
        .nav-tabs a {
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            background: #977c66;
            transition: all 0.3s;
        }
        .nav-tabs a:hover,
        .nav-tabs a.active {
            background: #bfb641;
            color: #333;
            font-weight: bold;
        }
        .calendar-container {
            max-width: 1100px;
            margin: 0 auto;
        }
        .calendar-header {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            color: #68672e;
            margin-bottom: 1rem;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
        }
        .day-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            min-height: 180px;
            text-align: center;
            border: 2px solid #e0e0e0;
            transition: all 0.2s;
        }
        .day-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #bfb641;
        }
        .day-name {
            font-size: 1.4rem;
            color: #a3712a;
            margin-bottom: 0.8rem;
        }
        .horario {
            font-size: 1.6rem;
            font-weight: bold;
            color: #68672e;
            margin: 0.8rem 0;
        }
        .no-horario {
            color: #999;
            font-style: italic;
            margin-top: 2rem;
        }
        .nota {
            text-align: center;
            margin-top: 2rem;
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>

<header>
    <h1>Panel del Guía</h1>
</header>

<div class="nav-tabs">
    <a href="index.php?r=guias/dashboard">Mis Recorridos</a>
    <a href="index.php?r=guias/horarios" class="active">Mis Horarios</a>
</div>

<div class="calendar-container">
    <h2>Mis Horarios Semanales</h2>

    <?php if (!$datosGuia || empty($datosGuia['dias_trabajo'])): ?>
        <p style="text-align:center; color:#777; font-size:1.2rem;">
            No tienes horarios asignados actualmente.
        </p>
    <?php else: ?>
        <div class="calendar-header">
            <div>Martes</div>
            <div>Miércoles</div>
            <div>Jueves</div>
            <div>Viernes</div>
            <div>Sábado</div>
            <div>Domingo</div>
        </div>

        <div class="calendar-grid">
            <?php
            // Días fijos: martes a domingo
            $dias = ['Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $horarioTexto = htmlspecialchars($datosGuia['horarios'] ?? 'No definido');
            $diasTrabajoTexto = strtolower($datosGuia['dias_trabajo'] ?? '');

            foreach ($dias as $dia): 
                $diaLower = strtolower($dia);
                $tieneHorario = strpos($diasTrabajoTexto, $diaLower) !== false || strpos($diasTrabajoTexto, 'a') !== false;
            ?>
                <div class="day-card">
                    <div class="day-name"><?= $dia ?></div>
                    
                    <?php if ($tieneHorario): ?>
                        <div class="horario"><?= $horarioTexto ?></div>
                        <small style="color:#777;">(Horario asignado)</small>
                    <?php else: ?>
                        <div class="no-horario">Sin horario</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="nota">
            Horario general asignado: <?= $horarioTexto ?><br>
            Días de trabajo: <?= htmlspecialchars($datosGuia['dias_trabajo']) ?>
        </div>
    <?php endif; ?>

    <p style="text-align:center; margin-top:2rem;">
        <a href="index.php?r=guias/dashboard">← Volver a Mis Recorridos</a> | 
        <a href="index.php?r=logout">Cerrar sesión</a>
    </p>
</div>

</body>
</html>
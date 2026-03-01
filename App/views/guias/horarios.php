<?php
// app/Views/guias/horarios.php
declare(strict_types=1);
$currentTab = 'horarios';
require_once APP_PATH . '/Views/guias/partials/tabs.php';

/* ── Calcular la semana en curso (lunes → domingo, mostramos mar→dom) ── */
$hoy       = new \DateTime();
// Primer día de la semana = lunes
$lunes     = clone $hoy;
$lunes->modify('Monday this week');

// Días que mostramos: martes a domingo
$diasSemana = [];
for ($i = 1; $i <= 6; $i++) {
    $d = clone $lunes;
    $d->modify("+{$i} days");
    $diasSemana[] = $d;
}

// Parsear horario guardado: "09:00 - 15:00"
$horaInicio = null;
$horaFin    = null;
if ($datosGuia && !empty($datosGuia['horarios'])) {
    $partes = array_map('trim', explode('-', $datosGuia['horarios']));
    if (count($partes) === 2) {
        $horaInicio = $partes[0];
        $horaFin    = $partes[1];
    }
}

// Calcular si el guía trabaja ese día de la semana
// "Martes a Domingo" → todos los días 2-7 de la semana
function guiaTrabajaDia(\DateTime $dia, string $diasTrabajo): bool {
    $dt = strtolower($diasTrabajo);
    $nombreEn = $dia->format('l');   // Monday, Tuesday...
    $map = [
        'monday'    => 'lunes',
        'tuesday'   => 'martes',
        'wednesday' => 'miércoles',
        'thursday'  => 'jueves',
        'friday'    => 'viernes',
        'saturday'  => 'sábado',
        'sunday'    => 'domingo',
    ];
    $nombreEs = $map[strtolower($nombreEn)] ?? '';
    // Si contiene "a" (rango: "martes a domingo") → todos aplican si
    // el día está dentro del rango
    if (strpos($dt, ' a ') !== false) {
        preg_match('/(\w+)\s+a\s+(\w+)/', $dt, $m);
        if (count($m) === 3) {
            $orden = ['lunes','martes','miércoles','jueves','viernes','sábado','domingo'];
            $desde = array_search($m[1], $orden);
            $hasta = array_search($m[2], $orden);
            $pos   = array_search($nombreEs, $orden);
            if ($desde !== false && $hasta !== false && $pos !== false) {
                return $pos >= $desde && $pos <= $hasta;
            }
        }
    }
    return strpos($dt, $nombreEs) !== false;
}

$nombreDiaEs = [
    'Monday'    => 'Lunes',
    'Tuesday'   => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday'  => 'Jueves',
    'Friday'    => 'Viernes',
    'Saturday'  => 'Sábado',
    'Sunday'    => 'Domingo',
];

$meses = [
    1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',
    7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'
];
$semanaLabel = $diasSemana[0]->format('j') . ' de ' . $meses[(int)$diasSemana[0]->format('n')]
    . ' – ' . $diasSemana[5]->format('j') . ' de ' . $meses[(int)$diasSemana[5]->format('n')]
    . ', ' . $diasSemana[0]->format('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Horarios – ZooWonderland</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brown:    #a3712a;
            --brown-dk: #7a521c;
            --olive:    #68672e;
            --olive-lt: #bfb641;
            --teal:     #4a8c8e;
            --teal-lt:  #7eaeb0;
            --cream:    #faf7f2;
            --card:     #ffffff;
            --text:     #2d2d2d;
            --muted:    #777;
            --border:   #e8e0d4;
            --today-bg: #fff8e6;
            --today-bd: #bfb641;
            --off-bg:   #f4f0ea;
            --off-text: #bbb;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── HEADER ── */
        header {
            background: linear-gradient(135deg, var(--brown-dk) 0%, var(--brown) 60%, #c4882f 100%);
            color: white;
            padding: 1.6rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,.25);
        }
        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
        }
        header .user-badge {
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.35);
            padding: .45rem 1.1rem;
            border-radius: 999px;
            font-size: .9rem;
            font-weight: 600;
        }

        /* ── NAV TABS ── */
        .nav-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding: 1.4rem 1rem;
            background: var(--brown-dk);
        }
        .nav-tabs a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            padding: .65rem 1.8rem;
            border-radius: 8px;
            background: rgba(255,255,255,.12);
            font-weight: 500;
            font-size: .95rem;
            transition: all .25s;
            border: 1px solid transparent;
        }
        .nav-tabs a:hover { background: rgba(255,255,255,.22); }
        .nav-tabs a.active {
            background: var(--olive-lt);
            color: #222;
            font-weight: 700;
        }

        /* ── MAIN ── */
        main { max-width: 1100px; margin: 2.5rem auto; padding: 0 1.5rem; }

        .section-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .8rem;
            margin-bottom: 1.8rem;
            padding-bottom: .8rem;
            border-bottom: 2px solid var(--border);
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            color: var(--brown);
        }
        .semana-label {
            font-size: .88rem;
            color: var(--muted);
            background: var(--card);
            padding: .35rem 1rem;
            border-radius: 999px;
            border: 1px solid var(--border);
        }

        /* ── INFO HORARIO GENERAL ── */
        .horario-general {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .info-chip {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .7rem 1.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }
        .chip-icon { font-size: 1.3rem; }
        .chip-label { font-size: .75rem; color: var(--muted); display: block; }
        .chip-val   { font-size: 1rem; font-weight: 700; color: var(--text); }

        /* ── CALENDARIO ── */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
        }

        @media (max-width: 900px) {
            .calendar-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 520px) {
            .calendar-grid { grid-template-columns: repeat(2, 1fr); }
        }

        .day-card {
            background: var(--card);
            border-radius: 14px;
            padding: 1.3rem 1rem;
            text-align: center;
            box-shadow: 0 3px 12px rgba(0,0,0,.07);
            border: 2px solid var(--border);
            transition: transform .2s, box-shadow .2s, border-color .2s;
            position: relative;
            overflow: hidden;
        }
        .day-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
        }
        .day-card.today {
            background: var(--today-bg);
            border-color: var(--today-bd);
        }
        .day-card.today::before {
            content: 'HOY';
            position: absolute;
            top: 8px; right: -18px;
            background: var(--olive-lt);
            color: #333;
            font-size: .65rem;
            font-weight: 800;
            padding: .2rem 1.6rem;
            transform: rotate(40deg);
            letter-spacing: 1px;
        }
        .day-card.no-trabajo {
            background: var(--off-bg);
            border-color: #ddd;
            opacity: .6;
        }

        .day-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--brown);
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: .3rem;
        }
        .day-date {
            font-size: .82rem;
            color: var(--muted);
            margin-bottom: 1rem;
        }

        .horario-block {
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-lt) 100%);
            color: white;
            border-radius: 10px;
            padding: .8rem .6rem;
            margin-bottom: .5rem;
        }
        .horario-time {
            font-size: 1.15rem;
            font-weight: 800;
            line-height: 1.2;
        }
        .horario-sub {
            font-size: .72rem;
            opacity: .85;
            margin-top: .15rem;
        }

        .no-trabajo-msg {
            font-size: .82rem;
            color: var(--off-text);
            font-style: italic;
            margin-top: .5rem;
        }

        /* ── LEYENDA ── */
        .leyenda {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 1.8rem;
            font-size: .83rem;
            color: var(--muted);
        }
        .leyenda-item { display: flex; align-items: center; gap: .5rem; }
        .leyenda-dot {
            width: 12px; height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .dot-activo  { background: var(--teal); }
        .dot-hoy     { background: var(--olive-lt); }
        .dot-libre   { background: #ccc; }

        .aviso-readonly {
            display: flex;
            align-items: center;
            gap: .6rem;
            background: #fff8e6;
            border: 1px solid #f0d060;
            border-radius: 10px;
            padding: .8rem 1.2rem;
            margin-bottom: 1.8rem;
            font-size: .88rem;
            color: #7a5c00;
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: var(--muted);
            font-size: .88rem;
        }
        footer a { color: var(--brown); text-decoration: none; font-weight: 600; }
        footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<header>
    <h1>🦁 ZooWonderland</h1>
    <span class="user-badge">👤 <?= htmlspecialchars($user->getNombreParaMostrar()) ?></span>
</header>

<?php require_once APP_PATH . '/Views/guias/partials/tabs.php'; ?>

<main>

    <div class="section-header">
        <h2 class="section-title">Mis Horarios Semanales</h2>
        <span class="semana-label">📅 Semana: <?= $semanaLabel ?></span>
    </div>

    <!-- Aviso solo lectura -->
    <div class="aviso-readonly">
        🔒 <span>Los horarios son de solo lectura. Para solicitar cambios, comunícate con el administrador.</span>
    </div>

    <?php if (!$datosGuia || empty($datosGuia['horarios'])): ?>
        <div style="text-align:center; padding:3rem; color:var(--muted); font-size:1.1rem;
                    background:white; border-radius:14px; border:2px dashed var(--border);">
            🗓️ No tienes horarios asignados actualmente.
        </div>
    <?php else: ?>

        <!-- Chips informativos -->
        <div class="horario-general">
            <div class="info-chip">
                <span class="chip-icon">🕘</span>
                <div>
                    <span class="chip-label">Hora de entrada</span>
                    <span class="chip-val"><?= htmlspecialchars($horaInicio ?? '—') ?></span>
                </div>
            </div>
            <div class="info-chip">
                <span class="chip-icon">🕒</span>
                <div>
                    <span class="chip-label">Hora de salida</span>
                    <span class="chip-val"><?= htmlspecialchars($horaFin ?? '—') ?></span>
                </div>
            </div>
            <div class="info-chip">
                <span class="chip-icon">📆</span>
                <div>
                    <span class="chip-label">Días laborales</span>
                    <span class="chip-val"><?= htmlspecialchars($datosGuia['dias_trabajo']) ?></span>
                </div>
            </div>
        </div>

        <!-- Calendario semanal -->
        <div class="calendar-grid">
            <?php foreach ($diasSemana as $dia):
                $enNombre   = $dia->format('l');
                $esHoy      = $dia->format('Y-m-d') === $hoy->format('Y-m-d');
                $trabaja    = guiaTrabajaDia($dia, $datosGuia['dias_trabajo']);
                $clases     = 'day-card' . ($esHoy ? ' today' : '') . (!$trabaja ? ' no-trabajo' : '');
            ?>
            <div class="<?= $clases ?>">
                <div class="day-name"><?= $nombreDiaEs[$enNombre] ?? $enNombre ?></div>
                <div class="day-date"><?= $dia->format('d') . '/' . $dia->format('m') ?></div>

                <?php if ($trabaja): ?>
                    <div class="horario-block">
                        <div class="horario-time">
                            <?= htmlspecialchars($horaInicio) ?><br>
                            <span style="font-size:.8rem; opacity:.8;">a</span><br>
                            <?= htmlspecialchars($horaFin) ?>
                        </div>
                        <div class="horario-sub">Turno activo</div>
                    </div>
                <?php else: ?>
                    <div class="no-trabajo-msg">Día libre</div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Leyenda -->
        <div class="leyenda">
            <div class="leyenda-item">
                <div class="leyenda-dot dot-activo"></div>Día laboral
            </div>
            <div class="leyenda-item">
                <div class="leyenda-dot dot-hoy"></div>Hoy
            </div>
            <div class="leyenda-item">
                <div class="leyenda-dot dot-libre"></div>Día libre
            </div>
        </div>
    <?php endif; ?>

</main>

<footer>
    <a href="index.php?r=guias/dashboard">← Volver a Mis Recorridos</a> &nbsp;·&nbsp;
    <a href="index.php?r=logout">Cerrar sesión</a>
</footer>

</body>
</html>

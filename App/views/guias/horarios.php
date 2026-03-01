<?php
// app/Views/guias/horarios.php
declare(strict_types=1);
$currentTab = 'horarios';

/* ══════════════════════════════════════════════════════════════
   LÓGICA PHP: semana en curso + siguiente, recorridos por día
   ══════════════════════════════════════════════════════════════ */

// Semana seleccionada (0 = esta semana, 1 = siguiente)
$semanaOffset = (int)($_GET['semana'] ?? 0);
$semanaOffset = max(0, min(1, $semanaOffset)); // solo 0 o 1

// Calcular lunes de la semana seleccionada
$hoy   = new \DateTime();
$lunes = clone $hoy;
$lunes->modify('Monday this week');
if ($semanaOffset === 1) {
    $lunes->modify('+7 days');
}

// Días mar → dom (índices 1-6 desde lunes)
$diasSemana = [];
for ($i = 1; $i <= 6; $i++) {
    $d = clone $lunes;
    $d->modify("+{$i} days");
    $diasSemana[] = $d;
}

$fechaInicio = $diasSemana[0]->format('Y-m-d');
$fechaFin    = $diasSemana[5]->format('Y-m-d');

// Parsear horario laboral: "09:00 - 15:00"
$horaInicioLaboral = '09:00';
$horaFinLaboral    = '15:00';
if ($datosGuia && !empty($datosGuia['horarios'])) {
    $partes = array_map('trim', explode('-', $datosGuia['horarios']));
    if (count($partes) === 2) {
        $horaInicioLaboral = $partes[0];
        $horaFinLaboral    = $partes[1];
    }
}

// Obtener recorridos de la semana desde el repositorio
// $recorridosPorSemana ya viene del controller (index.php lo inyecta)
// Si no existe, inicializar vacío
if (!isset($recorridosPorSemana)) {
    $recorridosPorSemana = [];
}

/**
 * Calcula hora inicio y fin de cada recorrido del día,
 * encadenándolos con 15 min de pausa entre ellos.
 * Retorna array de recorridos con 'hora_inicio' y 'hora_fin' añadidas.
 */
function calcularHorariosDelDia(array $recorridos, string $horaInicioBase): array
{
    $cursor = strtotime("1970-01-01 {$horaInicioBase}:00");
    foreach ($recorridos as &$rec) {
        $durSeg          = (int)$rec['duracion'] * 60;
        $rec['hora_inicio'] = date('H:i', $cursor);
        $rec['hora_fin']    = date('H:i', $cursor + $durSeg);
        $cursor += $durSeg + 15 * 60; // +15 min pausa
    }
    unset($rec);
    return $recorridos;
}

// Mapa nombres días ES
$nombreDiaEs = [
    'Monday'    => 'Lunes',
    'Tuesday'   => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday'  => 'Jueves',
    'Friday'    => 'Viernes',
    'Saturday'  => 'Sábado',
    'Sunday'    => 'Domingo',
];

// Meses ES
$meses = [
    1=>'ene',2=>'feb',3=>'mar',4=>'abr',5=>'may',6=>'jun',
    7=>'jul',8=>'ago',9=>'sep',10=>'oct',11=>'nov',12=>'dic'
];

$semanaLabel = $diasSemana[0]->format('j ') . $meses[(int)$diasSemana[0]->format('n')]
    . ' – ' . $diasSemana[5]->format('j ') . $meses[(int)$diasSemana[5]->format('n')]
    . ' ' . $diasSemana[0]->format('Y');

// Colores para los tipos de recorrido
function colorRecorrido(string $nombre): array {
    $colores = [
        'Felinos VIP'          => ['#fff3e0', '#e65100', '#ffcc80'],
        'Osos Andinos'         => ['#e8f5e9', '#2e7d32', '#a5d6a7'],
        'Cóndores'             => ['#e3f2fd', '#1565c0', '#90caf9'],
        'Acuario'              => ['#e0f7fa', '#006064', '#80deea'],
        'Recorrido General'    => ['#f3e5f5', '#6a1b9a', '#ce93d8'],
        'Recorrido Interactivo'=> ['#fce4ec', '#880e4f', '#f48fb1'],
    ];
    foreach ($colores as $key => $val) {
        if (stripos($nombre, explode(' ', $key)[0]) !== false) return $val;
    }
    return ['#f5f5f5', '#424242', '#bdbdbd'];
}
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
            --olive-lt: #bfb641;
            --teal:     #4a8c8e;
            --teal-lt:  #7eaeb0;
            --cream:    #faf7f2;
            --card:     #ffffff;
            --text:     #2d2d2d;
            --muted:    #777;
            --border:   #e8e0d4;
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
        header h1 { font-family: 'Playfair Display', serif; font-size: 1.8rem; }
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
        }
        .nav-tabs a:hover  { background: rgba(255,255,255,.22); }
        .nav-tabs a.active { background: var(--olive-lt); color: #222; font-weight: 700; }

        /* ── MAIN ── */
        main { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; }

        /* ── TOP BAR ── */
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border);
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--brown);
        }
        .semana-nav {
            display: flex;
            align-items: center;
            gap: .8rem;
        }
        .semana-label {
            font-size: .9rem;
            color: var(--muted);
            background: var(--card);
            padding: .4rem 1rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            font-weight: 600;
        }
        .btn-semana {
            padding: .45rem 1.1rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--brown);
            font-weight: 600;
            font-size: .88rem;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-semana:hover { background: var(--brown); color: white; }
        .btn-semana.disabled { opacity: .4; pointer-events: none; }

        /* ── CHIPS INFO ── */
        .horario-general {
            display: flex;
            gap: .8rem;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }
        .info-chip {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .6rem 1.1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }
        .chip-icon { font-size: 1.2rem; }
        .chip-label { font-size: .72rem; color: var(--muted); display: block; }
        .chip-val   { font-size: .95rem; font-weight: 700; color: var(--text); }

        /* ── AVISO ── */
        .aviso-readonly {
            display: flex;
            align-items: center;
            gap: .6rem;
            background: #fff8e6;
            border: 1px solid #f0d060;
            border-radius: 10px;
            padding: .7rem 1.1rem;
            margin-bottom: 1.5rem;
            font-size: .85rem;
            color: #7a5c00;
        }

        /* ── GRID CALENDARIO ── */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: .9rem;
        }
        @media (max-width: 960px)  { .calendar-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 560px)  { .calendar-grid { grid-template-columns: repeat(2, 1fr); } }

        /* ── DAY CARD ── */
        .day-card {
            background: var(--card);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0,0,0,.07);
            border: 2px solid var(--border);
            transition: transform .2s, box-shadow .2s, border-color .2s;
            display: flex;
            flex-direction: column;
        }
        .day-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
        }
        .day-card.today { border-color: var(--olive-lt); }
        .day-card.no-trabajo { opacity: .55; }

        .day-header {
            padding: .75rem 1rem .5rem;
            border-bottom: 1px solid var(--border);
            position: relative;
        }
        .day-name {
            font-weight: 700;
            font-size: .9rem;
            color: var(--brown);
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .day-date {
            font-size: .8rem;
            color: var(--muted);
        }
        .today-badge {
            position: absolute;
            top: .5rem; right: .6rem;
            background: var(--olive-lt);
            color: #333;
            font-size: .62rem;
            font-weight: 800;
            padding: .15rem .5rem;
            border-radius: 999px;
            letter-spacing: .5px;
        }

        /* Franja horario laboral */
        .turno-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .3rem;
            font-size: .72rem;
            color: white;
            background: var(--teal);
            padding: .3rem .5rem;
            font-weight: 600;
        }

        /* Recorridos del día */
        .day-body { padding: .6rem .7rem; flex: 1; display: flex; flex-direction: column; gap: .5rem; }

        .recorrido-slot {
            border-radius: 8px;
            padding: .55rem .7rem;
            border-left: 3px solid;
        }
        .slot-nombre {
            font-size: .82rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: .2rem;
        }
        .slot-hora {
            font-size: .78rem;
            font-weight: 700;
            opacity: .85;
        }
        .slot-personas {
            font-size: .72rem;
            opacity: .75;
            margin-top: .15rem;
        }
        .slot-tipo {
            display: inline-block;
            font-size: .62rem;
            font-weight: 700;
            padding: .1rem .4rem;
            border-radius: 999px;
            margin-top: .2rem;
            text-transform: uppercase;
            letter-spacing: .3px;
            opacity: .9;
        }

        .dia-libre {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            color: var(--muted);
            font-style: italic;
            padding: 1rem .5rem;
        }
        .sin-recorridos {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            color: #bbb;
            font-style: italic;
            padding: 1rem .5rem;
            text-align: center;
        }

        /* ── LEYENDA ── */
        .leyenda {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
            font-size: .82rem;
            color: var(--muted);
        }
        .leyenda-item { display: flex; align-items: center; gap: .5rem; }
        .leyenda-dot  { width: 11px; height: 11px; border-radius: 50%; flex-shrink: 0; }

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
    <div class="top-bar">
        <h2 class="section-title">Mis Horarios Semanales</h2>

        <div class="semana-nav">
            <!-- Botón "esta semana" solo si estamos en semana siguiente -->
            <a href="index.php?r=guias/horarios&semana=0"
               class="btn-semana <?= $semanaOffset === 0 ? 'disabled' : '' ?>">
               ← Esta semana
            </a>
            <span class="semana-label">📅 <?= $semanaLabel ?></span>
            <!-- Botón "siguiente" solo si estamos en semana actual -->
            <a href="index.php?r=guias/horarios&semana=1"
               class="btn-semana <?= $semanaOffset === 1 ? 'disabled' : '' ?>">
               Siguiente →
            </a>
        </div>
    </div>

    <!-- Aviso solo lectura -->
    <div class="aviso-readonly">
        🔒 Los horarios son de solo visualización. Para solicitar modificaciones, comunícate con el administrador.
    </div>

    <?php if (!$datosGuia): ?>
        <div style="text-align:center; padding:3rem; color:var(--muted); background:white;
                    border-radius:14px; border:2px dashed var(--border); font-size:1.05rem;">
            🗓️ No tienes horarios asignados actualmente.
        </div>
    <?php else: ?>

        <!-- Chips informativos -->
        <div class="horario-general">
            <div class="info-chip">
                <span class="chip-icon">🕘</span>
                <div>
                    <span class="chip-label">Turno entrada</span>
                    <span class="chip-val"><?= htmlspecialchars($horaInicioLaboral) ?></span>
                </div>
            </div>
            <div class="info-chip">
                <span class="chip-icon">🕒</span>
                <div>
                    <span class="chip-label">Turno salida</span>
                    <span class="chip-val"><?= htmlspecialchars($horaFinLaboral) ?></span>
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

        <!-- Calendario -->
        <div class="calendar-grid">
            <?php foreach ($diasSemana as $dia):
                $fechaKey  = $dia->format('Y-m-d');
                $esHoy     = $fechaKey === $hoy->format('Y-m-d');
                $enNombre  = $dia->format('l');

                // ¿Trabaja este día?
                $diasTrabajoStr = strtolower($datosGuia['dias_trabajo'] ?? '');
                $diaNombreEs    = strtolower($nombreDiaEs[$enNombre] ?? '');
                $trabaja = false;
                if (strpos($diasTrabajoStr, ' a ') !== false) {
                    preg_match('/(\w+)\s+a\s+(\w+)/', $diasTrabajoStr, $m);
                    if (count($m) === 3) {
                        $orden  = ['lunes','martes','miércoles','jueves','viernes','sábado','domingo'];
                        $desde  = array_search($m[1], $orden);
                        $hasta  = array_search($m[2], $orden);
                        $pos    = array_search($diaNombreEs, $orden);
                        $trabaja = ($desde !== false && $hasta !== false && $pos !== false && $pos >= $desde && $pos <= $hasta);
                    }
                } else {
                    $trabaja = strpos($diasTrabajoStr, $diaNombreEs) !== false;
                }

                // Recorridos del día con horarios calculados
                $recorridosDia = [];
                if (isset($recorridosPorSemana[$fechaKey])) {
                    $recorridosDia = calcularHorariosDelDia($recorridosPorSemana[$fechaKey], $horaInicioLaboral);
                }

                $clases = 'day-card' . ($esHoy ? ' today' : '') . (!$trabaja ? ' no-trabajo' : '');
            ?>
            <div class="<?= $clases ?>">
                <!-- Cabecera del día -->
                <div class="day-header">
                    <div class="day-name"><?= $nombreDiaEs[$enNombre] ?? $enNombre ?></div>
                    <div class="day-date"><?= $dia->format('d') . '/' . $dia->format('m') ?></div>
                    <?php if ($esHoy): ?>
                        <span class="today-badge">HOY</span>
                    <?php endif; ?>
                </div>

                <?php if ($trabaja): ?>
                    <!-- Barra turno -->
                    <div class="turno-bar">
                        ⏱ <?= htmlspecialchars($horaInicioLaboral) ?> – <?= htmlspecialchars($horaFinLaboral) ?>
                    </div>

                    <div class="day-body">
                        <?php if (empty($recorridosDia)): ?>
                            <div class="sin-recorridos">Sin recorridos<br>asignados</div>
                        <?php else: ?>
                            <?php foreach ($recorridosDia as $rec):
                                [$bg, $text, $border] = colorRecorrido($rec['nombre']);
                                $personas  = (int)$rec['personas_asignadas'];
                                $capacidad = (int)$rec['capacidad'];
                            ?>
                            <div class="recorrido-slot"
                                 style="background:<?= $bg ?>; border-color:<?= $border ?>; color:<?= $text ?>;">
                                <div class="slot-nombre"><?= htmlspecialchars($rec['nombre']) ?></div>
                                <div class="slot-hora">
                                    🕘 <?= $rec['hora_inicio'] ?> – <?= $rec['hora_fin'] ?>
                                </div>
                                <div class="slot-personas">
                                    👥 <?= $personas ?>/<?= $capacidad ?> personas
                                </div>
                                <span class="slot-tipo"
                                      style="background:<?= $border ?>; color:<?= $text ?>;">
                                    <?= htmlspecialchars($rec['tipo']) ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <div class="dia-libre">🌿 Día libre</div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Leyenda colores -->
        <div class="leyenda">
            <div class="leyenda-item"><div class="leyenda-dot" style="background:#e65100;"></div> Felinos VIP</div>
            <div class="leyenda-item"><div class="leyenda-dot" style="background:#2e7d32;"></div> Osos Andinos</div>
            <div class="leyenda-item"><div class="leyenda-dot" style="background:#1565c0;"></div> Cóndores</div>
            <div class="leyenda-item"><div class="leyenda-dot" style="background:#006064;"></div> Acuario</div>
            <div class="leyenda-item"><div class="leyenda-dot" style="background:#6a1b9a;"></div> Rec. General</div>
            <div class="leyenda-item"><div class="leyenda-dot" style="background:#880e4f;"></div> Rec. Interactivo</div>
        </div>

    <?php endif; ?>
</main>

<footer>
    <a href="index.php?r=guias/dashboard">← Volver a Mis Recorridos</a> &nbsp;·&nbsp;
    <a href="index.php?r=logout">Cerrar sesión</a>
</footer>

</body>
</html>
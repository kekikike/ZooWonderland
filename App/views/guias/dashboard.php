<?php
// app/Views/guias/dashboard.php
declare(strict_types=1);
$currentTab = 'recorridos';
require_once APP_PATH . '/Views/guias/partials/tabs.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Recorridos – ZooWonderland</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brown:     #a3712a;
            --brown-dk:  #7a521c;
            --olive:     #68672e;
            --olive-lt:  #bfb641;
            --teal:      #4a8c8e;
            --teal-lt:   #7eaeb0;
            --cream:     #faf7f2;
            --card:      #ffffff;
            --text:      #2d2d2d;
            --muted:     #777;
            --border:    #e8e0d4;
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
            letter-spacing: .5px;
        }
        header .user-badge {
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.35);
            padding: .45rem 1.1rem;
            border-radius: 999px;
            font-size: .9rem;
            font-weight: 600;
        }

        /* ── NAV TABS (styles referenced by tabs.php) ── */
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
            border-color: rgba(0,0,0,.1);
        }

        /* ── MAIN ── */
        main {
            max-width: 900px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            color: var(--brown);
            margin-bottom: 1.6rem;
            padding-bottom: .6rem;
            border-bottom: 2px solid var(--border);
        }

        /* ── RECORRIDO CARD ── */
        .recorrido-card {
            background: var(--card);
            border-radius: 14px;
            padding: 1.6rem 1.8rem;
            margin-bottom: 1.4rem;
            box-shadow: 0 3px 14px rgba(0,0,0,.07);
            border-left: 5px solid var(--teal);
            transition: transform .2s, box-shadow .2s;
        }
        .recorrido-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 26px rgba(0,0,0,.12);
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: .8rem;
            margin-bottom: 1rem;
        }

        .card-fecha {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .85rem;
            color: var(--muted);
            background: var(--cream);
            padding: .35rem .9rem;
            border-radius: 999px;
            border: 1px solid var(--border);
        }
        .card-fecha span { font-weight: 700; color: var(--brown); font-size: .95rem; }

        .badge-tipo {
            font-size: .78rem;
            font-weight: 700;
            padding: .3rem .9rem;
            border-radius: 999px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }
        .badge-guiado   { background: #e8f4e8; color: #2e7d32; border: 1px solid #a5d6a7; }
        .badge-noguiado { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }

        .card-nombre {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            color: var(--text);
            margin-bottom: 1rem;
        }

        .card-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: .8rem;
            margin-bottom: 1.2rem;
        }
        .meta-item {
            background: var(--cream);
            border-radius: 8px;
            padding: .7rem 1rem;
            border: 1px solid var(--border);
        }
        .meta-label {
            font-size: .75rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: .25rem;
        }
        .meta-value {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
        }

        /* Barra de ocupación */
        .ocupacion-bar {
            margin-bottom: 1.3rem;
        }
        .ocupacion-label {
            display: flex;
            justify-content: space-between;
            font-size: .82rem;
            color: var(--muted);
            margin-bottom: .35rem;
        }
        .bar-track {
            background: #e8e0d4;
            border-radius: 999px;
            height: 8px;
            overflow: hidden;
        }
        .bar-fill {
            height: 100%;
            border-radius: 999px;
            transition: width .5s ease;
        }
        .bar-low    { background: var(--teal-lt); }
        .bar-medium { background: var(--olive-lt); }
        .bar-high   { background: #e57373; }

        .btn-detalle {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1.4rem;
            background: var(--teal);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: .9rem;
            transition: background .2s;
        }
        .btn-detalle:hover { background: var(--teal-lt); color: #222; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: var(--card);
            border-radius: 14px;
            border: 2px dashed var(--border);
            color: var(--muted);
            font-size: 1.1rem;
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
    <h2 class="section-title">Mis Recorridos Asignados</h2>

    <?php if (empty($recorridosAsignados)): ?>
        <div class="empty-state">
            <p>🗓️ No tienes recorridos asignados en este momento.</p>
        </div>
    <?php else: ?>
        <?php foreach ($recorridosAsignados as $rec):
            $personas   = (int)$rec['personas_asignadas'];
            $capacidad  = (int)$rec['capacidad'];
            $pct        = $capacidad > 0 ? round($personas / $capacidad * 100) : 0;
            $barClass   = $pct < 50 ? 'bar-low' : ($pct < 80 ? 'bar-medium' : 'bar-high');
            $esGuiado   = strtolower($rec['tipo']) === 'guiado';

            // Calcular hora fin a partir de la duración (minutos)
            $horaInicio = '09:00'; // horario base definido en la BD para todos los guías
            $durMin     = (int)$rec['duracion'];
            $horaFinTs  = strtotime("1970-01-01 {$horaInicio}:00") + $durMin * 60;
            $horaFin    = date('H:i', $horaFinTs);

            $fechaFmt = date('d/m/Y', strtotime($rec['fecha_asignacion']));
            $diaSemana = ['Sunday'=>'Domingo','Monday'=>'Lunes','Tuesday'=>'Martes',
                          'Wednesday'=>'Miércoles','Thursday'=>'Jueves',
                          'Friday'=>'Viernes','Saturday'=>'Sábado'];
            $diaNombre = $diaSemana[date('l', strtotime($rec['fecha_asignacion']))] ?? '';
        ?>
        <div class="recorrido-card">
            <div class="card-top">
                <div class="card-fecha">
                    📅 <span><?= $diaNombre ?>, <?= $fechaFmt ?></span>
                </div>
                <span class="badge-tipo <?= $esGuiado ? 'badge-guiado' : 'badge-noguiado' ?>">
                    <?= htmlspecialchars($rec['tipo']) ?>
                </span>
            </div>

            <div class="card-nombre"><?= htmlspecialchars($rec['nombre']) ?></div>

            <div class="card-meta">
                <div class="meta-item">
                    <div class="meta-label">⏱ Hora inicio</div>
                    <div class="meta-value"><?= $horaInicio ?></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">⏱ Hora fin (aprox.)</div>
                    <div class="meta-value"><?= $horaFin ?></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">⏳ Duración</div>
                    <div class="meta-value"><?= $durMin ?> min</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">💰 Precio</div>
                    <div class="meta-value">Bs <?= number_format((float)$rec['precio'], 2) ?></div>
                </div>
            </div>

            <div class="ocupacion-bar">
                <div class="ocupacion-label">
                    <span>Personas inscritas</span>
                    <strong><?= $personas ?> / <?= $capacidad ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill <?= $barClass ?>" style="width:<?= $pct ?>%"></div>
                </div>
            </div>

            <a href="index.php?r=guias/detalle-recorrido&id=<?= $rec['id_recorrido'] ?>" class="btn-detalle">
                🔍 Ver áreas del recorrido
            </a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer>
    <a href="index.php">← Inicio público</a> &nbsp;·&nbsp;
    <a href="index.php?r=logout">Cerrar sesión</a>
</footer>

</body>
</html>

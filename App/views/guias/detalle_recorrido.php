<?php
// app/Views/guias/detalle_recorrido.php
declare(strict_types=1);
$currentTab = 'recorridos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Recorrido – ZooWonderland</title>
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
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            min-height: 100vh;
        }

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
        .nav-tabs a:hover  { background: rgba(255,255,255,.22); }
        .nav-tabs a.active { background: var(--olive-lt); color: #222; font-weight: 700; }

        main { max-width: 860px; margin: 2.5rem auto; padding: 0 1.5rem; }

        /* ── BREADCRUMB ── */
        .breadcrumb {
            font-size: .85rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
        }
        .breadcrumb a { color: var(--brown); text-decoration: none; font-weight: 600; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb span { margin: 0 .4rem; }

        /* ── HERO CARD ── */
        .hero-card {
            background: var(--card);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.8rem;
            box-shadow: 0 4px 20px rgba(0,0,0,.09);
            border-left: 6px solid var(--teal);
        }
        .hero-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.2rem;
        }
        .hero-nombre {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--text);
        }
        .badge-tipo {
            font-size: .78rem; font-weight: 700;
            padding: .3rem .9rem; border-radius: 999px;
            letter-spacing: .5px; text-transform: uppercase;
        }
        .badge-guiado   { background:#e8f4e8; color:#2e7d32; border:1px solid #a5d6a7; }
        .badge-noguiado { background:#fff3e0; color:#e65100; border:1px solid #ffcc80; }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: .9rem;
            margin-top: 1rem;
        }
        .meta-item {
            background: var(--cream);
            border-radius: 10px;
            padding: .8rem 1rem;
            border: 1px solid var(--border);
        }
        .meta-label { font-size: .72rem; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem; }
        .meta-value { font-size: 1.05rem; font-weight: 700; color: var(--text); }

        /* Barra ocupación */
        .ocupacion-wrap { margin-top: 1.2rem; }
        .ocu-label { display:flex; justify-content:space-between; font-size:.82rem; color:var(--muted); margin-bottom:.35rem; }
        .bar-track { background:#e8e0d4; border-radius:999px; height:10px; overflow:hidden; }
        .bar-fill  { height:100%; border-radius:999px; transition:width .5s; }
        .bar-low    { background: var(--teal-lt); }
        .bar-medium { background: var(--olive-lt); }
        .bar-high   { background: #e57373; }

        /* ── ÁREAS ── */
        .areas-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--brown);
            margin-bottom: 1.2rem;
            padding-bottom: .5rem;
            border-bottom: 2px solid var(--border);
        }
        .areas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
        }
        .area-card {
            background: var(--card);
            border-radius: 12px;
            padding: 1.1rem 1.3rem;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            border: 2px solid var(--border);
            position: relative;
            transition: transform .2s, border-color .2s;
        }
        .area-card:hover {
            transform: translateY(-3px);
            border-color: var(--teal-lt);
        }
        .area-nombre {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
            margin-bottom: .4rem;
        }
        .area-desc {
            font-size: .83rem;
            color: var(--muted);
            line-height: 1.4;
        }
        .badge-rest {
            display: inline-block;
            font-size: .7rem;
            font-weight: 700;
            background: #fdecea;
            color: #c62828;
            border: 1px solid #ef9a9a;
            padding: .15rem .6rem;
            border-radius: 999px;
            margin-top: .5rem;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .badge-libre {
            display: inline-block;
            font-size: .7rem;
            font-weight: 700;
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
            padding: .15rem .6rem;
            border-radius: 999px;
            margin-top: .5rem;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .empty-areas {
            color: var(--muted);
            font-style: italic;
            padding: 1rem 0;
        }

        /* ── BACK BTN ── */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.4rem;
            background: var(--teal);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: .9rem;
            margin-top: 2rem;
            transition: background .2s;
        }
        .btn-back:hover { background: var(--teal-lt); color: #222; }

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

<!-- Nav tabs manual (sin tabs.php para no marcar activo por path) -->
<nav class="nav-tabs">
    <a href="index.php?r=guias/dashboard" class="active">Mis Recorridos</a>
    <a href="index.php?r=guias/horarios">Mis Horarios</a>
</nav>

<main>
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="index.php?r=guias/dashboard">Mis Recorridos</a>
        <span>›</span>
        Detalle: <?= htmlspecialchars($recorrido['nombre']) ?>
    </div>

    <!-- Hero card con datos del recorrido -->
    <?php
        $personas  = (int)$recorrido['personas_asignadas'];
        $capacidad = (int)$recorrido['capacidad'];
        $pct       = $capacidad > 0 ? round($personas / $capacidad * 100) : 0;
        $barClass  = $pct < 50 ? 'bar-low' : ($pct < 80 ? 'bar-medium' : 'bar-high');
        $esGuiado  = strtolower($recorrido['tipo']) === 'guiado';

        $fechaFmt  = date('d/m/Y', strtotime($recorrido['fecha_asignacion']));
        $diaSemana = ['Sunday'=>'Domingo','Monday'=>'Lunes','Tuesday'=>'Martes',
                      'Wednesday'=>'Miércoles','Thursday'=>'Jueves',
                      'Friday'=>'Viernes','Saturday'=>'Sábado'];
        $diaNombre = $diaSemana[date('l', strtotime($recorrido['fecha_asignacion']))] ?? '';

        $horaInicio = '09:00';
        $durMin     = (int)$recorrido['duracion'];
        $horaFin    = date('H:i', strtotime("1970-01-01 {$horaInicio}:00") + $durMin * 60);
    ?>
    <div class="hero-card">
        <div class="hero-top">
            <div class="hero-nombre"><?= htmlspecialchars($recorrido['nombre']) ?></div>
            <span class="badge-tipo <?= $esGuiado ? 'badge-guiado' : 'badge-noguiado' ?>">
                <?= htmlspecialchars($recorrido['tipo']) ?>
            </span>
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <div class="meta-label">📅 Fecha asignada</div>
                <div class="meta-value"><?= $diaNombre ?>, <?= $fechaFmt ?></div>
            </div>
            <div class="meta-item">
                <div class="meta-label">⏱ Inicio</div>
                <div class="meta-value"><?= $horaInicio ?></div>
            </div>
            <div class="meta-item">
                <div class="meta-label">⏱ Fin (aprox.)</div>
                <div class="meta-value"><?= $horaFin ?></div>
            </div>
            <div class="meta-item">
                <div class="meta-label">⏳ Duración</div>
                <div class="meta-value"><?= $durMin ?> min</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">💰 Precio</div>
                <div class="meta-value">Bs <?= number_format((float)$recorrido['precio'], 2) ?></div>
            </div>
        </div>

        <div class="ocupacion-wrap">
            <div class="ocu-label">
                <span>Personas inscritas</span>
                <strong><?= $personas ?> / <?= $capacidad ?></strong>
            </div>
            <div class="bar-track">
                <div class="bar-fill <?= $barClass ?>" style="width:<?= $pct ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Áreas del recorrido -->
    <div class="areas-section">
        <h3>🗺️ Áreas que se visitarán</h3>

        <?php if (empty($areas)): ?>
            <p class="empty-areas">Este recorrido no tiene áreas asignadas.</p>
        <?php else: ?>
            <div class="areas-grid">
                <?php foreach ($areas as $area): ?>
                <div class="area-card">
                    <div class="area-nombre">📍 <?= htmlspecialchars($area['nombre']) ?></div>
                    <?php if (!empty($area['descripcion'])): ?>
                        <div class="area-desc"><?= htmlspecialchars($area['descripcion']) ?></div>
                    <?php endif; ?>
                    <?php if ((int)$area['restringida'] === 1): ?>
                        <span class="badge-rest">🔒 Acceso restringido</span>
                    <?php else: ?>
                        <span class="badge-libre">✅ Acceso libre</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <a href="index.php?r=guias/dashboard" class="btn-back">← Volver a mis recorridos</a>
</main>

<footer>
    <a href="index.php">← Inicio público</a> &nbsp;·&nbsp;
    <a href="index.php?r=logout">Cerrar sesión</a>
</footer>

</body>
</html>

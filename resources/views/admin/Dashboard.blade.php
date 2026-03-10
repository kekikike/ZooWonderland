{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin · ZooWonderland</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --verde:      #2e7d32;
            --verde-deep: #1b5e20;
            --verde-vivo: #4caf50;
            --amarillo:   #ffca28;
            --naranja:    #f57c00;

            --bg:      #000;
            --surface: #0d0d0d;
            --card:    #131313;
            --hover:   #1c1c1c;
            --borde:   rgba(255,255,255,0.08);
            --borde-v: rgba(76,175,80,0.3);
            --t1:      #f2f2f2;
            --t2:      #888;
            --t3:      #444;

            --tr: all 0.22s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Monserat', sans-serif;
            background: var(--bg);
            color: var(--t1);
            min-height: 100vh;
            display: flex;
            font-size: 15px; /* base más generosa */
        }

        /* ══ SIDEBAR ══════════════════════════════════════ */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 270px;
            background: var(--verde-deep);
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 6px 0 40px rgba(0,0,0,0.6);
        }

        .sb-head {
            padding: 2rem 1.8rem 1.6rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sb-logo {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--amarillo);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .sb-badge {
            font-size: 0.65rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.75);
            padding: 0.25rem 0.6rem;
            border-radius: 5px;
            align-self: center;
        }

        .sb-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0.8rem 1.1rem;
            scrollbar-width: none;
        }
        .sb-nav::-webkit-scrollbar { display: none; }

        .sb-section {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.3);
            padding: 1.4rem 0.8rem 0.6rem;
            font-weight: 600;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.85rem 1.1rem;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 10px;
            transition: var(--tr);
            margin-bottom: 0.2rem;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.09);
            color: #fff;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(255,202,40,0.14);
            color: var(--amarillo);
            font-weight: 600;
        }

        .nav-link.active i { color: var(--amarillo); }

        .sb-foot {
            padding: 1.4rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-block {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.9rem 1rem;
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            margin-bottom: 0.8rem;
        }

        .user-av {
            width: 38px; height: 38px;
            background: var(--verde);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; color: #fff; flex-shrink: 0;
        }

        .user-name-sb {
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-sb {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.4);
            margin-top: 0.1rem;
        }

        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 0.6rem;
            width: 100%; padding: 0.75rem;
            background: rgba(245,124,0,0.12);
            color: #ffb74d;
            border: 1px solid rgba(245,124,0,0.22);
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            transition: var(--tr);
        }

        .btn-logout:hover {
            background: rgba(245,124,0,0.25);
            color: #ffd580;
        }

        /* ══ MAIN ═════════════════════════════════════════ */
        .main {
            margin-left: 270px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* ══ TOPBAR ═══════════════════════════════════════ */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--borde);
            padding: 1.3rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .tb-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--t1);
            letter-spacing: -0.4px;
        }

        .tb-sub {
            font-size: 0.8rem;
            color: var(--t2);
            margin-top: 0.2rem;
        }

        .tb-right { display: flex; align-items: center; gap: 1rem; }

        .pill {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--t2);
            background: var(--card);
            border: 1px solid var(--borde);
            padding: 0.45rem 1rem;
            border-radius: 20px;
        }

        .dot {
            width: 7px; height: 7px;
            background: var(--verde-vivo);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(76,175,80,.5); }
            50%      { box-shadow: 0 0 0 5px rgba(76,175,80,0); }
        }

        .btn-sm {
            display: inline-flex; align-items: center; gap: 0.45rem;
            font-size: 0.82rem; font-weight: 600;
            padding: 0.45rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: var(--tr);
            border: 1px solid var(--borde);
            background: var(--card);
            color: var(--t2);
        }
        .btn-sm:hover { color: var(--t1); border-color: var(--t3); background: var(--hover); }

        /* ══ BODY ═════════════════════════════════════════ */
        .body { padding: 2.5rem; flex: 1; }

        /* ══ STATS ════════════════════════════════════════ */
        .stats {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1px;
            background: var(--borde);
            border: 1px solid var(--borde);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 2.5rem;
        }

        .sc {
            background: var(--card);
            padding: 1.8rem 1.5rem 1.5rem;
            transition: var(--tr);
            position: relative;
            overflow: hidden;
            animation: fadeUp .45s ease both;
        }

        .sc::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: var(--ac, var(--verde-vivo));
        }

        .sc:hover { background: var(--hover); }

        .sc:nth-child(1) { --ac: #ef5350; animation-delay: .04s; }
        .sc:nth-child(2) { --ac: #42a5f5; animation-delay: .08s; }
        .sc:nth-child(3) { --ac: #66bb6a; animation-delay: .12s; }
        .sc:nth-child(4) { --ac: var(--amarillo); animation-delay: .16s; }
        .sc:nth-child(5) { --ac: #ce93d8; animation-delay: .20s; }
        .sc:nth-child(6) { --ac: #4dd0e1; animation-delay: .24s; }

        .sc-top {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 1.1rem;
        }

        .sc-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            color: var(--t2);
            font-weight: 600;
            line-height: 1.4;
        }

        .sc-icon {
            font-size: 1rem;
            color: var(--ac, var(--verde-vivo));
            opacity: 0.7;
        }

        .sc-val {
            font-family: 'Syne', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--t1);
            letter-spacing: -2px;
            line-height: 1;
        }

        .sc-val.sm {
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }

        /* ══ SECTION ══════════════════════════════════════ */
        .section {
            background: var(--card);
            border: 1px solid var(--borde);
            border-radius: 16px;
            overflow: hidden;
            animation: fadeUp .45s ease .28s both;
        }

        .sec-head {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.4rem 2rem;
            border-bottom: 1px solid var(--borde);
        }

        .sec-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--t1);
            display: flex; align-items: center; gap: 0.65rem;
        }

        .sec-title i { color: var(--verde-vivo); font-size: 1rem; }

        .badge {
            font-size: 0.72rem;
            font-weight: 700;
            background: rgba(76,175,80,0.1);
            color: var(--verde-vivo);
            border: 1px solid rgba(76,175,80,0.22);
            padding: 0.2rem 0.65rem;
            border-radius: 20px;
        }

        .sec-body { padding: 2rem; }

        /* ══ REC GRID ═════════════════════════════════════ */
        .rec-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1px;
            background: var(--borde);
            border: 1px solid var(--borde);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .rc {
            background: var(--surface);
            transition: var(--tr);
        }
        .rc:hover { background: var(--hover); }

        .rc-stripe { height: 3px; background: linear-gradient(90deg, var(--verde), var(--amarillo)); }

        .rc-body { padding: 1.3rem 1.4rem; }

        .rc-name {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--t1);
            margin-bottom: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rc-row {
            display: flex; justify-content: space-between;
            font-size: 0.85rem;
            padding: 0.38rem 0;
            border-bottom: 1px solid var(--borde);
            color: var(--t2);
        }
        .rc-row:last-child { border-bottom: none; }
        .rc-row strong { color: var(--t1); font-weight: 500; }

        /* ══ BUTTONS ══════════════════════════════════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.7rem 1.4rem;
            border-radius: 9px;
            font-size: 0.88rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            transition: var(--tr);
            border: none; cursor: pointer;
        }

        .btn-green { background: var(--verde); color: #fff; }
        .btn-green:hover { background: var(--verde-vivo); }

        .btn-outline {
            background: transparent;
            color: var(--t2);
            border: 1px solid var(--borde);
        }
        .btn-outline:hover { background: var(--hover); color: var(--t1); border-color: var(--t3); }

        /* ══ EMPTY ════════════════════════════════════════ */
        .empty { text-align: center; padding: 4rem; color: var(--t3); }
        .empty i { font-size: 2.5rem; margin-bottom: 1rem; display: block; opacity: 0.2; }
        .empty p { font-size: 0.95rem; }

        /* ══ FOOTER ═══════════════════════════════════════ */
        footer {
            padding: 1.2rem 2.5rem;
            border-top: 1px solid var(--borde);
            font-size: 0.8rem;
            color: var(--t3);
        }

        /* ══ ANIMATIONS ═══════════════════════════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══ RESPONSIVE ═══════════════════════════════════ */
        @media (max-width: 1200px) {
            .stats { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 900px) {
            .sidebar { width: 230px; }
            .main { margin-left: 230px; }
        }
        @media (max-width: 680px) {
            .sidebar { position: relative; width: 100%; height: auto; box-shadow: none; }
            .main { margin-left: 0; }
            .sb-nav { display: flex; flex-wrap: wrap; }
            .nav-link { flex: 1 1 45%; }
            .stats { grid-template-columns: repeat(2, 1fr); }
            .body { padding: 1.2rem; }
        }
    </style>
</head>
<body>

<!-- ── SIDEBAR ──────────────────────────────────────── -->
<aside class="sidebar">
    <div class="sb-head">
        <a href="/admin/dashboard" class="sb-logo">
            🍃 ZooWonderland
        </a>
    </div>

    <div class="sb-nav">
        <span class="sb-section">Principal</span>
        <a href="/admin/dashboard" class="nav-link active">
            <i class="fas fa-gauge-high"></i> Dashboard
        </a>
        <a href="/admin/recorridos" class="nav-link">
            <i class="fas fa-route"></i> Recorridos
        </a>
        <a href="/admin/areas" class="nav-link">
            <i class="fas fa-map"></i> Áreas
        </a>
        <a href="/admin/animales" class="nav-link">
            <i class="fas fa-paw"></i> Animales
        </a>

        <span class="sb-section">Operaciones</span>
        <a href="/admin/usuarios" class="nav-link">
            <i class="fas fa-users"></i> Usuarios
        </a>
        <a href="/admin/asignaciones" class="nav-link">
            <i class="fas fa-user-tie"></i> Asignaciones
        </a>
        <a href="/admin/eventos" class="nav-link">
            <i class="fas fa-calendar-days"></i> Eventos
        </a>

        <span class="sb-section">Análisis</span>
        <a href="/admin/reportes" class="nav-link">
            <i class="fas fa-chart-line"></i> Reportes
        </a>
    </div>

    <div class="sb-foot">
        <div class="user-block">
            <div class="user-av"><i class="fas fa-user-shield"></i></div>
            <div style="overflow:hidden">
                <div class="user-name-sb">{{ $user->getNombreParaMostrar() }}</div>
                <div class="user-role-sb">Administrador</div>
            </div>
        </div>
        <a href="/logout" class="btn-logout">
            <i class="fas fa-power-off"></i> Cerrar sesión
        </a>
    </div>
</aside>

<!-- ── MAIN ─────────────────────────────────────────── -->
<div class="main">

    <div class="topbar">
        <div>
            <div class="tb-title">Panel de Control</div>
            <div class="tb-sub">{{ date('l, d \d\e F \d\e Y · H:i') }}</div>
        </div>
        <div class="tb-right">
            <div class="pill"><span class="dot"></span> Sistema activo</div>
            <a href="/" class="btn-sm" target="_blank">
                <i class="fas fa-arrow-up-right-from-square"></i> Ver sitio
            </a>
        </div>
    </div>

    <div class="body">

        <!-- Stats -->
        <div class="stats">
            <div class="sc">
                <div class="sc-top">
                    <span class="sc-label">Recorridos</span>
                    <i class="fas fa-route sc-icon"></i>
                </div>
                <div class="sc-val">{{ $totalRecorridos }}</div>
            </div>
            <div class="sc">
                <div class="sc-top">
                    <span class="sc-label">Áreas</span>
                    <i class="fas fa-map sc-icon"></i>
                </div>
                <div class="sc-val">{{ $totalAreas }}</div>
            </div>
            <div class="sc">
                <div class="sc-top">
                    <span class="sc-label">Animales</span>
                    <i class="fas fa-paw sc-icon"></i>
                </div>
                <div class="sc-val">{{ $totalAnimales }}</div>
            </div>
            <div class="sc">
                <div class="sc-top">
                    <span class="sc-label">Reservas activas</span>
                    <i class="fas fa-ticket sc-icon"></i>
                </div>
                <div class="sc-val">{{ $totalReservas }}</div>
            </div>
            <div class="sc">
                <div class="sc-top">
                    <span class="sc-label">Ingresos</span>
                    <i class="fas fa-coins sc-icon"></i>
                </div>
                <div class="sc-val sm">Bs. {{ number_format($totalIngresos, 0) }}</div>
            </div>
            <div class="sc">
                <div class="sc-top">
                    <span class="sc-label">Guías</span>
                    <i class="fas fa-user-tie sc-icon"></i>
                </div>
                <div class="sc-val">{{ $totalGuias }}</div>
            </div>
        </div>

        <!-- Recorridos -->
        <div class="section">
            <div class="sec-head">
                <div class="sec-title">
                    <i class="fas fa-route"></i>
                    Recorridos registrados
                    <span class="badge">{{ $totalRecorridos }}</span>
                </div>
                <a href="/admin/recorridos" class="btn btn-outline">
                    <i class="fas fa-table-list"></i> Ver todos
                </a>
            </div>

            <div class="sec-body">
                @if($recorridos->isEmpty())
                    <div class="empty">
                        <i class="fas fa-compass"></i>
                        <p>No hay recorridos registrados aún.</p>
                    </div>
                @else
                    <div class="rec-grid">
                        @foreach($recorridos as $rec)
                            <div class="rc">
                                <div class="rc-stripe"></div>
                                <div class="rc-body">
                                    <div class="rc-name">{{ $rec->nombre }}</div>
                                    <div class="rc-row">
                                        <span>Tipo</span>
                                        <strong>{{ $rec->tipo ?? '—' }}</strong>
                                    </div>
                                    <div class="rc-row">
                                        <span>Precio</span>
                                        <strong>Bs. {{ number_format($rec->precio ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="rc-row">
                                        <span>Duración</span>
                                        <strong>{{ $rec->duracion ?? '?' }} min</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="/admin/recorridos/crear" class="btn btn-green">
                        <i class="fas fa-plus"></i> Nuevo recorrido
                    </a>
                @endif
            </div>
        </div>

    </div>

    <footer>&copy; {{ date('Y') }} ZooWonderland — Panel Administrativo</footer>
</div>

</body>
</html>
@php
$coloresRecorrido = [
    'felinos'     => ['bg' => '#fff3e0', 'text' => '#e65100', 'border' => '#ffcc80'],
    'osos'        => ['bg' => '#e8f5e9', 'text' => '#2e7d32', 'border' => '#a5d6a7'],
    'cóndor'      => ['bg' => '#e3f2fd', 'text' => '#1565c0', 'border' => '#90caf9'],
    'condor'      => ['bg' => '#e3f2fd', 'text' => '#1565c0', 'border' => '#90caf9'],
    'acuario'     => ['bg' => '#e0f7fa', 'text' => '#006064', 'border' => '#80deea'],
    'general'     => ['bg' => '#f3e5f5', 'text' => '#6a1b9a', 'border' => '#ce93d8'],
    'interactivo' => ['bg' => '#fce4ec', 'text' => '#880e4f', 'border' => '#f48fb1'],
];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - ZooWonderland</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --selva-dark:    #0a3d1f;
            --selva-med:     #1b5e20;
            --selva-light:   #2e7d32;
            --jungle-gold:   #ffb300;
            --jungle-orange: #ff8c00;
            --sky-blue:      #1e88e5;
            --gris-bg:       #f0f7f4;
            --blanco:        #ffffff;
            --shadow-lg:     0 20px 60px rgba(0,0,0,0.15);
            --shadow-md:     0 10px 30px rgba(0,0,0,0.1);
            --trans:         all 0.3s cubic-bezier(0.34,1.56,0.64,1);
            --error:         #c62828;
        }
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,#f0f7f4 0%,#e8f5e9 50%,#f1f8e9 100%);
            color: #333;
            min-height: 100vh;
        }
        h1,h2,h3 { font-family:'Playfair Display',serif; }

        header {
            background: linear-gradient(135deg,var(--selva-dark) 0%,var(--selva-med) 50%,var(--jungle-gold) 100%);
            padding: 1.5rem 5%;
            box-shadow: var(--shadow-lg);
            border-bottom: 4px solid var(--jungle-gold);
            position: sticky; top:0; z-index:1000;
        }
        nav { display:flex; justify-content:space-between; align-items:center; max-width:1400px; margin:0 auto; gap:3rem; }
        .logo { font-size:1.6rem; font-weight:800; color:var(--jungle-gold); text-shadow:2px 2px 4px rgba(0,0,0,.3); text-decoration:none; letter-spacing:-1px; white-space:nowrap; }
        .menu { display:flex; gap:2.5rem; align-items:center; flex-grow:1; }
        .menu a { color:white; text-decoration:none; font-weight:600; font-size:.95rem; transition:var(--trans); display:flex; align-items:center; gap:.6rem; }
        .menu a:hover, .menu a.active { color:var(--jungle-gold); transform:translateY(-2px); }
        .user-area { display:flex; align-items:center; gap:1.5rem; }
        .user-name { background:rgba(255,255,255,.15); padding:.6rem 1.2rem; border-radius:25px; color:white; font-weight:600; font-size:.9rem; }
        .logout-btn { background:var(--jungle-orange); color:white; padding:.7rem 1.5rem; border-radius:25px; border:none; cursor:pointer; font-weight:600; transition:var(--trans); box-shadow:0 4px 15px rgba(255,140,0,.3); text-decoration:none; display:inline-flex; align-items:center; gap:.5rem; }
        .logout-btn:hover { background:#e67e00; transform:translateY(-2px); }

        main { max-width:1400px; margin:0 auto; padding:3rem 5%; }

        .page-header { margin-bottom:2rem; animation: slideInDown .6s ease; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
        .page-header-text h1 { font-size:2.5rem; background: linear-gradient(135deg,var(--selva-dark),var(--jungle-gold)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; font-weight:800; }
        .page-header-text p { color:#666; font-size:1rem; font-weight:300; margin-top:.3rem; }

        .btn-nuevo {
            background: linear-gradient(135deg, var(--jungle-gold), var(--jungle-orange));
            color: white; padding: .85rem 2rem; border-radius: 12px; border: none;
            cursor: pointer; font-weight: 700; font-size: 1rem;
            display: inline-flex; align-items: center; gap: .6rem;
            font-family: 'Poppins', sans-serif; transition: var(--trans);
            box-shadow: 0 4px 20px rgba(255,179,0,.4); text-decoration: none;
        }
        .btn-nuevo:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(255,140,0,.5); }

        .flash { padding:1rem 1.5rem; border-radius:12px; margin-bottom:1.5rem; font-weight:600; display:flex; align-items:center; gap:.8rem; animation: fadeInUp .4s ease; }
        .flash.ok      { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
        .flash.success { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
        .flash.error   { background:#fdecea; color:#c62828; border:1px solid #ef9a9a; }

        .filtros-card { background:var(--blanco); border-radius:16px; padding:1.8rem 2rem; margin-bottom:2rem; box-shadow:var(--shadow-md); animation: fadeInUp .7s ease; }
        .filtros-card h3 { font-size:1.1rem; color:var(--selva-dark); margin-bottom:1.2rem; display:flex; align-items:center; gap:.6rem; }
        .filtros-grid { display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:1rem; align-items:end; }
        @media(max-width:900px){ .filtros-grid{ grid-template-columns:1fr 1fr; } }
        @media(max-width:560px){ .filtros-grid{ grid-template-columns:1fr; } }

        .form-group { display:flex; flex-direction:column; gap:.4rem; }
        .form-group label { font-size:.8rem; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:.5px; }
        .form-control { padding:.7rem 1rem; border:2px solid #e0e0e0; border-radius:10px; font-family:'Poppins',sans-serif; font-size:.9rem; transition:border-color .2s; outline:none; background:white; }
        .form-control:focus { border-color:var(--jungle-gold); }
        .form-control.field-error { border-color: var(--error) !important; }

        .btn-filtrar { background:linear-gradient(135deg,var(--selva-light),var(--jungle-gold)); color:white; padding:.75rem 1.5rem; border-radius:10px; border:none; cursor:pointer; font-weight:700; display:inline-flex; align-items:center; gap:.5rem; font-family:'Poppins',sans-serif; font-size:.9rem; transition:var(--trans); white-space:nowrap; box-shadow:0 4px 15px rgba(46,125,50,.3); }
        .btn-filtrar:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(255,179,0,.4); }
        .btn-limpiar { background:var(--gris-bg); color:var(--selva-dark); padding:.75rem 1.2rem; border-radius:10px; border:1px solid #ccc; cursor:pointer; font-weight:600; font-family:'Poppins',sans-serif; font-size:.9rem; text-decoration:none; display:inline-flex; align-items:center; gap:.5rem; transition:var(--trans); white-space:nowrap; }
        .btn-limpiar:hover { background:#ddd; }

        .tabla-card { background:var(--blanco); border-radius:16px; box-shadow:var(--shadow-md); overflow:hidden; animation: fadeInUp .9s ease; }
        .tabla-header { padding:1.5rem 2rem; border-bottom:3px solid var(--gris-bg); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
        .tabla-header h2 { font-size:1.6rem; color:var(--selva-dark); display:flex; align-items:center; gap:.7rem; }
        .tabla-header h2 i { color:var(--jungle-gold); }
        .contador-badge { background:var(--gris-bg); color:var(--selva-dark); padding:.35rem .9rem; border-radius:999px; font-size:.85rem; font-weight:700; }

        .tabla-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        thead { background:linear-gradient(90deg,#f5f5f5,#efefef); border-bottom:3px solid var(--jungle-gold); }
        th { padding:1.1rem 1.2rem; text-align:left; font-weight:700; color:var(--selva-dark); text-transform:uppercase; font-size:.8rem; letter-spacing:1px; }
        td { padding:1rem 1.2rem; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
        tbody tr { transition:background .2s; }
        tbody tr:hover { background:linear-gradient(90deg,transparent,rgba(255,179,0,.04),transparent); }
        tbody tr.inactivo td { opacity:.55; }
        tbody tr.inactivo { background:#fafafa; }

        .badge { display:inline-flex; align-items:center; gap:.3rem; padding:.3rem .8rem; border-radius:999px; font-size:.75rem; font-weight:700; letter-spacing:.3px; }
        .badge-admin    { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
        .badge-guia     { background:#e3f2fd; color:#1565c0; border:1px solid #90caf9; }
        .badge-cliente  { background:#fff3e0; color:#e65100; border:1px solid #ffcc80; }
        .badge-activo   { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
        .badge-inactivo { background:#fdecea; color:#c62828; border:1px solid #ef9a9a; }

        .recorrido-tags { display:flex; flex-wrap:wrap; gap:.3rem; }
        .rtag { padding:.2rem .6rem; border-radius:999px; font-size:.7rem; font-weight:700; border:1px solid; }

        .acciones { display:flex; gap:.6rem; align-items:center; flex-wrap:wrap; }
        .btn-editar { color:var(--selva-light); background:rgba(46,125,50,.1); padding:.45rem .9rem; border-radius:8px; text-decoration:none; font-weight:600; font-size:.82rem; display:inline-flex; align-items:center; gap:.4rem; transition:var(--trans); }
        .btn-editar:hover { background:rgba(46,125,50,.25); transform:translateX(2px); }
        .btn-toggle { padding:.45rem .9rem; border-radius:8px; border:none; cursor:pointer; font-weight:600; font-size:.82rem; display:inline-flex; align-items:center; gap:.4rem; font-family:'Poppins',sans-serif; transition:var(--trans); }
        .btn-desactivar { color:#ff6f6f; background:rgba(255,111,111,.1); }
        .btn-desactivar:hover { background:rgba(255,111,111,.25); transform:translateX(2px); }
        .btn-activar { color:#2e7d32; background:rgba(46,125,50,.1); }
        .btn-activar:hover { background:rgba(46,125,50,.25); transform:translateX(2px); }

        .empty-state { text-align:center; padding:4rem 2rem; color:#bbb; }
        .empty-state i { font-size:4rem; margin-bottom:1rem; opacity:.4; }
        .empty-state p { font-size:1.05rem; color:#999; }

        footer { background:var(--selva-dark); color:white; text-align:center; padding:2rem; margin-top:3rem; font-size:.9rem; }

        /* ── MODAL ───────────────────────────────────────────── */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 9000;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(4px);
            align-items: center; justify-content: center;
            animation: fadeIn .2s ease;
        }
        .modal-overlay.active { display: flex; }

        .modal-card {
            background: white; border-radius: 24px;
            width: 100%; max-width: 680px; max-height: 90vh;
            overflow-y: auto; padding: 2.5rem;
            box-shadow: 0 30px 80px rgba(0,0,0,.3);
            animation: slideUp .3s cubic-bezier(0.34,1.56,0.64,1);
            position: relative;
        }
        .modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; }
        .modal-header h2 { font-size:1.8rem; color:var(--selva-dark); }
        .modal-close { background:none; border:none; font-size:1.5rem; cursor:pointer; color:#999; transition:color .2s; padding:.3rem; border-radius:6px; }
        .modal-close:hover { color:var(--error); background:#fdecea; }

        .modal-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; }
        .modal-grid .full { grid-column: 1 / -1; }

        .modal-label { font-size:.78rem; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:.5px; margin-bottom:.4rem; display:block; }
        .modal-input {
            width:100%; padding:.8rem 1rem; border:2px solid #e0e0e0; border-radius:10px;
            font-family:'Poppins',sans-serif; font-size:.9rem; outline:none;
            transition: border-color .2s, box-shadow .2s;
        }
        .modal-input:focus { border-color:var(--jungle-gold); box-shadow:0 0 0 3px rgba(255,179,0,.15); }
        .modal-input.err { border-color:var(--error); }

        .err-msg { font-size:.75rem; color:var(--error); margin-top:.3rem; display:none; }
        .err-msg.show { display:block; }

        .pass-wrap { position:relative; }
        .pass-wrap .modal-input { padding-right:2.8rem; }
        .pass-toggle { position:absolute; right:.8rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#aaa; font-size:1rem; }
        .pass-toggle:hover { color:var(--selva-med); }

        .pass-strength { height:4px; border-radius:4px; margin-top:.4rem; background:#e0e0e0; overflow:hidden; }
        .pass-strength-bar { height:100%; border-radius:4px; width:0; transition:width .3s, background .3s; }

        .modal-footer { display:flex; gap:1rem; justify-content:flex-end; margin-top:2rem; padding-top:1.5rem; border-top:2px solid var(--gris-bg); }
        .btn-cancelar { background:var(--gris-bg); color:#555; padding:.8rem 1.8rem; border-radius:10px; border:1px solid #ddd; cursor:pointer; font-weight:600; font-family:'Poppins',sans-serif; transition:var(--trans); }
        .btn-cancelar:hover { background:#ddd; }
        .btn-guardar { background:linear-gradient(135deg,var(--selva-med),var(--jungle-gold)); color:white; padding:.8rem 2rem; border-radius:10px; border:none; cursor:pointer; font-weight:700; font-family:'Poppins',sans-serif; font-size:1rem; transition:var(--trans); display:inline-flex; align-items:center; gap:.5rem; }
        .btn-guardar:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(46,125,50,.3); }
        .btn-guardar:disabled { opacity:.6; cursor:not-allowed; transform:none; }

        .alert-modal { padding:.9rem 1.2rem; border-radius:10px; margin-bottom:1.2rem; font-weight:600; font-size:.88rem; display:none; align-items:center; gap:.6rem; }
        .alert-modal.show { display:flex; }
        .alert-modal.error   { background:#fdecea; color:#c62828; border:1px solid #ef9a9a; }
        .alert-modal.success { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }

        @keyframes slideInDown { from{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)} }
        @keyframes fadeInUp    { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
        @keyframes fadeIn      { from{opacity:0}to{opacity:1} }
        @keyframes slideUp     { from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)} }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="/admin/dashboard" class="logo"><i class="fas fa-leaf"></i> ZooWonderland</a>
        <div class="menu">
            <a href="/admin/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="/admin/recorridos"><i class="fas fa-route"></i> Recorridos</a>
            <a href="/admin/areas"><i class="fas fa-map"></i> Áreas</a>
            <a href="/admin/animales"><i class="fas fa-paw"></i> Animales</a>
            <a href="/admin/reservas"><i class="fas fa-calendar-alt"></i> Reservas</a>
            <a href="/admin/usuarios" class="active"><i class="fas fa-user-group"></i> Usuarios</a>
            <a href="/admin/reportes"><i class="fas fa-file-chart-line"></i> Reportes</a>
            <a href="/admin/eventos"><i class="fas fa-calendar-days"></i> Eventos</a>
        </div>
        <div class="user-area">
            <span class="user-name">👋 {{ $user->getNombreParaMostrar() }}</span>
            <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </nav>
</header>

<main>
    <div class="page-header">
        <div class="page-header-text">
            <h1><i class="fas fa-users-gear"></i> Gestión de Usuarios</h1>
            <p>Administra cuentas, roles y permisos del sistema • {{ date('d/m/Y H:i') }}</p>
        </div>
        <button class="btn-nuevo" onclick="abrirModal()">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </button>
    </div>

    @if(session('success'))
        <div class="flash success"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash error"><i class="fas fa-circle-xmark"></i> {{ session('error') }}</div>
    @endif

    <div class="filtros-card">
        <h3><i class="fas fa-filter" style="color:var(--jungle-gold)"></i> Buscar y Filtrar</h3>
        <form method="GET" action="/admin/usuarios">
            <div class="filtros-grid">
                <div class="form-group">
                    <label><i class="fas fa-search"></i> Buscar por CI, nombre o apellido</label>
                    <input type="text" name="busqueda" class="form-control"
                           placeholder="Ej: 800007 o Juan Mendoza"
                           value="{{ request('busqueda') }}">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-id-badge"></i> Rol</label>
                    <select name="rol" class="form-control">
                        <option value="">Todos los roles</option>
                        <option value="administrador" {{ request('rol') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="guia"          {{ request('rol') === 'guia'          ? 'selected' : '' }}>Guía</option>
                        <option value="cliente"       {{ request('rol') === 'cliente'       ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-toggle-on"></i> Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div style="display:flex; gap:.6rem; flex-wrap:wrap;">
                    <button type="submit" class="btn-filtrar"><i class="fas fa-search"></i> Buscar</button>
                    <a href="/admin/usuarios" class="btn-limpiar"><i class="fas fa-xmark"></i> Limpiar</a>
                </div>
            </div>
        </form>
    </div>

    <div class="tabla-card">
        <div class="tabla-header">
            <h2><i class="fas fa-table-list"></i> Usuarios</h2>
            <span class="contador-badge">
                <i class="fas fa-users"></i> {{ count($usuarios) }} resultado{{ count($usuarios) !== 1 ? 's' : '' }}
            </span>
        </div>

        @if($usuarios->isEmpty())
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <p>No se encontraron usuarios con los filtros aplicados.</p>
            </div>
        @else
            <div class="tabla-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre completo</th>
                            <th>CI</th>
                            <th>Correo / Tel.</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $u)
                        @php
                            $inactivo  = (int)$u->estado === 0;
                            $rolNombre = $u->rol?->nombre_rol ?? 'cliente';
                            $rolBadge  = match($rolNombre) {
                                'administrador' => ['class' => 'badge-admin',   'icon' => 'fa-crown',         'label' => 'Admin'],
                                'guia'          => ['class' => 'badge-guia',    'icon' => 'fa-person-hiking', 'label' => 'Guía'],
                                default         => ['class' => 'badge-cliente', 'icon' => 'fa-user',          'label' => 'Cliente'],
                            };
                        @endphp
                        <tr class="{{ $inactivo ? 'inactivo' : '' }}">
                            <td style="color:#aaa; font-size:.85rem;">{{ $u->id_usuario }}</td>
                            <td>
                                <strong>{{ trim($u->nombre1.' '.($u->nombre2??'').' '.$u->apellido1.' '.($u->apellido2??'')) }}</strong>
                                @if($rolNombre === 'guia' && !empty($u->guia?->horarios))
                                    <br><small style="color:#888;"><i class="fas fa-clock"></i> {{ $u->guia->horarios }} &nbsp;·&nbsp; {{ $u->guia->dias_trabajo ?? '' }}</small>
                                @endif
                            </td>
                            <td style="font-weight:700; color:var(--selva-dark);">{{ $u->ci }}</td>
                            <td>
                                <small>{{ $u->correo }}</small><br>
                                <small style="color:#888;"><i class="fas fa-phone"></i> {{ $u->telefono ?? '—' }}</small>
                            </td>
                            <td><code style="background:#f5f5f5; padding:.2rem .5rem; border-radius:5px; font-size:.82rem;">{{ $u->nombre_usuario }}</code></td>
                            <td>
                                <span class="badge {{ $rolBadge['class'] }}">
                                    <i class="fas {{ $rolBadge['icon'] }}"></i> {{ $rolBadge['label'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $inactivo ? 'badge-inactivo' : 'badge-activo' }}">
                                    <i class="fas {{ $inactivo ? 'fa-ban' : 'fa-circle-check' }}"></i>
                                    {{ $inactivo ? 'Inactivo' : 'Activo' }}
                                </span>
                            </td>
                            <td>
                                <div class="acciones">
                                    <a href="/admin/usuario-editar?id={{ $u->id_usuario }}" class="btn-editar" title="Editar usuario">
                                        <i class="fas fa-pen-to-square"></i> Editar
                                    </a>
                                    <form method="POST" action="/admin/usuario-toggle" onsubmit="return confirm('¿Confirmas el cambio de estado?')">
                                        @csrf
                                        <input type="hidden" name="id_usuario" value="{{ $u->id_usuario }}">
                                        <input type="hidden" name="estado"     value="{{ $u->estado }}">
                                        <button type="submit" class="btn-toggle {{ $inactivo ? 'btn-activar' : 'btn-desactivar' }}">
                                            <i class="fas {{ $inactivo ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                            {{ $inactivo ? 'Activar' : 'Desactivar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</main>

<footer>
    <p>&copy; {{ date('Y') }} ZooWonderland - Panel de Administración. Educación y Conservación.</p>
</footer>

<!-- ── MODAL CREAR USUARIO ──────────────────────────────────── -->
<div class="modal-overlay" id="modalOverlay" onclick="cerrarModalFuera(event)">
    <div class="modal-card" id="modalCard">
        <div class="modal-header">
            <h2><i class="fas fa-user-plus" style="color:var(--jungle-gold)"></i> Nuevo Usuario</h2>
            <button class="modal-close" onclick="cerrarModal()" title="Cerrar">&times;</button>
        </div>

        <div class="alert-modal" id="modalAlert"></div>

        <form id="formCrear" method="POST" action="/admin/usuario-crear" novalidate>
            @csrf
            <div class="modal-grid">

                <div>
                    <label class="modal-label">Primer nombre *</label>
                    <input type="text" name="nombre1" id="f_nombre1" class="modal-input" placeholder="Ej: Juan">
                    <div class="err-msg" id="e_nombre1">Solo letras, sin números.</div>
                </div>
                <div>
                    <label class="modal-label">Segundo nombre</label>
                    <input type="text" name="nombre2" id="f_nombre2" class="modal-input" placeholder="Opcional">
                    <div class="err-msg" id="e_nombre2">Solo letras, sin números.</div>
                </div>
                <div>
                    <label class="modal-label">Primer apellido *</label>
                    <input type="text" name="apellido1" id="f_apellido1" class="modal-input" placeholder="Ej: Pérez">
                    <div class="err-msg" id="e_apellido1">Solo letras, sin números.</div>
                </div>
                <div>
                    <label class="modal-label">Segundo apellido</label>
                    <input type="text" name="apellido2" id="f_apellido2" class="modal-input" placeholder="Opcional">
                    <div class="err-msg" id="e_apellido2">Solo letras, sin números.</div>
                </div>

                <div>
                    <label class="modal-label">CI *</label>
                    <input type="text" name="ci" id="f_ci" class="modal-input" placeholder="7-8 dígitos" maxlength="8">
                    <div class="err-msg" id="e_ci">CI debe tener entre 7 y 8 números.</div>
                </div>
                <div>
                    <label class="modal-label">Teléfono *</label>
                    <input type="text" name="telefono" id="f_telefono" class="modal-input" placeholder="7-8 dígitos" maxlength="8">
                    <div class="err-msg" id="e_telefono">Teléfono debe tener entre 7 y 8 números.</div>
                </div>

                <div class="full">
                    <label class="modal-label">Correo electrónico *</label>
                    <input type="email" name="correo" id="f_correo" class="modal-input" placeholder="correo@ejemplo.com">
                    <div class="err-msg" id="e_correo">Ingresa un correo válido.</div>
                </div>

                <div>
                    <label class="modal-label">Nombre de usuario *</label>
                    <input type="text" name="nombre_usuario" id="f_usuario" class="modal-input" placeholder="Ej: juanp">
                    <div class="err-msg" id="e_usuario">El nombre de usuario es obligatorio.</div>
                </div>
                <div>
                    <label class="modal-label">Rol *</label>
                    <select name="rol" id="f_rol" class="modal-input">
                        <option value="">-- Selecciona --</option>
                        <option value="administrador">Administrador</option>
                        <option value="guia">Guía</option>
                    </select>
                    <div class="err-msg" id="e_rol">Selecciona un rol válido.</div>
                </div>

                <div>
                    <label class="modal-label">Contraseña *</label>
                    <div class="pass-wrap">
                        <input type="password" name="password" id="f_pass" class="modal-input" placeholder="Mín. 1 mayúscula y 1 número">
                        <button type="button" class="pass-toggle" onclick="togglePass('f_pass', this)"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="pass-strength"><div class="pass-strength-bar" id="passBar"></div></div>
                    <div class="err-msg" id="e_pass">Debe tener al menos una mayúscula y un número.</div>
                </div>
                <div>
                    <label class="modal-label">Confirmar contraseña *</label>
                    <div class="pass-wrap">
                        <input type="password" name="password_confirm" id="f_pass2" class="modal-input" placeholder="Repite la contraseña">
                        <button type="button" class="pass-toggle" onclick="togglePass('f_pass2', this)"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="err-msg" id="e_pass2">Las contraseñas no coinciden.</div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-guardar" id="btnGuardar">
                    <i class="fas fa-save"></i> Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Modal ──────────────────────────────────────────────────────
function abrirModal() {
    document.getElementById('modalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function cerrarModal() {
    document.getElementById('modalOverlay').classList.remove('active');
    document.body.style.overflow = '';
    limpiarModal();
}
function cerrarModalFuera(e) {
    if (e.target === document.getElementById('modalOverlay')) cerrarModal();
}
function limpiarModal() {
    document.getElementById('formCrear').reset();
    document.querySelectorAll('.err-msg').forEach(el => el.classList.remove('show'));
    document.querySelectorAll('.modal-input').forEach(el => el.classList.remove('err'));
    document.getElementById('passBar').style.width = '0';
    document.getElementById('passBar').style.background = '';
    const alert = document.getElementById('modalAlert');
    alert.classList.remove('show','error','success');
}

// ── Toggle password ───────────────────────────────────────────
function togglePass(inputId, btn) {
    const inp = document.getElementById(inputId);
    const isPass = inp.type === 'password';
    inp.type = isPass ? 'text' : 'password';
    btn.innerHTML = isPass ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
}

// ── Fuerza contraseña ─────────────────────────────────────────
document.getElementById('f_pass').addEventListener('input', function() {
    const v = this.value;
    const bar = document.getElementById('passBar');
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const colors = ['#ef5350','#ff7043','#ffb300','#66bb6a'];
    const widths = ['25%','50%','75%','100%'];
    bar.style.width  = score > 0 ? widths[score-1] : '0';
    bar.style.background = score > 0 ? colors[score-1] : '';
});

// ── Validaciones ──────────────────────────────────────────────
const soloLetras = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/;

function validarCampo(id, errId, condicion) {
    const el  = document.getElementById(id);
    const err = document.getElementById(errId);
    if (!condicion(el.value.trim())) {
        el.classList.add('err');
        err.classList.add('show');
        return false;
    }
    el.classList.remove('err');
    err.classList.remove('show');
    return true;
}

document.getElementById('formCrear').addEventListener('submit', function(e) {
    e.preventDefault();
    let ok = true;

    // Nombres y apellidos — solo letras
    ['nombre1','apellido1'].forEach(id => {
        if (!validarCampo('f_'+id, 'e_'+id, v => v.length > 0 && soloLetras.test(v))) ok = false;
    });
    ['nombre2','apellido2'].forEach(id => {
        const v = document.getElementById('f_'+id).value.trim();
        if (v && !soloLetras.test(v)) {
            document.getElementById('f_'+id).classList.add('err');
            document.getElementById('e_'+id).classList.add('show');
            ok = false;
        } else {
            document.getElementById('f_'+id).classList.remove('err');
            document.getElementById('e_'+id).classList.remove('show');
        }
    });

    // CI: 7-8 dígitos
    if (!validarCampo('f_ci', 'e_ci', v => /^\d{7,8}$/.test(v))) ok = false;

    // Teléfono: 7-8 dígitos
    if (!validarCampo('f_telefono', 'e_telefono', v => /^\d{7,8}$/.test(v))) ok = false;

    // Correo
    if (!validarCampo('f_correo', 'e_correo', v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v))) ok = false;

    // Usuario
    if (!validarCampo('f_usuario', 'e_usuario', v => v.length > 0)) ok = false;

    // Rol — solo admin o guia
    if (!validarCampo('f_rol', 'e_rol', v => ['administrador','guia'].includes(v))) ok = false;

    // Contraseña — 1 mayúscula + 1 número
    const pass  = document.getElementById('f_pass').value;
    const pass2 = document.getElementById('f_pass2').value;
    if (!validarCampo('f_pass', 'e_pass', v => v.length >= 6 && /[A-Z]/.test(v) && /[0-9]/.test(v))) ok = false;

    // Confirmar contraseña
    if (pass !== pass2) {
        document.getElementById('f_pass2').classList.add('err');
        document.getElementById('e_pass2').classList.add('show');
        ok = false;
    } else {
        document.getElementById('f_pass2').classList.remove('err');
        document.getElementById('e_pass2').classList.remove('show');
    }

    if (!ok) return;

    // Enviar
    const btn = document.getElementById('btnGuardar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
    this.submit();
});

// ── Abrir modal si hubo error de servidor ─────────────────────
@if(session('modal_error'))
    window.onload = () => {
        abrirModal();
        const alert = document.getElementById('modalAlert');
        alert.className = 'alert-modal show error';
        alert.innerHTML = '<i class="fas fa-circle-xmark"></i> {{ session("modal_error") }}';
    };
@endif
</script>

</body>
</html>
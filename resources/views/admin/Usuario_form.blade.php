<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - ZooWonderland</title>
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
            --gris-bg:       #f0f7f4;
            --blanco:        #ffffff;
            --shadow-lg:     0 20px 60px rgba(0,0,0,.15);
            --shadow-md:     0 10px 30px rgba(0,0,0,.1);
            --trans:         all .3s cubic-bezier(.34,1.56,.64,1);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f7f4, #e8f5e9 50%, #f1f8e9);
            color: #333;
            min-height: 100vh;
        }

        h1, h2, h3 { font-family: 'Playfair Display', serif; }

        header {
            background: linear-gradient(135deg, var(--selva-dark) 0%, var(--selva-med) 50%, var(--jungle-gold) 100%);
            padding: 1.5rem 5%;
            box-shadow: var(--shadow-lg);
            border-bottom: 4px solid var(--jungle-gold);
            position: sticky; top: 0; z-index: 1000;
        }
        nav {
            display: flex; justify-content: space-between; align-items: center;
            max-width: 1400px; margin: 0 auto; gap: 2rem;
        }
        .logo {
            font-size: 1.6rem; font-weight: 800; color: var(--jungle-gold);
            text-decoration: none; letter-spacing: -1px; white-space: nowrap;
        }
        .menu {
            display: flex; gap: 2rem; align-items: center; flex-grow: 1;
            flex-wrap: wrap;
        }
        .menu a {
            color: white; text-decoration: none; font-weight: 600; font-size: .9rem;
            transition: var(--trans); display: flex; align-items: center; gap: .5rem;
            white-space: nowrap;
        }
        .menu a:hover, .menu a.active { color: var(--jungle-gold); transform: translateY(-2px); }
        .user-area { display: flex; align-items: center; gap: 1rem; flex-shrink: 0; }
        .user-name {
            background: rgba(255,255,255,.15); padding: .5rem 1rem;
            border-radius: 25px; color: white; font-weight: 600; font-size: .85rem;
            white-space: nowrap;
        }
        .logout-btn {
            background: var(--jungle-orange); color: white;
            padding: .6rem 1.3rem; border-radius: 25px; border: none;
            cursor: pointer; font-weight: 600; transition: var(--trans);
            text-decoration: none; display: inline-flex; align-items: center;
            gap: .5rem; font-size: .85rem; white-space: nowrap;
        }
        .logout-btn:hover { background: #e67e00; transform: translateY(-2px); }

        main {
            width: 100%;
            max-width: 780px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
        }

        .breadcrumb {
            font-size: .83rem; color: #888; margin-bottom: 1.4rem;
            display: flex; align-items: center; gap: .4rem; flex-wrap: wrap;
        }
        .breadcrumb a { color: var(--selva-light); text-decoration: none; font-weight: 600; }
        .breadcrumb a:hover { text-decoration: underline; }

        .page-header { margin-bottom: 1.8rem; }
        .page-header h1 {
            font-size: 2rem;
            background: linear-gradient(135deg, var(--selva-dark), var(--jungle-gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; font-weight: 800;
        }
        .page-header p { color: #666; font-size: .9rem; margin-top: .3rem; }

        .errores-box {
            background: #fdecea; border: 1px solid #ef9a9a;
            border-radius: 12px; padding: 1rem 1.4rem; margin-bottom: 1.4rem;
        }
        .errores-box ul { list-style: none; }
        .errores-box li {
            color: #c62828; font-size: .85rem; font-weight: 600;
            padding: .2rem 0; display: flex; align-items: center; gap: .5rem;
        }

        .info-actual {
            background: var(--gris-bg); border-radius: 12px;
            padding: .9rem 1.2rem; margin-bottom: 1.5rem;
            border-left: 4px solid var(--jungle-gold);
            font-size: .85rem; color: #555;
            display: flex; align-items: center; gap: .6rem; flex-wrap: wrap;
        }
        .info-actual strong { color: var(--selva-dark); }

        .form-card {
            background: var(--blanco); border-radius: 20px;
            padding: 2rem; box-shadow: var(--shadow-md);
            animation: fadeInUp .6s ease;
            width: 100%;
        }

        .form-section-title {
            font-size: .9rem; font-weight: 700; color: var(--selva-dark);
            text-transform: uppercase; letter-spacing: .7px;
            margin-bottom: 1rem; padding-bottom: .5rem;
            border-bottom: 2px solid var(--gris-bg);
            display: flex; align-items: center; gap: .5rem;
        }
        .form-section-title i { color: var(--jungle-gold); }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .form-grid.cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        @media (max-width: 640px) {
            .form-grid,
            .form-grid.cols-3 { grid-template-columns: minmax(0, 1fr); }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: .35rem;
            min-width: 0;
        }
        .form-group.full { grid-column: 1 / -1; }

        label {
            font-size: .75rem; font-weight: 700; color: #666;
            text-transform: uppercase; letter-spacing: .5px;
        }

        .form-control {
            width: 100%;
            min-width: 0;
            padding: .72rem .9rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: .88rem;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            background: white;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .form-control:focus {
            border-color: var(--jungle-gold);
            box-shadow: 0 0 0 3px rgba(255,179,0,.15);
        }

        .hint { font-size: .72rem; color: #aaa; margin-top: .1rem; }

        .aviso-credenciales {
            background: #fff8e6; border: 1px solid #f0d060;
            border-radius: 10px; padding: .75rem 1rem;
            margin-bottom: 1.2rem;
            font-size: .82rem; color: #7a5c00;
            display: flex; align-items: flex-start; gap: .6rem;
        }
        .aviso-credenciales i { margin-top: .1rem; flex-shrink: 0; }

        .form-actions {
            display: flex; gap: 1rem; justify-content: flex-end;
            flex-wrap: wrap;
            margin-top: 1.8rem; padding-top: 1.4rem;
            border-top: 2px solid var(--gris-bg);
        }
        .btn-guardar {
            background: linear-gradient(135deg, var(--selva-light), var(--jungle-gold));
            color: white; padding: .85rem 2rem; border-radius: 25px;
            border: none; cursor: pointer; font-weight: 700; font-size: .9rem;
            font-family: 'Poppins', sans-serif; transition: var(--trans);
            display: inline-flex; align-items: center; gap: .6rem;
            box-shadow: 0 4px 15px rgba(46,125,50,.3);
        }
        .btn-guardar:hover { transform: translateY(-3px); box-shadow: 0 6px 25px rgba(255,179,0,.4); }

        .btn-cancelar {
            background: var(--gris-bg); color: var(--selva-dark);
            padding: .85rem 1.8rem; border-radius: 25px;
            border: 1px solid #ccc; cursor: pointer; font-weight: 600; font-size: .9rem;
            font-family: 'Poppins', sans-serif; text-decoration: none;
            display: inline-flex; align-items: center; gap: .5rem;
            transition: var(--trans);
        }
        .btn-cancelar:hover { background: #ddd; }

        footer {
            background: var(--selva-dark); color: white;
            text-align: center; padding: 2rem; margin-top: 3rem; font-size: .88rem;
        }

        .section-gap { margin-top: 1.6rem; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="/admin/dashboard" class="logo">
            <i class="fas fa-leaf"></i> ZooWonderland
        </a>
        <div class="menu">
            <a href="/admin/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="/admin/recorridos"><i class="fas fa-route"></i> Recorridos</a>
            <a href="/admin/areas"><i class="fas fa-map"></i> Áreas</a>
            <a href="/admin/animales"><i class="fas fa-paw"></i> Animales</a>
            <a href="/admin/reservas"><i class="fas fa-calendar-alt"></i> Reservas</a>
            <a href="/admin/usuarios" class="active"><i class="fas fa-user-group"></i> Usuarios</a>
            <a href="/admin/reportes"><i class="fas fa-file-chart-line"></i> Reportes</a>
            <a href="/admin/eventos" title="Gestionar Eventos"><i class="fas fa-calendar-days"></i> Eventos</a>
        </div>
        <div class="user-area">
            <span class="user-name">👋 {{ $authUser->getNombreParaMostrar() }}</span>
            <a href="/logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>
    </nav>
</header>

<main>
    <div class="breadcrumb">
        <a href="/admin/dashboard"><i class="fas fa-house"></i> Dashboard</a>
        <i class="fas fa-chevron-right" style="font-size:.65rem;"></i>
        <a href="/admin/usuarios">Usuarios</a>
        <i class="fas fa-chevron-right" style="font-size:.65rem;"></i>
        <span>Editar #{{ $usuarioEditar->id_usuario }}</span>
    </div>

    <div class="page-header">
        <h1><i class="fas fa-user-pen"></i> Editar Usuario</h1>
        <p>Solo el administrador puede realizar cambios sobre las cuentas del sistema.</p>
    </div>

    @if(!empty($errores))
        <div class="errores-box">
            <ul>
                @foreach($errores as $e)
                    <li><i class="fas fa-circle-exclamation"></i> {{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{--
        En el original PHP, $d se resuelve como $datos ?? $usuarioEditar (array).
        En Laravel usamos old() con fallback a la propiedad del modelo.
    --}}
    <div class="info-actual">
        <i class="fas fa-circle-info" style="color:var(--jungle-gold);"></i>
        Editando: <strong>{{ $usuarioEditar->nombre1 }} {{ $usuarioEditar->apellido1 }}</strong>
        &nbsp;·&nbsp; Rol actual: <strong>{{ ucfirst($usuarioEditar->rol?->nombre_rol ?? '') }}</strong>
        &nbsp;·&nbsp; Estado: <strong>{{ (int)$usuarioEditar->estado === 1 ? '✅ Activo' : '🔴 Inactivo' }}</strong>
    </div>

    <div class="form-card">
        <form method="POST" action="/admin/usuario-editar-post" autocomplete="off">
            @csrf
            <input type="hidden" name="id_usuario" value="{{ $usuarioEditar->id_usuario }}">

            <div class="form-section-title">
                <i class="fas fa-id-card"></i> Datos personales
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre1">Primer nombre *</label>
                    <input type="text" id="nombre1" name="nombre1" class="form-control"
                           value="{{ old('nombre1', $usuarioEditar->nombre1 ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="nombre2">Segundo nombre</label>
                    <input type="text" id="nombre2" name="nombre2" class="form-control"
                           value="{{ old('nombre2', $usuarioEditar->nombre2 ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="apellido1">Primer apellido *</label>
                    <input type="text" id="apellido1" name="apellido1" class="form-control"
                           value="{{ old('apellido1', $usuarioEditar->apellido1 ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="apellido2">Segundo apellido</label>
                    <input type="text" id="apellido2" name="apellido2" class="form-control"
                           value="{{ old('apellido2', $usuarioEditar->apellido2 ?? '') }}">
                </div>
            </div>

            <div class="form-section-title section-gap">
                <i class="fas fa-address-book"></i> Contacto e identificación
            </div>
            <div class="form-grid cols-3">
                <div class="form-group">
                    <label for="ci">CI *</label>
                    <input type="number" id="ci" name="ci" class="form-control"
                           value="{{ old('ci', $usuarioEditar->ci ?? '') }}"
                           required min="1">
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" class="form-control"
                           value="{{ old('telefono', $usuarioEditar->telefono ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="rol">Rol *</label>
                    <select id="rol" name="rol" class="form-control" required>
                        <option value="cliente"       {{ old('rol', $usuarioEditar->rol?->nombre_rol ?? '') === 'cliente'       ? 'selected' : '' }}>Cliente</option>
                        <option value="guia"          {{ old('rol', $usuarioEditar->rol?->nombre_rol ?? '') === 'guia'          ? 'selected' : '' }}>Guía</option>
                        <option value="administrador" {{ old('rol', $usuarioEditar->rol?->nombre_rol ?? '') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
            </div>

            <div class="form-section-title section-gap">
                <i class="fas fa-key"></i> Credenciales de acceso
            </div>

            <div class="aviso-credenciales">
                <i class="fas fa-triangle-exclamation"></i>
                <span>
                    Modificar el correo o el nombre de usuario afecta directamente el acceso del usuario al sistema.
                    El sistema verificará que no estén en uso por otra cuenta antes de guardar.
                </span>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="correo">Correo electrónico *</label>
                    <input type="email" id="correo" name="correo" class="form-control"
                           value="{{ old('correo', $usuarioEditar->correo ?? '') }}"
                           required autocomplete="off">
                    <span class="hint">Debe ser único en el sistema.</span>
                </div>
                <div class="form-group">
                    <label for="nombre_usuario">Nombre de usuario *</label>
                    <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control"
                           value="{{ old('nombre_usuario', $usuarioEditar->nombre_usuario ?? '') }}"
                           required autocomplete="off">
                    <span class="hint">Debe ser único. Se usa para iniciar sesión.</span>
                </div>
            </div>

            <div class="form-actions">
                <a href="/admin/usuarios" class="btn-cancelar">
                    <i class="fas fa-xmark"></i> Cancelar
                </a>
                <button type="submit" class="btn-guardar">
                    <i class="fas fa-floppy-disk"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
</main>

<footer>
    <p>&copy; {{ date('Y') }} ZooWonderland - Panel de Administración.</p>
</footer>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asignaciones - ZooWonderland</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --selva-dark: #2E7D32;
            --selva-med: #43A047;
            --selva-light: #66BB6A;
            --jungle-gold: #FFC107;
            --sky-blue: #0288D1;
            --blanco: #ffffff;
            --gris-bg: #f5f5f5;
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --trans: all 0.3s ease;
        }
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #fafafa; margin: 0; color: #333; }
        header { background: var(--selva-dark); color: white; }
        header nav { max-width: 1400px; margin: 0 auto; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        header .logo { font-size: 1.6rem; font-weight: bold; text-decoration: none; color: white; }
        header .menu a { color: white; text-decoration: none; margin-left: 1.5rem; font-weight: 600; }
        main { max-width: 1400px; margin: 0 auto; padding: 3rem 5%; }
        .page-header h1 { font-size: 2.4rem; color: var(--selva-dark); margin-bottom: 0.5rem; }
        .content-section { background: var(--blanco); border-radius: 20px; padding: 2.5rem; box-shadow: var(--shadow-md); }
        .flash { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 1rem; font-weight: 600; }
        .flash.ok { background: #e6f7ff; color: #0275d8; border: 1px solid #b3e5fc; }
        .flash.error { background: #ffe6e6; color: #c0392b; border: 1px solid #f5c6cb; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 3px solid var(--gris-bg); padding-bottom: 1.5rem; }
        .btn-primary { background: linear-gradient(135deg, var(--selva-light), var(--jungle-gold)); color: white; padding: 0.9rem 2rem; border-radius: 25px; text-decoration: none; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.8rem; box-shadow: 0 4px 15px rgba(46,125,50,0.3); }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f5f5f5; border-bottom: 3px solid var(--jungle-gold); }
        th, td { padding: 1.2rem; text-align: left; }
        th { font-weight: 700; color: var(--selva-dark); }
        .actions { display: flex; gap: 1rem; }
        .delete { color: #ff6f6f; background: rgba(255,111,111,0.1); text-decoration: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; font-size: 1rem; font-family: inherit; }
        .empty-state { text-align: center; padding: 4rem 2rem; color: #bbb; }
        footer { background: var(--selva-dark); color: white; text-align: center; padding: 2rem; margin-top: 3rem; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="/admin/dashboard" class="logo"><i class="fas fa-leaf"></i> ZooWonderland</a>
            <div class="menu">
                <a href="/admin/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/recorridos"><i class="fas fa-compass"></i> Recorridos</a>
                <a href="/admin/usuarios"><i class="fas fa-users"></i> Usuarios</a>
                <a href="/admin/asignaciones"><i class="fas fa-user-tie"></i> Asignaciones</a>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="page-header">
            <h1><i class="fas fa-user-tie"></i> Asignación de Guías</h1>
            <p>Organiza los tours asignando guías disponibles a los recorridos.</p>
        </div>

        <div class="content-section">
            @if(session('mensaje'))
                <div class="flash {{ session('tipoMsg') }}">
                    {{ session('mensaje') }}
                </div>
            @endif

            <div class="section-header">
                <h2><i class="fas fa-list"></i> Asignaciones Actuales</h2>
                <a href="/admin/asignaciones/crear" class="btn-primary"><i class="fas fa-plus-circle"></i> Nueva Asignación</a>
            </div>

            @if($asignaciones->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-xmark" style="font-size: 4rem; opacity: 0.2;"></i>
                    <p>No hay asignaciones registradas.</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>📅 Fecha</th>
                            <th>⏰ Hora Inicio</th>
                            <th>👤 Guía</th>
                            <th>🗺️ Recorrido</th>
                            <th>⏳ Duración</th>
                            <th>⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asignaciones as $asig)
                        <tr>
                            <td><strong>{{ $asig->fecha_asignacion }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($asig->hora_inicio)->format('H:i') }}</td>
                            <td>{{ $asig->guia?->usuario?->nombre1 }} {{ $asig->guia?->usuario?->apellido1 }}</td>
                            <td><strong>{{ $asig->recorrido?->nombre }}</strong></td>
                            <td>{{ $asig->recorrido?->duracion }} min</td>
                            <td class="actions">
                                <a href="/admin/asignaciones/eliminar?id={{ $asig->id_guia_recorrido }}" class="delete"
                                   onclick="return confirm('¿Eliminar esta asignación?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} ZooWonderland - Panel Administrativo</p>
    </footer>
</body>
</html>
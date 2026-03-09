<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Animales - ZooWonderland</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --selva-dark: #2E7D32; --selva-light: #66BB6A;
            --jungle-gold: #FFC107; --blanco: #ffffff;
            --gris-bg: #f5f5f5; --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --trans: all 0.3s ease;
        }
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #fafafa; margin: 0; color: #333; }
        header { background: var(--selva-dark); color: white; }
        header nav { max-width: 1400px; margin: 0 auto; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        header .logo { font-size: 1.6rem; font-weight: bold; text-decoration: none; color: white; }
        header .menu a { color: white; text-decoration: none; margin-left: 1.5rem; font-weight: 600; }
        header .menu a:hover { text-decoration: underline; }
        main { max-width: 1400px; margin: 0 auto; padding: 3rem 5%; }
        .page-header h1 { font-size: 2.4rem; color: var(--selva-dark); margin-bottom: 0.5rem; }
        .page-header p { color: #666; }
        .content-section { background: var(--blanco); border-radius: 20px; padding: 2.5rem; box-shadow: var(--shadow-md); animation: fadeInUp 1s ease 0.2s both; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 3px solid var(--gris-bg); padding-bottom: 1.5rem; }
        .section-header h2 { font-size: 1.8rem; color: var(--selva-dark); display: flex; align-items: center; gap: 0.8rem; }
        .btn-primary { background: linear-gradient(135deg, var(--selva-light), var(--jungle-gold)); color: white; padding: 0.9rem 2rem; border-radius: 25px; text-decoration: none; font-weight: 700; border: none; cursor: pointer; transition: var(--trans); display: inline-flex; align-items: center; gap: 0.8rem; box-shadow: 0 4px 15px rgba(46,125,50,0.3); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 6px 25px rgba(255,179,0,0.4); }
        table { width: 100%; border-collapse: collapse; }
        thead { background: linear-gradient(90deg, #f5f5f5, #efefef); border-bottom: 3px solid var(--jungle-gold); }
        th, td { padding: 1.2rem; text-align: left; }
        th { font-weight: 700; color: var(--selva-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        tbody tr:hover { background: rgba(255,179,0,0.05); }
        .actions { display: flex; gap: 1rem; font-size: 0.85rem; }
        .actions a { text-decoration: none; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; transition: var(--trans); display: inline-flex; align-items: center; gap: 0.4rem; }
        .edit   { color: var(--selva-light); background: rgba(46,125,50,0.1); }
        .edit:hover   { background: rgba(46,125,50,0.25); transform: translateX(3px); }
        .delete { color: #ff6f6f; background: rgba(255,111,111,0.1); }
        .delete:hover { background: rgba(255,111,111,0.25); transform: translateX(3px); }
        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-state i { font-size: 4rem; color: #ddd; margin-bottom: 1rem; }
        .empty-state p { font-size: 1.1rem; color: #999; }
        footer { background: var(--selva-dark); color: white; text-align: center; padding: 2rem; margin-top: 3rem; font-size: 0.9rem; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="/admin/dashboard" class="logo"><i class="fas fa-leaf"></i> ZooWonderland</a>
            <div class="menu">
                <a href="/admin/dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/recorridos"><i class="fas fa-compass"></i> Recorridos</a>
                <a href="/admin/animales"><i class="fas fa-paw"></i> Animales</a>
                <a href="/admin/areas"><i class="fas fa-shapes"></i> Áreas</a>
                <a href="/admin/reservas"><i class="fas fa-calendar-check"></i> Reservas</a>
                <a href="/admin/eventos"><i class="fas fa-calendar-days"></i> Eventos</a>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="page-header">
            <h1><i class="fas fa-paw"></i> Gestión de Animales</h1>
            <p>Aquí puedes crear, editar y eliminar animales del zoo.</p>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-paw"></i> Animales</h2>
                <a href="/admin/animales/crear" class="btn-primary"><i class="fas fa-plus-circle"></i> Nuevo Animal</a>
            </div>

            <div style="margin-bottom: 2rem;">
                <form method="GET" action="/admin/animales" style="display: flex; gap: 0.8rem; align-items: center; flex-wrap: wrap;">
                    <input type="text" name="q" placeholder="Buscar por especie, nombre, hábitat o descripción..."
                           value="{{ request('q') }}"
                           style="flex: 1; min-width: 200px; padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                    <select name="area" style="padding: 0.8rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                        <option value="0">Todas las áreas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}"
                                {{ request('area', 0) == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" style="background: var(--selva-light); color: white; padding: 0.8rem 1.5rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    @if(request('q') || request('area'))
                        <a href="/admin/animales" style="background: #999; color: white; padding: 0.8rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    @endif
                </form>
            </div>

            @if($animales->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-paw"></i>
                    <p>{{ request('q') ? 'No se encontraron animales que coincidan con tu búsqueda.' : 'No hay animales registrados aún. ¡Agrega tu primer animal!' }}</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>🧬 Especie</th>
                            <th>🌍 Hábitat</th>
                            <th>🏷️ Área</th>
                            <th>📝 Descripción</th>
                            <th>⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($animales as $animal)
                        <tr>
                            <td><strong>{{ $animal->especie }}</strong></td>
                            <td>{{ $animal->habitat ?? '—' }}</td>
                            <td>{{ $animal->area?->nombre ?? '—' }}</td>
                            <td>{{ $animal->descripcion }}</td>
                            <td class="actions">
                                <a href="/admin/animales/editar?id={{ $animal->id_animal }}" class="edit">
                                    <i class="fas fa-pen"></i> Editar
                                </a>
                                <form method="POST" action="/admin/animales/eliminar" style="display:inline"
                                      onsubmit="return confirm('¿Confirma eliminar este animal?')">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $animal->id_animal }}">
                                    <button type="submit" class="delete" title="Eliminar">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} ZooWonderland - Panel de Administración. Educación y Conservación.</p>
    </footer>
</body>
</html>
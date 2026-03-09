<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Eventos - ZooWonderland</title>
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
            --shadow-sm: 0 2px 6px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
            --trans: all 0.3s ease;
        }
        body { background: #fafafa; margin: 0; color: #333; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
        header { background: var(--selva-dark); color: white; }
        header nav { max-width: 1400px; margin: 0 auto; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        header .logo { font-size: 1.6rem; font-weight: bold; text-decoration: none; color: white; }
        header .menu a { color: white; text-decoration: none; margin-left: 1.5rem; font-weight: 600; }
        main { max-width: 1400px; margin: 0 auto; padding: 3rem 5%; }
        .page-header h1 { font-size: 2.4rem; color: var(--selva-dark); margin-bottom: 0.5rem; }
        .page-header p { color: #666; }
        .content-section { background: var(--blanco); border-radius: 20px; padding: 2.5rem; box-shadow: var(--shadow-md); }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 3px solid var(--gris-bg); padding-bottom: 1.5rem; }
        .section-header h2 { font-size: 1.8rem; color: var(--selva-dark); display: flex; align-items: center; gap: 0.8rem; }
        .btn-primary { background: linear-gradient(135deg, var(--selva-light), var(--jungle-gold)); color: white; padding: 0.9rem 2rem; border-radius: 25px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.8rem; box-shadow: 0 4px 15px rgba(46,125,50,0.3); letter-spacing: 0.5px; cursor: pointer; transition: var(--trans); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 6px 25px rgba(255,179,0,0.4); }
        table { width: 100%; border-collapse: collapse; }
        thead { background: linear-gradient(90deg, #f5f5f5 0%, #efefef 100%); border-bottom: 3px solid var(--jungle-gold); }
        th, td { padding: 1.2rem; text-align: left; }
        th { font-weight: 700; color: var(--selva-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        tbody tr { transition: var(--trans); }
        tbody tr:hover { background: linear-gradient(90deg, transparent, rgba(255,179,0,0.05), transparent); }
        .actions { display: flex; gap: 1rem; font-size: 0.85rem; }
        .actions a, .actions button { text-decoration: none; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; transition: var(--trans); display: inline-flex; align-items: center; gap: 0.4rem; border: none; cursor: pointer; font-size: 0.85rem; font-family: inherit; }
        .view { color: var(--sky-blue); background: rgba(2,136,209,0.1); }
        .view:hover { background: rgba(2,136,209,0.25); transform: translateX(3px); }
        .edit { color: var(--selva-light); background: rgba(46,125,50,0.1); }
        .edit:hover { background: rgba(46,125,50,0.25); transform: translateX(3px); }
        .delete { color: #ff6f6f; background: rgba(255,111,111,0.1); }
        .delete:hover { background: rgba(255,111,111,0.25); transform: translateX(3px); }
        .empty-state { text-align: center; padding: 4rem 2rem; color: #bbb; }
        .empty-state i { font-size: 4rem; margin-bottom: 1rem; color: #ddd; opacity: 0.5; }
        .empty-state p { font-size: 1.1rem; color: #999; }
        footer { background: var(--selva-dark); color: white; text-align: center; padding: 2rem; margin-top: 3rem; font-size: 0.9rem; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); text-align: center; max-width: 400px; }
        .modal-btn { margin: 1rem 0.5rem; padding: 0.8rem 1.5rem; border: none; border-radius: 6px; cursor: pointer; }
        .btn-confirm { background: #c62828; color: white; }
        .btn-cancel { background: #ddd; color: #333; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="/admin/dashboard" class="logo"><i class="fas fa-leaf"></i> ZooWonderland</a>
            <div class="menu">
                <a href="/admin/dashboard" title="Dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="/admin/recorridos" title="Gestionar Recorridos"><i class="fas fa-compass"></i> Recorridos</a>
                <a href="/admin/animales" title="Gestionar Animales"><i class="fas fa-paw"></i> Animales</a>
                <a href="/admin/areas" title="Gestión de Áreas"><i class="fas fa-map-location-dot"></i> Áreas</a>
                <a href="/admin/reservas" title="Reservas"><i class="fas fa-calendar-check"></i> Reservas</a>
                <a href="/admin/usuarios" title="Usuarios"><i class="fas fa-users"></i> Usuarios</a>
                <a href="/admin/reportes" title="Reportes"><i class="fas fa-file-alt"></i> Reportes</a>
                <a href="/admin/eventos" title="Eventos"><i class="fas fa-calendar-days"></i> Eventos</a>
                <a href="/logout" title="Salir"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="page-header">
            <h1><i class="fas fa-calendar-days"></i> Gestión de Eventos</h1>
            <p>Crear, editar y eliminar eventos del zoológico</p>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-days"></i> Eventos</h2>
                <a href="/admin/eventos/crear" class="btn-primary"><i class="fas fa-plus-circle"></i> Nuevo Evento</a>
            </div>

            {{-- Filtros --}}
            <form method="GET" action="/admin/eventos" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
                <select name="vigencia" style="padding: 0.8rem; flex: 1; border-radius: 6px; border: 1px solid #ddd;">
                    <option value="">Todas las vigencias</option>
                    <option value="pasado"    {{ ($filtros['vigencia'] ?? '') === 'pasado'    ? 'selected' : '' }}>Pasados</option>
                    <option value="pendiente" {{ ($filtros['vigencia'] ?? '') === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                </select>
                <input type="date" name="fecha"
                       value="{{ $filtros['fecha'] ?? '' }}"
                       style="padding: 0.8rem; flex: 1; border-radius: 6px; border: 1px solid #ddd;">
                <input type="text" name="nombre" placeholder="Buscar por nombre..."
                       value="{{ $filtros['nombre'] ?? '' }}"
                       style="padding: 0.8rem; flex: 2; border-radius: 6px; border: 1px solid #ddd;">
                <select name="encargado_id" style="padding: 0.8rem; flex: 1; border-radius: 6px; border: 1px solid #ddd;">
                    <option value="">Todos los encargados</option>
                    @foreach($guias as $guia)
                        <option value="{{ $guia->id_guia }}"
                            {{ ($filtros['encargado_id'] ?? '') == $guia->id_guia ? 'selected' : '' }}>
                            {{ $guia->usuario?->nombre1 }} {{ $guia->usuario?->apellido1 }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" style="background: var(--selva-med); color: white; padding: 0.8rem 1.5rem; border-radius: 6px; border: none; cursor: pointer;">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </form>

            @if($eventos->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-days"></i>
                    <p>No hay eventos que coincidan con los filtros.</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fechas</th>
                            <th>Costo</th>
                            <th>Encargado</th>
                            <th>Vigencia</th>
                            <th>Límite</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos as $e)
                        <tr>
                            <td><strong>{{ $e->nombre_evento }}</strong></td>
                            <td>
                                {{ \Carbon\Carbon::parse($e->fecha_inicio)->format('d/m/Y H:i') }} —
                                {{ \Carbon\Carbon::parse($e->fecha_fin)->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ $e->tiene_costo ? 'Bs ' . number_format($e->precio, 2) : 'Gratuito' }}</td>
                            <td>{{ $e->guia?->usuario?->nombre1 ?? 'No asignado' }} {{ $e->guia?->usuario?->apellido1 ?? '' }}</td>
                            <td>{{ $e->vigencia }}</td>
                            <td>{{ $e->limite_participantes ?? 'Sin límite' }}</td>
                            <td class="actions">
                                <a href="/admin/eventos/detalle?id={{ $e->id_evento }}" class="view">
                                    <i class="fas fa-eye"></i> Detalle
                                </a>
                                <a href="/admin/eventos/editar?id={{ $e->id_evento }}" class="edit">
                                    <i class="fas fa-pen"></i> Editar
                                </a>
                                <button class="delete" onclick="showDeleteModal({{ $e->id_evento }})">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Modal eliminar --}}
            <div id="deleteModal" class="modal">
                <div class="modal-content">
                    <h2>¿Eliminar evento?</h2>
                    <p>Esta acción no se puede deshacer. ¿Estás seguro?</p>
                    <form method="POST" action="/admin/eventos/eliminar">
                        @csrf
                        <input type="hidden" id="deleteId" name="id">
                        <button type="submit" class="modal-btn btn-confirm">Eliminar</button>
                        <button type="button" class="modal-btn btn-cancel" onclick="closeModal()">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} ZooWonderland - Panel de Administración</p>
    </footer>

    <script>
        function showDeleteModal(id) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $evento ? 'Editar Evento' : 'Nuevo Evento' }} - ZooWonderland</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
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
        body { font-family: 'Segoe UI', sans-serif; background: #fafafa; margin: 0; color: #333; }
        header { background: var(--selva-dark); color: white; }
        header nav { max-width: 1400px; margin: 0 auto; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        header .logo { font-size: 1.6rem; font-weight: bold; text-decoration: none; color: white; }
        header .menu a { color: white; text-decoration: none; margin-left: 1.5rem; font-weight: 600; }
        main { max-width: 1400px; margin: 0 auto; padding: 3rem 5%; }
        .page-header h1 { font-size: 2.4rem; color: var(--selva-dark); margin-bottom: 0.5rem; }
        .page-header p { color: #666; }
        .form-container { background: var(--blanco); border-radius: 20px; padding: 2.5rem; box-shadow: var(--shadow-md); }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--selva-dark); }
        input[type="text"], input[type="datetime-local"], input[type="number"], select { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; box-sizing: border-box; }
        textarea { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; min-height: 120px; font-size: 1rem; box-sizing: border-box; }
        .checkbox { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
        .actividades { margin-top: 2rem; border-top: 2px solid var(--gris-bg); padding-top: 1.5rem; }
        .actividad-item { background: #f9f9f9; padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.08); position: relative; }
        .remove-btn { position: absolute; top: 10px; right: 10px; background: #ff6f6f; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; font-size: 1rem; }
        .add-actividad { background: var(--selva-light); color: white; padding: 0.8rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-submit { background: var(--jungle-gold); color: #333; padding: 1rem 2rem; border: none; border-radius: 25px; font-weight: 700; cursor: pointer; width: 100%; margin-top: 2rem; font-size: 1.1rem; }
        .success { padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center; background: #e8f5e9; color: #2e7d32; }
        .error   { padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center; background: #ffebee; color: #c62828; }
        .field-error { color: #c62828; font-size: 0.9rem; margin-top: 0.3rem; display: block; }
        #precio-group { display: none; }
        input.error, textarea.error, select.error { border: 2px solid #c62828; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const costoCheckbox = document.getElementById('tiene_costo');
            const precioGroup   = document.getElementById('precio-group');
            const form          = document.querySelector('form');

            costoCheckbox.addEventListener('change', () => {
                precioGroup.style.display = costoCheckbox.checked ? 'block' : 'none';
                if (!costoCheckbox.checked) {
                    document.getElementById('precio').value = '0.00';
                    document.getElementById('error-precio').textContent = '';
                }
            });

            form.addEventListener('submit', (e) => {
                let valid = true;
                document.querySelectorAll('.field-error').forEach(el => el.textContent = '');

                const nombreEvento = document.getElementById('nombre_evento').value.trim();
                if (!nombreEvento) {
                    document.getElementById('error-nombre').textContent = 'El nombre del evento es obligatorio';
                    valid = false;
                }

                const descGeneral = document.getElementById('descripcion').value.trim();
                if (!descGeneral) {
                    document.getElementById('error-descripcion').textContent = 'La descripción del evento es obligatoria';
                    valid = false;
                }

                const inicio = document.getElementById('fecha_inicio').value;
                const fin    = document.getElementById('fecha_fin').value;
                if (!inicio) {
                    document.getElementById('error-fechas').textContent = 'La fecha de inicio es obligatoria';
                    valid = false;
                }
                if (!fin) {
                    document.getElementById('error-fechas').textContent = 'La fecha de fin es obligatoria';
                    valid = false;
                }
                if (inicio && fin && new Date(inicio) > new Date(fin)) {
                    document.getElementById('error-fechas').textContent = 'La fecha de inicio no puede ser posterior a la fecha de fin';
                    valid = false;
                }

                if (costoCheckbox.checked) {
                    const precio = parseFloat(document.getElementById('precio').value) || 0;
                    if (precio < 0) {
                        document.getElementById('error-precio').textContent = 'El precio debe ser mayor o igual a 0';
                        valid = false;
                    }
                }

                const encargado = document.getElementById('encargado_id').value;
                if (!encargado) {
                    document.getElementById('error-encargado').textContent = 'El encargado es obligatorio';
                    valid = false;
                }

                const lugar = document.getElementById('lugar').value;
                if (!lugar) {
                    document.getElementById('error-lugar').textContent = 'El lugar es obligatorio';
                    valid = false;
                }

                document.querySelectorAll('.actividad-item').forEach(item => {
                    const nombre = item.querySelector('input[name="actividad_nombre[]"]');
                    const desc   = item.querySelector('textarea[name="actividad_desc[]"]');

                    if (nombre && !nombre.value.trim()) {
                        let errorEl = item.querySelector('.error-nombre') || document.createElement('span');
                        errorEl.className = 'field-error error-nombre';
                        errorEl.textContent = 'Nombre de actividad obligatorio';
                        nombre.parentNode.appendChild(errorEl);
                        valid = false;
                    }
                    if (desc && !desc.value.trim()) {
                        let errorEl = item.querySelector('.error-desc') || document.createElement('span');
                        errorEl.className = 'field-error error-desc';
                        errorEl.textContent = 'Descripción de actividad obligatoria';
                        desc.parentNode.appendChild(errorEl);
                        valid = false;
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    const firstError = document.querySelector('.field-error:not(:empty)');
                    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });

        let actividadIndex = {{ json_encode(count(old('actividades', $evento?->actividades?->toArray() ?? []))) }};

        function addActividad() {
            const container = document.getElementById('actividades-container');
            container.insertAdjacentHTML('beforeend', `
                <div class="actividad-item" id="actividad-${actividadIndex}">
                    <button type="button" class="remove-btn" onclick="this.parentElement.remove()">×</button>
                    <div class="form-group">
                        <label>Nombre de actividad *</label>
                        <input type="text" name="actividad_nombre[]">
                        <span class="field-error error-nombre"></span>
                    </div>
                    <div class="form-group">
                        <label>Descripción *</label>
                        <textarea name="actividad_desc[]"></textarea>
                        <span class="field-error error-desc"></span>
                    </div>
                </div>
            `);
            actividadIndex++;
        }
    </script>
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
            <h1><i class="fas fa-calendar-days"></i> {{ $evento ? 'Editar Evento' : 'Nuevo Evento' }}</h1>
            <p>Completa los datos del evento</p>
        </div>

        <div class="form-container">
            @if(session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="/admin/eventos/guardar" novalidate>
                @csrf
                <input type="hidden" name="id" value="{{ $evento?->id_evento ?? 0 }}">

                <div class="form-group">
                    <label for="nombre_evento">Nombre del evento *</label>
                    <input type="text" id="nombre_evento" name="nombre_evento" maxlength="150"
                           value="{{ old('nombre_evento', $evento?->nombre_evento ?? '') }}">
                    <span class="field-error" id="error-nombre"></span>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción detallada *</label>
                    <textarea id="descripcion" name="descripcion" rows="5">{{ old('descripcion', $evento?->descripcion ?? '') }}</textarea>
                    <span class="field-error" id="error-descripcion"></span>
                </div>

                <div class="form-group" style="display:flex; gap:1rem;">
                    <div style="flex:1;">
                        <label for="fecha_inicio">Fecha y hora de inicio *</label>
                        <input type="datetime-local" id="fecha_inicio" name="fecha_inicio"
                               min="{{ date('Y-m-d\TH:i') }}"
                               value="{{ old('fecha_inicio', $evento?->fecha_inicio ?? '') }}">
                    </div>
                    <div style="flex:1;">
                        <label for="fecha_fin">Fecha y hora de finalización *</label>
                        <input type="datetime-local" id="fecha_fin" name="fecha_fin"
                               min="{{ date('Y-m-d\TH:i') }}"
                               value="{{ old('fecha_fin', $evento?->fecha_fin ?? '') }}">
                    </div>
                </div>
                <span class="field-error" id="error-fechas"></span>

                <div class="checkbox">
                    <input type="checkbox" id="tiene_costo" name="tiene_costo"
                        {{ old('tiene_costo', $evento?->tiene_costo ?? false) ? 'checked' : '' }}>
                    <label for="tiene_costo">Este evento tiene costo adicional</label>
                </div>

                <div class="form-group" id="precio-group"
                     style="display: {{ old('tiene_costo', $evento?->tiene_costo ?? false) ? 'block' : 'none' }};">
                    <label for="precio">Precio del evento (Bs)</label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0"
                           value="{{ old('precio', $evento?->precio ?? '0.00') }}">
                    <span class="field-error" id="error-precio">El precio debe ser mayor o igual a 0 si el evento tiene costo</span>
                </div>

                <div class="form-group">
                    <label for="encargado_id">Encargado / Guía responsable *</label>
                    <select id="encargado_id" name="encargado_id">
                        <option value="">Seleccione un guía</option>
                        @foreach($guias as $guia)
                            <option value="{{ $guia->id_guia }}"
                                {{ old('encargado_id', $evento?->encargado_id ?? '') == $guia->id_guia ? 'selected' : '' }}>
                                {{ $guia->usuario?->nombre1 }} {{ $guia->usuario?->apellido1 }}
                            </option>
                        @endforeach
                    </select>
                    <span class="field-error" id="error-encargado"></span>
                </div>

                <div class="form-group">
                    <label for="lugar">Lugar del evento *</label>
                    <select id="lugar" name="lugar">
                        <option value="">Seleccione un lugar</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->nombre }}"
                                {{ old('lugar', $evento?->lugar ?? '') === $area->nombre ? 'selected' : '' }}>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <span class="field-error" id="error-lugar"></span>
                </div>

                <div class="form-group">
                    <label for="limite_participantes">Límite de participantes *</label>
                    <input type="number" id="limite_participantes" name="limite_participantes"
                           min="0" max="50" placeholder="Maximo 50"
                           value="{{ old('limite_participantes', $evento?->limite_participantes ?? '') }}">
                </div>

                {{-- Actividades --}}
                <div class="actividades">
                    <h3>Actividades del evento</h3>
                    <div id="actividades-container">
                        @php $actividades = old('actividades', $evento?->actividades?->toArray() ?? []); @endphp
                        @foreach($actividades as $idx => $act)
                            <div class="actividad-item" id="actividad-{{ $idx }}">
                                <button type="button" class="remove-btn" onclick="this.parentElement.remove()">×</button>
                                <div class="form-group">
                                    <label>Nombre de actividad *</label>
                                    <input type="text" name="actividad_nombre[]"
                                           value="{{ $act['nombre_actividad'] ?? '' }}">
                                    <span class="field-error error-nombre"></span>
                                </div>
                                <div class="form-group">
                                    <label>Descripción *</label>
                                    <textarea name="actividad_desc[]">{{ $act['descripcion'] ?? '' }}</textarea>
                                    <span class="field-error error-desc"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-actividad" onclick="addActividad()">
                        <i class="fas fa-plus"></i> Agregar Actividad
                    </button>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> {{ $evento ? 'Guardar Cambios' : 'Crear Evento' }}
                </button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} ZooWonderland - Panel de Administración</p>
    </footer>
</body>
</html>
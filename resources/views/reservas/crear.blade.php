{{-- resources/views/reservas/crear.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Tour Grupal - ZooWonderland</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    :root {
        --verde-selva:   #2e7d32;
        --verde-oscuro:  #1b5e20;
        --amarillo-sol:  #ffca28;
        --gris-claro:    #f8faf8;
        --oscuro:        #0d3a1f;
        --blanco:        #ffffff;
        --sombra:        0 10px 30px rgba(0,0,0,0.08);
        --transicion:    all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --error:         #d32f2f;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Open Sans', sans-serif; background-color: var(--gris-claro); color: #333; line-height: 1.6; }
    h1, h2, h3, .logo { font-family: 'Montserrat', sans-serif; }
    header { background: var(--blanco); padding: 1rem 5%; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
    nav { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto; }
    .logo { font-size: 1.6rem; font-weight: 800; color: var(--verde-selva); text-transform: uppercase; text-decoration: none; }
    .menu { display: flex; gap: 2rem; }
    .menu a { color: var(--oscuro); text-decoration: none; font-weight: 600; }
    .user-welcome { display: flex; align-items: center; gap: 1rem; background: #f0f4f0; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.9rem; }
    .page-hero { background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1544924405-4f76263b655a?q=80&w=2070&auto=format&fit=crop') center/cover; height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; text-align: center; margin-bottom: 3rem; }
    .page-hero h1 { font-size: 2.8rem; margin-bottom: 0.5rem; }
    main { max-width: 1200px; margin: 0 auto; padding: 0 2rem; display: grid; grid-template-columns: 350px 1fr; gap: 2.5rem; }
    @media (max-width: 900px) { main { grid-template-columns: 1fr; } }
    .sidebar { display: flex; flex-direction: column; gap: 1.5rem; }
    .card-info { background: var(--blanco); border-radius: 20px; padding: 1.5rem; box-shadow: var(--sombra); border-left: 5px solid var(--amarillo-sol); }
    .card-info h3 { color: var(--verde-selva); margin-bottom: 1rem; display: flex; align-items: center; gap: 10px; }
    .card-info ul { list-style: none; }
    .card-info li { margin-bottom: 0.8rem; font-size: 0.95rem; display: flex; align-items: center; gap: 10px; }
    .card-info li i { color: var(--verde-selva); width: 20px; }
    .form-container { background: var(--blanco); border-radius: 25px; padding: 2.5rem; box-shadow: var(--sombra); }
    .form-container h2 { margin-bottom: 2rem; color: var(--oscuro); text-align: left; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 1.2rem; }
    .form-group label { display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--oscuro); font-size: 0.9rem; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.8rem 1rem; border: 2px solid #eee; border-radius: 12px; font-family: inherit; font-size: 1rem; transition: var(--transicion); }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--verde-selva); outline: none; background: #f0fdf4; }
    .full-width { grid-column: span 2; }
    @media (max-width: 600px) { .full-width { grid-column: span 1; } }
    .btn-submit { background: var(--verde-selva); color: white; padding: 1rem 2rem; border: none; border-radius: 12px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: var(--transicion); display: inline-flex; align-items: center; justify-content: center; gap: 10px; margin-top: 1rem; box-shadow: 0 5px 15px rgba(46,125,50,0.2); }
    .btn-submit:hover { background: var(--verde-oscuro); transform: translateY(-3px); }
    .alert { padding: 1rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 15px; }
    .alert-error { background: #fee2e2; color: var(--error); border: 1px solid #fecaca; }
    .field-error { color: var(--error); font-size: 0.8rem; margin-top: 0.4rem; font-weight: 600; }
    .input-error { border-color: var(--error) !important; }
    footer { background: var(--oscuro); color: white; text-align: center; padding: 3rem; margin-top: 5rem; font-size: 0.9rem; opacity: 0.9; }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="/" class="logo">🍃 ZooWonderland</a>
        <div class="menu">
            <a href="/">Inicio</a>
            <a href="/reservas/historial">Mis Reservas</a>
        </div>
        <div class="user-welcome">
            <i class="fa-solid fa-user-circle"></i>
            <strong>{{ $usuario->getNombreParaMostrar() }}</strong>
        </div>
    </nav>
</header>

<div class="page-hero">
    <h1>Tours Grupales</h1>
    <p>Reserva una experiencia educativa inolvidable</p>
</div>

<main>
    <aside class="sidebar">
        <div class="card-info">
            <h3><i class="fa-solid fa-circle-info"></i> Información</h3>
            <ul>
                <li><i class="fa-solid fa-users"></i> Grupos de 10 a 200 personas</li>
                <li><i class="fa-solid fa-calendar-day"></i> 3 días de anticipación</li>
                <li><i class="fa-solid fa-clock"></i> De 09:00 a 15:00</li>
                <li><i class="fa-solid fa-check-double"></i> Sujeto a confirmación</li>
            </ul>
        </div>

        <div class="card-info" style="border-left-color: var(--verde-selva);">
            <h3><i class="fa-solid fa-map-location-dot"></i> Recorridos</h3>
            @foreach ($recorridosGuiados as $r)
                <div style="margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px dashed #eee;">
                    <div style="font-weight:700; color:var(--verde-oscuro);">{{ $r['nombre'] }}</div>
                    <div style="font-size:0.85rem; color:#666;">
                        <i class="fa-solid fa-tag"></i> Bs. {{ number_format($r['precio'], 2) }}/persona
                    </div>
                </div>
            @endforeach
        </div>
    </aside>

    <div class="form-container">
        @if (session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        <h2><i class="fa-solid fa-pen-to-square"></i> Datos de la Reserva</h2>

        <form method="POST" action="/reservar">
            @csrf
            <div class="form-grid">

                <div class="form-group full-width">
                    <label>Nombre de la Institución / Empresa</label>
                    <input type="text" name="institucion" placeholder="Ej: Colegio San Agustín"
                           value="{{ old('institucion', $form['institucion'] ?? '') }}"
                           class="{{ $errors->has('institucion') ? 'input-error' : '' }}">
                    @error('institucion')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Tipo de Institución</label>
                    <select name="tipo_institucion">
                        @foreach (['colegio' => 'Colegio / Escuela', 'universidad' => 'Universidad', 'empresa' => 'Empresa', 'ong' => 'ONG', 'otro' => 'Otro'] as $val => $label)
                            <option value="{{ $val }}" {{ old('tipo_institucion', $form['tipo_institucion'] ?? '') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Número de Personas (min 10)</label>
                    <input type="number" name="numero_personas" min="10" max="200"
                           value="{{ old('numero_personas', $form['numero_personas'] ?? '') }}"
                           class="{{ $errors->has('numero_personas') ? 'input-error' : '' }}">
                    @error('numero_personas')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label>Nombre del Responsable</label>
                    <input type="text" name="contacto_nombre" placeholder="Nombre completo"
                           value="{{ old('contacto_nombre', $form['contacto_nombre'] ?? '') }}"
                           class="{{ $errors->has('contacto_nombre') ? 'input-error' : '' }}">
                    @error('contacto_nombre')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Teléfono de contacto</label>
                    <input type="text" name="contacto_telefono" placeholder="Ej: 71234567"
                           value="{{ old('contacto_telefono', $form['contacto_telefono'] ?? '') }}"
                           class="{{ $errors->has('contacto_telefono') ? 'input-error' : '' }}">
                    @error('contacto_telefono')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email de contacto</label>
                    <input type="email" name="contacto_email" placeholder="ejemplo@correo.com"
                           value="{{ old('contacto_email', $form['contacto_email'] ?? '') }}"
                           class="{{ $errors->has('contacto_email') ? 'input-error' : '' }}">
                    @error('contacto_email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label>Seleccionar Recorrido</label>
                    <select name="recorrido_id">
                        @foreach ($recorridosGuiados as $r)
                            <option value="{{ $r['id'] }}" {{ old('recorrido_id', $form['recorrido_id'] ?? '') == $r['id'] ? 'selected' : '' }}>
                                {{ $r['nombre'] }} - Bs. {{ number_format($r['precio'], 2) }}/pers.
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha de la Visita</label>
                    <input type="date" name="fecha" min="{{ $fechaMin }}"
                           value="{{ old('fecha', $form['fecha'] ?? '') }}"
                           class="{{ $errors->has('fecha') ? 'input-error' : '' }}">
                    @error('fecha')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Hora preferida (09:00 - 15:00)</label>
                    <input type="time" name="hora"
                           value="{{ old('hora', $form['hora'] ?? '') }}"
                           class="{{ $errors->has('hora') ? 'input-error' : '' }}">
                    @error('hora')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label>Observaciones o Requerimientos Especiales</label>
                    <textarea name="observaciones" rows="3" placeholder="Opcional...">{{ old('observaciones', $form['observaciones'] ?? '') }}</textarea>
                </div>

            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-calendar-check"></i>
                Confirmar Solicitud de Reserva
            </button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; {{ date('Y') }} ZooWonderland - Educación y Conservación</p>
</footer>

</body>
</html>
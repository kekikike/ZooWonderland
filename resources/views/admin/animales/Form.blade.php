<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($animal) ? 'Editar' : 'Nuevo' }} Animal - ZooWonderland</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
    <style>
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #fafafa; margin: 0; color: #333; }
        header { background: #2E7D32; color: white; }
        header nav { max-width: 1400px; margin: 0 auto; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; }
        header .logo { font-size: 1.6rem; font-weight: bold; text-decoration: none; color: white; }
        header .menu a { color: white; text-decoration: none; margin-left: 1.5rem; font-weight: 600; }
        header .menu a:hover { text-decoration: underline; }
        main { max-width: 800px; margin: 0 auto; padding: 3rem 5%; }
        .form-section { background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .form-section h2 { color: #2E7D32; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-family: inherit; font-size: 1rem; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #66BB6A; box-shadow: 0 0 0 3px rgba(102,187,106,0.2); }
        .btn { background: linear-gradient(135deg, #66BB6A, #FFC107); color: white; padding: 0.9rem 2rem; border: none; border-radius: 25px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; font-size: 1rem; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,193,7,0.4); }
        .back-link { display: inline-block; margin-top: 1.2rem; color: #555; text-decoration: none; font-weight: 600; }
        .back-link:hover { color: #2E7D32; }
        .alert-error { background: #fdecea; color: #c0392b; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 8px; margin-bottom: 1.2rem; }
        footer { text-align: center; padding: 2rem; background: #2E7D32; color: white; font-size: 0.9rem; margin-top: 3rem; }
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
        <div class="form-section">
            <h2><i class="fas fa-paw"></i> {{ isset($animal) ? 'Editar' : 'Nuevo' }} Animal</h2>

            @if(session('error'))
                <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif
            @if(isset($error))
                <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
            @endif

            <form action="{{ isset($animal) ? '/admin/animales/actualizar' : '/admin/animales/guardar' }}" method="POST">
                @csrf
                @if(isset($animal))
                    <input type="hidden" name="id" value="{{ $animal->id_animal }}">
                @endif

                <div class="form-group">
                    <label for="especie">Especie *</label>
                    <input type="text" id="especie" name="especie"
                           value="{{ old('especie', $animal->especie ?? '') }}" required
                           placeholder="Ej: Panthera leo">
                </div>

                <div class="form-group">
                    <label for="nombre_comun">Nombre común</label>
                    <input type="text" id="nombre_comun" name="nombre_comun"
                           value="{{ old('nombre_comun', $animal->nombre_comun ?? '') }}"
                           placeholder="Ej: León africano">
                </div>

                <div class="form-group">
                    <label for="habitat">Hábitat *</label>
                    <input type="text" id="habitat" name="habitat"
                           value="{{ old('habitat', $animal->habitat ?? '') }}" required
                           placeholder="Ej: Sabana africana">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" rows="4" required
                              placeholder="Descripción del animal...">{{ old('descripcion', $animal->descripcion ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        @foreach(['Activo', 'Inactivo', 'En observación'] as $opt)
                            <option value="{{ $opt }}"
                                {{ old('estado', $animal->estado ?? 'Activo') === $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_area">Área</label>
                    <select id="id_area" name="id_area">
                        <option value="0">-- Sin asignar --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}"
                                {{ old('id_area', $animal->id_area ?? 0) == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-{{ isset($animal) ? 'save' : 'plus-circle' }}"></i>
                    {{ isset($animal) ? 'Actualizar' : 'Guardar' }}
                </button>
            </form>

            <a href="/admin/animales" class="back-link">
                <i class="fas fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </main>

    <footer>
        &copy; {{ date('Y') }} ZooWonderland - Panel de Administración.
    </footer>
</body>
</html>
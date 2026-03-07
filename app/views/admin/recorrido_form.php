<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $recorrido ? 'Editar' : 'Nuevo'; ?> Recorrido - ZooWonderland</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-p1Cm..." crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 6px; }
        .btn { background: linear-gradient(135deg, #66BB6A, #FFC107); color: white; padding: 0.9rem 2rem; border: none; border-radius: 25px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); }
        .back-link { display: inline-block; margin-top: 1rem; color: #555; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .error-list { background: #ffe6e6; border: 1px solid #cc0000; padding: 1rem; margin-bottom: 1rem; border-radius: 6px; }
        .error-list li { color: #c0392b; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php?r=admin/dashboard" class="logo"><i class="fas fa-leaf"></i> ZooWonderland</a>
            <div class="menu">
                <a href="index.php?r=admin/dashboard" title="Dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="index.php?r=admin/recorridos" title="Gestionar Recorridos"><i class="fas fa-compass"></i> Recorridos</a>
                <a href="index.php?r=admin/animales" title="Gestionar Animales"><i class="fas fa-paw"></i> Animales</a>
                <a href="index.php?r=admin/areas" title="Gestionar Áreas"><i class="fas fa-shapes"></i> Áreas</a>
                <a href="index.php?r=admin/reservas" title="Reservas"><i class="fas fa-calendar-check"></i> Reservas</a>
                <a href="index.php?r=logout" title="Salir"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="form-section">
            <h2><?php echo $recorrido ? 'Editar' : 'Nuevo'; ?> Recorrido</h2>

            <?php if (!empty($errores)): ?>
                <div class="error-list">
                    <ul>
                        <?php foreach ($errores as $e): ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="index.php?r=admin/recorridos/<?php echo $action; ?>" method="post">
                <?php
                    // valores por defecto
                    $vals = [
                        'nombre' => '',
                        'tipo' => 'No Guiado',
                        'precio' => '',
                        'duracion' => '',
                        'capacidad' => '',
                        'areas' => []
                    ];
                    if ($recorrido) {
                        $vals['nombre']    = $recorrido['nombre'] ?? '';
                        $vals['tipo']      = $recorrido['tipo'] ?? 'No Guiado';
                        $vals['precio']    = $recorrido['precio'] ?? '';
                        $vals['duracion']  = $recorrido['duracion'] ?? '';
                        $vals['capacidad'] = $recorrido['capacidad'] ?? '';
                        // áreas asociadas
                        $vals['areas']     = $selectedAreas ?? [];
                    }
                    // si hubo datos previos en sesión (por error), se sobreescriben
                    if (!empty($datos)) {
                        $vals = array_merge($vals, $datos);
                    }
                ?>

                <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($vals['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo">
                        <?php foreach (['Guiado','No Guiado'] as $opt):
                            $sel = $opt === $vals['tipo'] ? 'selected' : '';
                            echo "<option value=\"$opt\" $sel>$opt</option>";
                        endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="precio">Precio (Bs.) *</label>
                    <input type="number" step="0.01" min="0" id="precio" name="precio" value="<?php echo htmlspecialchars($vals['precio']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="duracion">Duración (minutos)</label>
                    <input type="number" min="0" id="duracion" name="duracion" value="<?php echo htmlspecialchars($vals['duracion']); ?>">
                </div>
                <div class="form-group">
                    <label for="capacidad">Capacidad máxima</label>
                    <input type="number" min="0" id="capacidad" name="capacidad" value="<?php echo htmlspecialchars($vals['capacidad']); ?>">
                </div>
                <div class="form-group">
                    <label>Áreas *</label>
                    <?php foreach ($areas as $area):
                        $aid = is_array($area) ? $area['id_area'] : $area->id_area ?? $area->getId();
                        $aname = is_array($area) ? $area['nombre'] : $area->nombre ?? $area->getNombre();
                        $checked = in_array($aid, $vals['areas']) ? 'checked' : '';
                    ?>
                    <div><label><input type="checkbox" name="areas[]" value="<?php echo $aid; ?>" <?php echo $checked; ?>> <?php echo htmlspecialchars($aname); ?></label></div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn"><?php echo $recorrido ? 'Actualizar' : 'Guardar'; ?></button>
            </form>

            <a href="index.php?r=admin/recorridos" class="back-link"><i class="fas fa-arrow-left"></i> Volver a la lista</a>
        </div>
    </main>

    <footer style="text-align:center; padding:2rem; background:#2E7D32; color:white; font-size:0.9rem;">
        &copy; <?php echo date('Y'); ?> ZooWonderland - Panel de Administración.
    </footer>
</body>
</html>
<?php // app/Views/admin/evento_detalle.php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Evento - ZooWonderland</title>
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
        body { background: #fafafa; margin: 0; color: #333; }
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
        .btn-primary { background: linear-gradient(135deg, var(--selva-light), var(--jungle-gold)); color: white; padding: 0.9rem 2rem; border-radius: 25px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 0.8rem; box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3); letter-spacing: 0.5px; cursor: pointer; transition: var(--trans); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 6px 25px rgba(255, 179, 0, 0.4); }
        table { width: 100%; border-collapse: collapse; }
        thead { background: linear-gradient(90deg, #f5f5f5 0%, #efefef 100%); border-bottom: 3px solid var(--jungle-gold); }
        th, td { padding: 1.2rem; text-align: left; }
        th { font-weight: 700; color: var(--selva-dark); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        tbody tr { transition: var(--trans); }
        tbody tr:hover { background: linear-gradient(90deg, transparent, rgba(255,179,0,0.05), transparent); }
        .actions { display: flex; gap: 1rem; font-size: 0.85rem; }
        .actions a { text-decoration: none; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; transition: var(--trans); display: inline-flex; align-items: center; gap: 0.4rem; }
        .edit { color: var(--selva-light); background: rgba(46, 125, 50, 0.1); }
        .edit:hover { background: rgba(46, 125, 50, 0.25); transform: translateX(3px); }
        .delete { color: #ff6f6f; background: rgba(255, 111, 111, 0.1); }
        .delete:hover { background: rgba(255, 111, 111, 0.25); transform: translateX(3px); }
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
        .detalle-section { margin-bottom: 2rem; }
        .detalle-section h3 { color: var(--selva-dark); margin-bottom: 1rem; }
        .detalle-item { margin-bottom: 0.5rem; }
        .detalle-item strong { color: #555; }
        .actividades-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .actividades-table th, .actividades-table td { padding: 1rem; border-bottom: 1px solid #ddd; text-align: left; }
        .actividades-table th { background: #f5f5f5; font-weight: 700; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php?r=admin/dashboard" class="logo"><i class="fas fa-leaf"></i> ZooWonderland</a>
            <div class="menu">
                <!-- ... menú existente ... -->
                <a href="index.php?r=admin/eventos" title="Gestionar Eventos"><i class="fas fa-calendar-days"></i> Eventos</a>
                <!-- ... resto ... -->
            </div>
        </nav>
    </header>

    <main>
        <div class="page-header">
            <h1><i class="fas fa-calendar-days"></i> Detalle de Evento</h1>
            <p>Información completa del evento seleccionado</p>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-info-circle"></i> Detalles del Evento</h2>
                <a href="index.php?r=admin/eventos/editar&id=<?= $evento['id_evento'] ?>" class="btn-primary"><i class="fas fa-pen"></i> Editar Evento</a>
            </div>

            <div class="detalle-section">
                <div class="detalle-item"><strong>Nombre:</strong> <?= htmlspecialchars($evento['nombre_evento']) ?></div>
                <div class="detalle-item"><strong>Descripción:</strong> <?= htmlspecialchars($evento['descripcion']) ?></div>
                <div class="detalle-item"><strong>Fecha inicio:</strong> <?= date('d/m/Y H:i', strtotime($evento['fecha_inicio'])) ?></div>
                <div class="detalle-item"><strong>Fecha fin:</strong> <?= date('d/m/Y H:i', strtotime($evento['fecha_fin'])) ?></div>
                <div class="detalle-item"><strong>Costo:</strong> <?= $evento['tiene_costo'] ? 'Bs ' . number_format($evento['precio'], 2) : 'Gratuito' ?></div>
                <div class="detalle-item"><strong>Encargado:</strong> <?= htmlspecialchars($evento['encargado_nombre'] ?? 'No asignado') ?></div>
                <div class="detalle-item"><strong>Lugar:</strong> <?= htmlspecialchars($evento['lugar']) ?></div>
                <div class="detalle-item"><strong>Límite participantes:</strong> <?= $evento['limite_participantes'] ?? 'Sin límite' ?></div>
                <div class="detalle-item"><strong>Estado:</strong> <?= $evento['estado'] ? 'Activo' : 'Inactivo' ?></div>
            </div>

            <div class="detalle-section">
                <h3><i class="fas fa-tasks"></i> Actividades del Evento</h3>
                <?php if (empty($evento['actividades'])): ?>
                    <p>No hay actividades registradas para este evento.</p>
                <?php else: ?>
                    <table class="actividades-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Hora inicio</th>
                                <th>Hora fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evento['actividades'] as $act): ?>
                                <tr>
                                    <td><?= htmlspecialchars($act['nombre_actividad']) ?></td>
                                    <td><?= htmlspecialchars($act['descripcion'] ?? 'No especificada') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <a href="index.php?r=admin/eventos" class="btn-primary" style="display: block; text-align: center; margin-top: 2rem; width: fit-content; margin: 0 auto;">
                <i class="fas fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> ZooWonderland - Panel de Administración</p>
    </footer>
</body>
</html>
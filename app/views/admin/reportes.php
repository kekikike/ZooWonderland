<?php
$reservas      = $reservas      ?? [];
$compras       = $compras       ?? [];
$reportesGuias = $reportesGuias ?? [];
$errores       = $errores       ?? [];
$inicio        = $_GET['inicio'] ?? '';
$fin           = $_GET['fin']    ?? '';

// Totales reservas
$totalReservas = 0; $totalCupos = 0; $totalPagadasR = 0; $totalPendientesR = 0;
foreach ($reservas as $r) {
    $totalReservas   += $r['total_reservas'];
    $totalCupos      += $r['total_cupos'];
    $totalPagadasR   += $r['pagadas'];
    $totalPendientesR+= $r['pendientes'];
}

// Totales compras
$totalCompras = 0; $totalIngresos = 0.0; $totalPagadasC = 0.0; $totalPendientesC = 0.0;
foreach ($compras as $c) {
    $totalCompras     += $c['total_compras'];
    $totalIngresos    += $c['total_ingresos'];
    $totalPagadasC    += $c['pagadas'];
    $totalPendientesC += $c['pendientes'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes – ZooWonderland</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --verde-selva:   #2e7d32;
            --verde-oscuro:  #1b5e20;
            --verde-claro:   #e8f5e9;
            --amarillo-sol:  #ffca28;
            --naranja-tigre: #f57c00;
            --azul-cielo:    #0288d1;
            --gris-claro:    #f8faf8;
            --oscuro:        #0d3a1f;
            --blanco:        #ffffff;
            --sombra:        0 10px 30px rgba(0,0,0,0.08);
            --transicion:    all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Open Sans', sans-serif; 
            background: var(--gris-claro); 
            color: #333; 
            scroll-behavior: smooth;
            line-height: 1.6;
        }

        /* ── HEADER ── */
        .page-header {
            background: linear-gradient(135deg, var(--verde-oscuro) 0%, var(--verde-selva) 100%);
            padding: 2rem 5% 4.5rem 2rem;
            color: white;
            position: sticky;
            margin-bottom: 50px;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-content h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .back-link {
            background: rgba(255,255,255,0.15);
            padding: 8px 16px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transicion);
        }
        .back-link:hover { background: var(--amarillo-sol); color: var(--oscuro); }

        /* ── NAVEGACIÓN DE SECCIONES ── */
        .section-nav {
            max-width: 700px;
            width: 90%;                    /* ← para que no se salga en móvil */
            background: var(--blanco);
            border-radius: 50px;
            display: flex;
            padding: 6px;
            box-shadow: var(--sombra);
            position: fixed;
            top: 80px;                     /* ← distancia desde arriba (ajusta según tu header) */
            left: 50%;                     /* ← empieza en el centro */
            transform: translateX(-50%);   /* ← se desplaza la mitad de su propio ancho */
            z-index: 1001;
            align-items: center;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .nav-item {
            flex: 1;
            text-align: center;
            padding: 10px;
            text-decoration: none;
            color: var(--oscuro);
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 40px;
            transition: var(--transicion);
        }
        .nav-item:hover { background: var(--verde-claro); color: var(--verde-selva); }

        /* ── CONTENEDOR PRINCIPAL ── */
        .wrapper { max-width: 1200px; margin: 0 auto; padding: 0 2rem 5rem; }

        /* ── KPI CARDS ── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .kpi-card {
            background: var(--blanco);
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: var(--sombra);
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 5px solid var(--verde-selva);
        }
        .kpi-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .kpi-data span { font-size: 0.75rem; font-weight: 700; color: #888; text-transform: uppercase; }
        .kpi-data h3 { font-size: 1.4rem; color: var(--oscuro); font-family: 'Montserrat'; }

        /* ── FILTRO ── */
        .filter-card {
            background: var(--blanco);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--sombra);
            margin-bottom: 2.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            align-items: flex-end;
            gap: 1rem;
        }
        .filter-group label {
            display: block; font-size: 0.75rem; font-weight: 800; color: #666; margin-bottom: 6px;
        }
        .filter-group input {
            width: 100%; padding: 10px; border: 2px solid #eee; border-radius: 10px; font-family: inherit;
        }
        .btn-filtrar {
            background: var(--verde-selva); color: white; border: none; height: 45px;
            border-radius: 10px; font-weight: 700; cursor: pointer; transition: var(--transicion);
        }
        .btn-filtrar:hover { background: var(--verde-oscuro); transform: translateY(-2px); }

        /* ── TABLAS Y SECCIONES ── */
        .report-section { scroll-margin-top: 140px; margin-bottom: 4rem; }
        .section-title { 
            display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; 
        }
        .section-title h2 { font-size: 1.4rem; color: var(--oscuro); font-weight: 800; }
        
        .table-wrap {
            background: var(--blanco); border-radius: 16px; overflow: hidden; box-shadow: var(--sombra);
        }
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--oscuro); color: white; }
        th { padding: 14px; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 14px; border-bottom: 1px solid #f0f0f0; font-size: 0.95rem; }
        tr:last-child td { border-bottom: none; }
        
        .total-row { background: var(--verde-claro); font-weight: 800; color: var(--verde-oscuro); }

        /* CHIPS */
        .chip {
            padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;
        }
        .chip-green { background: #e6f4ea; color: #1e7e34; }
        .chip-orange { background: #fff4e5; color: #d97706; }

        .pdf-row { display: flex; gap: 10px; margin-bottom: 2rem; flex-wrap: wrap; }
        .btn-pdf {
            text-decoration: none; padding: 10px 20px; border-radius: 10px; font-weight: 700;
            font-size: 0.85rem; display: flex; align-items: center; gap: 8px; transition: var(--transicion);
        }
        .btn-pdf-res { background: #fce4ec; color: #c2185b; }
        .btn-pdf-com { background: #fff8e1; color: #f57f17; }
        .btn-pdf-gui { background: #e3f2fd; color: #1976d2; }
        .btn-pdf:hover { transform: scale(1.05); }
    </style>
</head>
<body>

<header class="page-header">
    <div class="header-content">
        <h1><i class="fa-solid fa-leaf"></i> ZooWonderland Reportes</h1>
        <a href="index.php?r=admin/dashboard" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Volver al Panel
        </a>
    </div>
</header>

<nav class="section-nav">
    <a href="#reservas" class="nav-item">Reservas</a>
    <a href="#compras" class="nav-item">Compras</a>
    <a href="#guias" class="nav-item">Guías</a>
</nav>

<div class="wrapper">

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon" style="background: var(--verde-claro); color: var(--verde-selva);"><i class="fa-solid fa-ticket"></i></div>
            <div class="kpi-data">
                <span>Total Reservas</span>
                <h3><?= $totalReservas ?></h3>
            </div>
        </div>
        <div class="kpi-card" style="border-left-color: var(--naranja-tigre);">
            <div class="kpi-icon" style="background: #fff3e0; color: var(--naranja-tigre);"><i class="fa-solid fa-sack-dollar"></i></div>
            <div class="kpi-data">
                <span>Ingresos Brutos</span>
                <h3>Bs <?= number_format($totalIngresos, 2) ?></h3>
            </div>
        </div>
        <div class="kpi-card" style="border-left-color: var(--azul-cielo);">
            <div class="kpi-icon" style="background: #e1f5fe; color: var(--azul-cielo);"><i class="fa-solid fa-users-viewfinder"></i></div>
            <div class="kpi-data">
                <span>Cupos Totales</span>
                <h3><?= $totalCupos ?></h3>
            </div>
        </div>
    </div>

    <form method="GET" action="index.php">
        <input type="hidden" name="r" value="admin/reportes">
        <div class="filter-card">
            <div class="filter-group">
                <label>DESDE</label>
                <input type="date" name="inicio" value="<?= $inicio ?>">
            </div>
            <div class="filter-group">
                <label>HASTA</label>
                <input type="date" name="fin" value="<?= $fin ?>">
            </div>
            <button type="submit" class="btn-filtrar">
                <i class="fa-solid fa-sync"></i> Actualizar Reporte
            </button>
            <?php if ($inicio || $fin): ?>
            <a href="index.php?r=admin/reportes" style="
                display: flex; align-items: center; gap: 6px;
                padding: 0 16px; height: 45px; border-radius: 10px;
                background: #fee2e2; color: #b91c1c;
                text-decoration: none; font-weight: 700; font-size: 0.85rem;
                transition: var(--transicion);
            " onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fee2e2'">
                <i class="fa-solid fa-xmark"></i> Limpiar
            </a>
    <?php endif; ?>
        </div>
    </form>

    <div class="pdf-row">
        <a href="#" class="btn-pdf btn-pdf-res"><i class="fa-solid fa-file-pdf"></i> Reservas</a>
        <a href="#" class="btn-pdf btn-pdf-com"><i class="fa-solid fa-file-pdf"></i> Compras</a>
        <a href="#" class="btn-pdf btn-pdf-gui"><i class="fa-solid fa-file-pdf"></i> Guías</a>
    </div>

    <section id="reservas" class="report-section">
        <div class="section-title">
            <div class="kpi-icon" style="background: var(--verde-selva); color: white; width: 35px; height: 35px; font-size: 1rem;"><i class="fa-solid fa-calendar"></i></div>
            <h2>Reporte Reservas</h2>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cant. Reservas</th>
                        <th>Total Cupos</th>
                        <th>Estado Pagado</th>
                        <th>Estado Pendiente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $r): ?>
                    <tr>
                        <td><strong><?= $r['fecha'] ?></strong></td>
                        <td><?= $r['total_reservas'] ?></td>
                        <td><?= $r['total_cupos'] ?></td>
                        <td><span class="chip chip-green"><?= $r['pagadas'] ?></span></td>
                        <td><span class="chip chip-orange"><?= $r['pendientes'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td>TOTAL FINAL</td>
                        <td><?= $totalReservas ?></td>
                        <td><?= $totalCupos ?></td>
                        <td><?= $totalPagadasR ?></td>
                        <td><?= $totalPendientesR ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section id="compras" class="report-section">
        <div class="section-title">
            <div class="kpi-icon" style="background: var(--naranja-tigre); color: white; width: 35px; height: 35px; font-size: 1rem;"><i class="fa-solid fa-cart-flatbed"></i></div>
            <h2>Reporte Compras</h2>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Transacciones</th>
                        <th>Ingreso (Bs)</th>
                        <th>Efectivo Pagado</th>
                        <th>Por Cobrar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compras as $c): ?>
                    <tr>
                        <td><?= $c['fecha'] ?></td>
                        <td><?= $c['total_compras'] ?></td>
                        <td>Bs <?= number_format($c['total_ingresos'], 2) ?></td>
                        <td><span class="chip chip-green">Bs <?= number_format($c['pagadas'], 2) ?></span></td>
                        <td><span class="chip chip-orange">Bs <?= number_format($c['pendientes'], 2) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td>RESUMEN FINANCIERO</td>
                        <td><?= $totalCompras ?></td>
                        <td>Bs <?= number_format($totalIngresos, 2) ?></td>
                        <td>Bs <?= number_format($totalPagadasC, 2) ?></td>
                        <td>Bs <?= number_format($totalPendientesC, 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section id="guias" class="report-section">
        <div class="section-title">
            <div class="kpi-icon" style="background: var(--azul-cielo); color: white; width: 35px; height: 35px; font-size: 1rem;"><i class="fa-solid fa-map-location-dot"></i></div>
            <h2>Reportes Guia</h2>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha Reporte</th>
                        <th>ID Asignación</th>
                        <th>Observaciones de Campo</th>
                        <th>Estado de Guía</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reportesGuias as $g): ?>
                    <tr>
                        <td><?= $g['fecha_reporte'] ?></td>
                        <td>#<?= $g['id_guia_recorrido'] ?></td>
                        <td style="color: #666; font-size: 0.85rem; max-width: 300px;"><?= $g['observaciones'] ?: 'Sin novedades.' ?></td>
                        <td><?= ($g['estado'] == 1) ? '<span class="chip chip-green">Activo</span>' : '<span class="chip chip-orange">Inactivo</span>' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const inicio = document.querySelector('input[name="inicio"]').value;
    const fin    = document.querySelector('input[name="fin"]').value;

    if (inicio && fin && inicio > fin) {
        e.preventDefault();
        alert('⚠️ La fecha de inicio no puede ser mayor que la fecha fin.');
        return false;
    }
});
</script>
</body>
</html>
<?php
// app/Views/guias/dashboard.php
declare(strict_types=1);
require_once APP_PATH . '/Views/guias/partials/tabs.php';  // ← nuevo
$currentTab = 'recorridos';  // marca esta pestaña como activa

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Guía - ZooWonderland</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background:#f8f5f0; margin:0; padding:20px; }
        header { background:#a3712a; color:white; padding:1rem; text-align:center; }
        h1 { margin:0; }
        .welcome { text-align:center; margin:2rem 0; font-size:1.3rem; color:#68672e; }
        .card {
            background:white;
            border-radius:12px;
            padding:1.5rem;
            margin-bottom:1.5rem;
            box-shadow:0 4px 15px rgba(0,0,0,0.1);
        }
        .fecha { font-size:1.5rem; color:#a3712a; font-weight:bold; }
        .info { margin:10px 0; color:#555; }
        .btn { display:inline-block; padding:10px 20px; background:#7eaeb0; color:white; border-radius:6px; text-decoration:none; margin-top:1rem; }
        .btn:hover { background:#5e8a8e; }
    </style>
</head>
<body>

<header>
    <h1>Panel del Guía</h1>
</header>

<div class="welcome">
    Bienvenido, <?= htmlspecialchars($user->getNombreParaMostrar()) ?>  
    <br><small>Estos son tus recorridos asignados</small>
</div>

<?php if (empty($recorridosAsignados)): ?>
    <div class="card" style="text-align:center;">
        <p>No tienes recorridos asignados en este momento.</p>
    </div>
<?php else: ?>
    <?php foreach ($recorridosAsignados as $r): ?>
        <div class="card">
            <div class="fecha">
                <?= date('d/m/Y', strtotime($r['fecha_asignacion'])) ?>
            </div>
            <h3><?= htmlspecialchars($r['nombre']) ?> <small>(<?= htmlspecialchars($r['tipo']) ?>)</small></h3>
            
            <div class="info">
                <strong>Duración:</strong> <?= $r['duracion'] ?> minutos
            </div>
            <div class="info">
                <strong>Personas inscritas:</strong> 
                <?= $r['personas_asignadas'] ?> / <?= $r['capacidad'] ?>
            </div>

            <!-- Puedes agregar aquí un botón para ver detalle -->
            <a href="index.php?r=guias/detalle-recorrido&id=<?= $r['id_recorrido'] ?>" class="btn">
                Ver detalle del recorrido
            </a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<p style="text-align:center; margin-top:2rem;">
    <a href="index.php">← Volver al inicio (público)</a> | 
    <a href="index.php?r=logout">Cerrar sesión</a>
</p>

</body>
</html>
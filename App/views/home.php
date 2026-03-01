<?php
// app/Views/home.php
$isLoggedIn = $isLoggedIn ?? false;
$esCliente  = $esCliente  ?? false;
$user       = $user       ?? null;
$recorridos = $recorridos ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZooWonderland - Inicio</title>
    <style>
        :root {
            --primary: #a3712a; --light: #ffe2a0; --accent: #bfb641;
            /* ... resto de tu paleta de colores ... */
        }
        /* Copia aquí los estilos que tenías antes: nav, banner, cards, etc. */
        nav { background: linear-gradient(135deg, var(--primary), #977c66); color: white; }
        .banner { background: url('https://images.unsplash.com/photo-1564760054-906debeff642') center/cover; height: 60vh; }
        .card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-reservar { background: var(--accent); color: #333; }
        .btn-comprar  { background: #7eaeb0; color: white; }
    </style>
</head>
<body>

<header>
    <nav style="padding:1rem; display:flex; justify-content:space-between; align-items:center;">
        <div class="logo" style="font-size:1.8rem; font-weight:bold;">ZooWonderland</div>
        <div style="display:flex; gap:2rem;">
            <a href="/" style="color:white;">Inicio</a>
            <a href="#nosotros" style="color:white;">Nosotros</a>
            <a href="#visitanos" style="color:white;">Visítanos</a>
        </div>

        <div>
            <?php if ($isLoggedIn && $user): ?>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                    <span>Bienvenido, <strong><?= htmlspecialchars($user->getNombreParaMostrar() ?? $user->nombre_usuario) ?></strong></span>
                    <div style="font-size:0.9rem;">
                        <a href="index.php?r=login" style="color:#ffe2a0; margin-right:1rem;">Mi perfil</a>
                        <a href="index.php?r=logout" style="color:#ffe2a0;">Cerrar sesión</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?r=login" style="background:var(--accent); color:#333; padding:8px 16px; border-radius:6px; text-decoration:none; margin-right:0.8rem;">Iniciar sesión</a>
                <a href="index.php?r=registro" style="background:white; color:var(--primary); padding:8px 16px; border-radius:6px; text-decoration:none;">Registrarse</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<div class="banner" style="height:60vh; display:flex; align-items:center; justify-content:center; color:white; text-shadow:2px 2px 8px black;">
    <div style="text-align:center;">
        <h1 style="font-size:3.5rem; margin-bottom:1rem;">Bienvenidos a ZooWonderland</h1>
        <p style="font-size:1.4rem;">Descubre la magia de la naturaleza y vive experiencias únicas</p>
    </div>
</div>

<main style="max-width:1200px; margin:3rem auto; padding:0 1.5rem;">
    <section id="nosotros" style="margin-bottom:4rem; text-align:center;">
        <h2 style="color:var(--primary);">Nosotros</h2>
        <p>ZooWonderland fue fundado en 1998 con la misión de conservar, educar y conectar...</p>
    </section>

    <section id="visitanos">
        <h2 style="color:var(--primary); text-align:center;">Visítanos - Nuestros Recorridos</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:2rem; margin-top:2rem;">
            <?php foreach ($recorridos as $r): ?>
                <div class="card" style="background:white; border-radius:12px; overflow:hidden;">
                    <div style="background:var(--primary); color:white; padding:1.2rem; text-align:center; font-weight:bold;">
                        <?= htmlspecialchars($r['nombre']) ?>
                    </div>
                    <div style="padding:1.5rem;">
                        <p><strong>Tipo:</strong> <?= htmlspecialchars($r['tipo']) ?></p>
                        <p><strong>Precio:</strong> Bs. <?= number_format($r['precio'], 2) ?></p>
                        <p><strong>Duración:</strong> <?= $r['duracion'] ?> min</p>

                        <div style="text-align:center; margin-top:1.5rem;">
                            <?php if ($esCliente): ?>
                                <a href="/reservas/crear?recorrido=<?= $r['id_recorrido'] ?>" class="btn btn-reservar" style="padding:10px 20px; border-radius:6px; text-decoration:none; margin:0 0.5rem;">Reservar</a>
                                <a href="/compras/crear?recorrido=<?= $r['id_recorrido'] ?>" class="btn btn-comprar" style="padding:10px 20px; border-radius:6px; text-decoration:none; margin:0 0.5rem;">Comprar Ticket</a>
                            <?php elseif ($isLoggedIn): ?>
                                <p style="color:#d32f2f; font-weight:500;">Solo clientes pueden reservar o comprar</p>
                            <?php else: ?>
                                <a href="index.php?r=login" class="btn" style="background:#68672e; color:white; padding:10px 20px; border-radius:6px; text-decoration:none;">Inicia sesión para reservar o comprar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer style="background:#977c66; color:white; text-align:center; padding:2rem; margin-top:4rem;">
    <p>© <?= date('Y') ?> ZooWonderland - Todos los derechos reservados</p>
</footer>

</body>
</html>
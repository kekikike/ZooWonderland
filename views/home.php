<?php
// app/Views/home.php
declare(strict_types=1);

// Variables que llegan del controlador o front controller
$isLoggedIn = $isLoggedIn ?? false;
$user = $user ?? null;
$recorridos = $recorridos ?? []; // array de recorridos (puedes cargarlos aquí o desde controlador)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Bienvenidos</title>
    <!-- Aquí pones tus estilos CSS (puedes moverlos a un archivo assets/css/style.css y linkearlo) -->
    <style>
        /* Copia aquí los estilos que tenías en el index.php anterior */
        :root {
            --color-primary: #a3712a;
            --color-light: #ffe2a0;
            --color-accent: #bfb641;
            /* ... resto de colores ... */
        }
        /* ... todos tus estilos de banner, cards, nav, etc. ... */
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo"><?= APP_NAME ?></div>
        <ul class="nav-links">
            <li><a href="/">Inicio</a></li>
            <li><a href="#nosotros">Nosotros</a></li>
            <li><a href="#visitanos">Visítanos</a></li>
        </ul>

        <div class="auth-section">
            <?php if ($isLoggedIn && $user): ?>
                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 6px;">
                    <span style="color: white; font-weight: 500;">
                        Bienvenido, <?= htmlspecialchars($user->getNombreParaMostrar()) ?>
                    </span>
                    <div style="display: flex; gap: 1rem;">
                        <a href="/perfil" style="color: #ffe2a0; font-size: 0.95rem;">Mi perfil</a>
                        <a href="/logout" style="color: #ffe2a0; font-size: 0.95rem;">Cerrar sesión</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/login" class="btn" style="background: var(--color-accent); color: var(--color-dark);">Iniciar sesión</a>
                <a href="/register" class="btn" style="background: white; color: var(--color-primary);">Registrarse</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<!-- Banner -->
<div class="banner" id="inicio">
    <div class="banner-content">
        <h1>Bienvenidos a ZooWonderland</h1>
        <p>Descubre la magia de la naturaleza...</p>
    </div>
</div>

<main>
    <!-- Sección Nosotros -->
    <section id="nosotros">
        <h2>Nosotros</h2>
        <p>Historia del zoológico...</p>
    </section>

    <!-- Sección Visítanos con recorridos -->
    <section id="visitanos">
        <h2>Visítanos - Nuestros Recorridos</h2>
        <div class="recorridos-grid">
            <?php foreach ($recorridos as $r): ?>
                <div class="card">
                    <div class="card-header"><?= htmlspecialchars($r['nombre']) ?></div>
                    <div class="card-body">
                        <p>Tipo: <?= htmlspecialchars($r['tipo']) ?></p>
                        <p>Precio: Bs. <?= number_format($r['precio'], 2) ?></p>
                        <?php if ($isLoggedIn): ?>
                            <a href="/reservas/crear?recorrido=<?= $r['id'] ?>" class="btn">Reservar</a>
                            <a href="/compras/crear?recorrido=<?= $r['id'] ?>" class="btn">Comprar Ticket</a>
                        <?php else: ?>
                            <a href="/login" class="btn btn-login">Inicia sesión para reservar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer>
    <p>© <?= date('Y') ?> <?= APP_NAME ?> - Todos los derechos reservados</p>
</footer>

</body>
</html>
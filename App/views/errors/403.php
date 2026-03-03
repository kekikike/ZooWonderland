<?php // app/Views/errors/403.php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso Denegado</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background:#f8f5f0; margin:0; height:100vh; display:flex; align-items:center; justify-content:center; color:#333; }
        .container { text-align:center; max-width:600px; padding:3rem; background:white; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.15); }
        h1 { color:#c62828; font-size:6rem; margin:0; line-height:1; }
        h2 { color:#a3712a; margin:1rem 0; font-size:2rem; }
        p { font-size:1.2rem; margin:1.5rem 0; }
        a { color:#7eaeb0; font-weight:bold; text-decoration:none; }
        a:hover { text-decoration:underline; }
    </style>
</head>
<body>
<div class="container">
    <h1>403</h1>
    <h2>Acceso Denegado</h2>
    <p>No tienes permiso para acceder a esta sección.</p>
    <p>Es posible que necesites otro rol o iniciar sesión nuevamente.</p>
    <a href="index.php">Volver al inicio</a>
</div>
</body>
</html>
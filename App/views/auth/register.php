<?php // app/Views/auth/register.php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ZooWonderland</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(to bottom, #ffe2a0, #fffaf0); margin:0; display:flex; justify-content:center; align-items:center; min-height:100vh; }
        .container { background:white; padding:2.5rem; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.15); width:100%; max-width:500px; }
        h1 { color:#a3712a; text-align:center; margin-bottom:1.8rem; }
        .error { background:#ffebee; color:#c62828; padding:0.8rem; border-radius:6px; margin-bottom:1rem; text-align:center; }
        .success { background:#e8f5e9; color:#2e7d32; padding:0.8rem; border-radius:6px; margin-bottom:1rem; text-align:center; }
        .form-group { margin-bottom:1.4rem; }
        label { display:block; margin-bottom:0.5rem; color:#555; font-weight:500; }
        input { width:100%; padding:12px; border:1px solid #ccc; border-radius:6px; font-size:1rem; }
        input:focus { outline:none; border-color:#bfb641; box-shadow:0 0 0 3px rgba(191,182,65,0.2); }
        button { width:100%; padding:14px; background:#a3712a; color:white; border:none; border-radius:6px; font-size:1.1rem; cursor:pointer; }
        button:hover { background:#8c5e22; }
        .login-link { text-align:center; margin-top:1.5rem; }
        .login-link a { color:#7eaeb0; text-decoration:none; font-weight:500; }
    </style>
</head>
<body>

<div class="container">
    <h1>Crear Cuenta</h1>

    <?php if (isset($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?r=registro">
        <div class="form-group">
            <label for="nombre1">Nombre</label>
            <input type="text" id="nombre1" name="nombre1" required value="<?= htmlspecialchars($old['nombre1'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="apellido1">Apellido</label>
            <input type="text" id="apellido1" name="apellido1" required value="<?= htmlspecialchars($old['apellido1'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo" required value="<?= htmlspecialchars($old['correo'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="nombre_usuario">Nombre de usuario</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required value="<?= htmlspecialchars($old['nombre_usuario'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirmar contraseña</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>

        <button type="submit">Registrarse</button>
    </form>

    <div class="login-link">
        ¿Ya tienes cuenta? <a href="index.php?r=login">Inicia sesión</a>
    </div>
</div>

</body>
</html>
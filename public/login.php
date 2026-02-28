<?php
// app/Views/auth/login.php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ZooWonderland</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom, #ffe2a0, #fffaf0);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 420px;
        }
        h1 {
            color: #a3712a;
            text-align: center;
            margin-bottom: 1.8rem;
        }
        .form-group {
            margin-bottom: 1.4rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        input:focus {
            outline: none;
            border-color: #bfb641;
            box-shadow: 0 0 0 3px rgba(191,182,65,0.2);
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: #a3712a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #8c5e22;
        }
        .error {
            color: #d32f2f;
            font-size: 0.95rem;
            margin-top: -0.8rem;
            margin-bottom: 1.2rem;
            text-align: center;
        }
        .link {
            text-align: center;
            margin-top: 1.2rem;
        }
        .link a {
            color: #7eaeb0;
            text-decoration: none;
            font-weight: 500;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Iniciar Sesión</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/login">
        <div class="form-group">
            <label for="login">Usuario o Email</label>
            <input type="text" id="login" name="login" required 
                   placeholder="Ej: juanperez o juan@ejemplo.com" 
                   value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn">Ingresar</button>
    </form>

    <div class="link">
        ¿No tienes cuenta? <a href="/register">Regístrate aquí</a>
    </div>
</div>

</body>
</html>
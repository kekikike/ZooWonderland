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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #ffe2a0, #fffaf0);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 420px;
        }
        h1 {
            color: #a3712a;
            text-align: center;
            margin-bottom: 1.8rem;
        }
        .error {
            background: #ffebee;
            color: #c62828;
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            text-align: center;
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
        input {
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
        button {
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
        button:hover {
            background: #8c5e22;
        }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .register-link a {
            color: #7eaeb0;
            text-decoration: none;
            font-weight: 500;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Iniciar Sesión</h1>

    <?php if (isset($error) && $error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

<form method="POST" action="index.php?r=login">
        <div class="form-group">
            <label for="login">Usuario o Correo</label>
            <input type="text" id="login" name="login" required 
                   placeholder="Ej: juanperez o juan@ejemplo.com"
                   value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Ingresar</button>
    </form>

    <div class="register-link">
        ¿No tienes cuenta? <a href="index.php?r=registro">Regístrate aquí</a>
    </div>
</div>

</body>
</html>
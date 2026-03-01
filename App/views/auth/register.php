<?php
// app/Views/auth/register.php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ZooWonderland</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom, #ffe2a0, #fffaf0);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 520px;
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
            font-size: 0.95rem;
        }
        .success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1.4rem;
            position: relative;
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
        .field-error {
            color: #c62828;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: block;
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
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .login-link a {
            color: #7eaeb0;
            text-decoration: none;
            font-weight: 500;
        }
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

    <form id="registerForm" method="POST" action="index.php?r=registro">
        <!-- Nombre 1 -->
        <div class="form-group">
            <label for="nombre1">Primer nombre *</label>
            <input type="text" id="nombre1" name="nombre1" required 
                   value="<?= htmlspecialchars($old['nombre1'] ?? '') ?>" 
                   placeholder="Ej: Juan">
            <span class="field-error" id="error-nombre1"></span>
        </div>

        <!-- Nombre 2 (opcional) -->
        <div class="form-group">
            <label for="nombre2">Segundo nombre</label>
            <input type="text" id="nombre2" name="nombre2" 
                   value="<?= htmlspecialchars($old['nombre2'] ?? '') ?>" 
                   placeholder="Ej: Carlos">
            <span class="field-error" id="error-nombre2"></span>
        </div>

        <!-- Apellido paterno -->
        <div class="form-group">
            <label for="apellido1">Apellido paterno *</label>
            <input type="text" id="apellido1" name="apellido1" required 
                   value="<?= htmlspecialchars($old['apellido1'] ?? '') ?>" 
                   placeholder="Ej: Pérez">
            <span class="field-error" id="error-apellido1"></span>
        </div>

        <!-- Apellido materno (opcional) -->
        <div class="form-group">
            <label for="apellido2">Apellido materno</label>
            <input type="text" id="apellido2" name="apellido2" 
                   value="<?= htmlspecialchars($old['apellido2'] ?? '') ?>" 
                   placeholder="Ej: Gómez">
            <span class="field-error" id="error-apellido2"></span>
        </div>

        <!-- CI -->
        <div class="form-group">
            <label for="ci">C.I. (7 a 13 dígitos) *</label>
            <input type="text" id="ci" name="ci" required maxlength="13" 
                   value="<?= htmlspecialchars($old['ci'] ?? '') ?>" 
                   placeholder="Ej: 12345678">
            <span class="field-error" id="error-ci"></span>
        </div>

        <!-- Correo -->
        <div class="form-group">
            <label for="correo">Correo electrónico *</label>
            <input type="email" id="correo" name="correo" required 
                   value="<?= htmlspecialchars($old['correo'] ?? '') ?>" 
                   placeholder="ejemplo@correo.com">
            <span class="field-error" id="error-correo"></span>
        </div>

        <!-- Teléfono -->
        <div class="form-group">
            <label for="telefono">Teléfono (8 a 15 dígitos) *</label>
            <input type="tel" id="telefono" name="telefono" required maxlength="15" 
                   value="<?= htmlspecialchars($old['telefono'] ?? '') ?>" 
                   placeholder="Ej: 77777777 o +59177777777">
            <span class="field-error" id="error-telefono"></span>
        </div>

        <!-- Nombre de usuario -->
        <div class="form-group">
            <label for="nombre_usuario">Nombre de usuario *</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required 
                   value="<?= htmlspecialchars($old['nombre_usuario'] ?? '') ?>" 
                   placeholder="Ej: juanperez123">
            <span class="field-error" id="error-nombre_usuario"></span>
        </div>

        <!-- Contraseña -->
        <div class="form-group">
            <label for="password">Contraseña *</label>
            <input type="password" id="password" name="password" required>
            <span class="field-error" id="error-password"></span>
        </div>

        <!-- Confirmar contraseña -->
        <div class="form-group">
            <label for="password_confirm">Confirmar contraseña *</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
            <span class="field-error" id="error-password_confirm"></span>
        </div>

        <button type="submit" id="submitBtn">Registrarse</button>
    </form>

    <div class="login-link">
        ¿Ya tienes cuenta? <a href="index.php?r=login">Inicia sesión</a>
    </div>
</div>

<script>
// Validación en tiempo real + al enviar
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');

    // Referencias a campos y errores
    const fields = {
        nombre1: document.getElementById('nombre1'),
        nombre2: document.getElementById('nombre2'),
        apellido1: document.getElementById('apellido1'),
        apellido2: document.getElementById('apellido2'),
        ci: document.getElementById('ci'),
        correo: document.getElementById('correo'),
        telefono: document.getElementById('telefono'),
        nombre_usuario: document.getElementById('nombre_usuario'),
        password: document.getElementById('password'),
        password_confirm: document.getElementById('password_confirm')
    };

    const errors = {
        nombre1: document.getElementById('error-nombre1'),
        nombre2: document.getElementById('error-nombre2'),
        apellido1: document.getElementById('error-apellido1'),
        apellido2: document.getElementById('error-apellido2'),
        ci: document.getElementById('error-ci'),
        correo: document.getElementById('error-correo'),
        telefono: document.getElementById('error-telefono'),
        nombre_usuario: document.getElementById('error-nombre_usuario'),
        password: document.getElementById('error-password'),
        password_confirm: document.getElementById('error-password_confirm')
    };

    // Validación en tiempo real (al escribir)
    Object.keys(fields).forEach(key => {
        fields[key].addEventListener('input', () => validateField(key));
        fields[key].addEventListener('blur', () => validateField(key));
    });

    // Validación al enviar
    form.addEventListener('submit', (e) => {
        let hasError = false;

        Object.keys(fields).forEach(key => {
            if (!validateField(key)) {
                hasError = true;
            }
        });

        if (hasError) {
            e.preventDefault();
            submitBtn.disabled = true;
            setTimeout(() => { submitBtn.disabled = false; }, 2000);
        }
    });

    function validateField(fieldName) {
        const field = fields[fieldName];
        const errorEl = errors[fieldName];
        let value = field.value.trim();
        let isValid = true;
        let message = '';

        switch (fieldName) {
            case 'nombre1':
            case 'apellido1':
                if (!value) {
                    message = 'Este campo es obligatorio';
                    isValid = false;
                } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) {
                    message = 'Solo letras y espacios permitidos';
                    isValid = false;
                }
                break;

            case 'nombre2':
            case 'apellido2':
                // opcionales, pero si se escriben → solo letras
                if (value && !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value)) {
                    message = 'Solo letras y espacios permitidos';
                    isValid = false;
                }
                break;

            case 'ci':
                if (!/^\d{7,13}$/.test(value)) {
                    message = 'Debe tener entre 7 y 13 dígitos numéricos';
                    isValid = false;
                }
                break;

            case 'correo':
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    message = 'Correo electrónico inválido';
                    isValid = false;
                }
                break;

            case 'telefono':
                if (!/^\+?\d{8,15}$/.test(value.replace(/\s/g, ''))) {
                    message = 'Debe tener entre 8 y 15 dígitos (puede incluir +)';
                    isValid = false;
                }
                break;

            case 'nombre_usuario':
                if (!value) {
                    message = 'Obligatorio';
                    isValid = false;
                } else if (value.length < 4) {
                    message = 'Mínimo 4 caracteres';
                    isValid = false;
                }
                break;

            case 'password':
                if (value.length < 8) {
                    message = 'Mínimo 8 caracteres';
                    isValid = false;
                } else if (!/[A-Z]/.test(value)) {
                    message = 'Debe contener al menos una mayúscula';
                    isValid = false;
                } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(value)) {
                    message = 'Debe contener al menos un carácter especial';
                    isValid = false;
                }
                break;

            case 'password_confirm':
                if (value !== fields.password.value) {
                    message = 'Las contraseñas no coinciden';
                    isValid = false;
                }
                break;
        }

        errorEl.textContent = message;
        field.style.borderColor = isValid ? '#ccc' : '#c62828';
        return isValid;
    }
});
</script>

</body>
</html>
<?php
// app/Controllers/AuthController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    /**
     * Muestra el formulario de login
     */
    public function showLogin(): void
    {
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']); // Limpiar después de mostrar

        require_once APP_PATH . '/Views/auth/login.php';
    }

    /**
     * Procesa el formulario POST de login
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $login    = trim($_POST['login'] ?? '');
        $password = $_POST['contrasena'] ?? '';

        if (empty($login) || empty($password)) {
            $_SESSION['login_error'] = 'Debes completar ambos campos.';
            header('Location: /login');
            exit;
        }

        $result = $this->auth->attempt($login, $password);

        if ($result['success']) {
            // Redirigir a la página principal tras login exitoso
            header('Location: /');
            \Core\Session::regenerate();
            exit;
        }

        // Error → guardar mensaje y volver al formulario
        $_SESSION['login_error'] = $result['message'];
        header('Location: /login');
        exit;
    }

    /**
     * Cierra la sesión y redirige
     */
    public function logout(): void
    {
        $this->auth->logout();
        header('Location: /');
        exit;
    }
}
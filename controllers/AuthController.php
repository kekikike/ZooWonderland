<?php
// app/Controllers/AuthController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController {
    private AuthService $auth;

    public function __construct() {
        $this->auth = new AuthService();
    }

    public function showLogin(): void {
        require_once APP_PATH . '/Views/auth/login.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $login    = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';

        $result = $this->auth->attempt($login, $password);

        if ($result['success']) {
            header('Location: /');
            exit;
        }

        $error = $result['message'];
        require_once APP_PATH . '/Views/auth/login.php';
    }

    public function logout(): void {
        $this->auth->logout();
        header('Location: /');
        exit;
    }
}
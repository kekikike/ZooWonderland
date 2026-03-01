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

    public function showLogin(): void
    {
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require_once APP_PATH . '/Views/auth/login.php';
    }

    public function login(): void
    {
        // Si no es POST → redirigir a formulario (ruta correcta)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?r=login');
            exit;
        }

        $login    = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';  // ← nota: aquí usabas 'contrasena', debe coincidir con el name del input

        if (empty($login) || empty($password)) {
            $_SESSION['login_error'] = 'Debes completar ambos campos.';
            header('Location: index.php?r=login');
            exit;
        }

        $result = $this->auth->attempt($login, $password);

        if ($result['success']) {
            \Core\Session::regenerate();  // regenerar ID tras login exitoso
            header('Location: index.php');  // ← redirige a principal (ruta raíz)
            exit;
        }

        // Error
        $_SESSION['login_error'] = $result['message'];
        header('Location: index.php?r=login');
        exit;
    }

    public function logout(): void
    {
        $this->auth->logout();
        header('Location: index.php');
        exit;
    }

    public function showRegister(): void
{
    $error   = $_SESSION['register_error'] ?? null;
    $success = $_SESSION['register_success'] ?? null;
    $old     = $_SESSION['register_old'] ?? [];

    unset($_SESSION['register_error'], $_SESSION['register_success'], $_SESSION['register_old']);

    require_once APP_PATH . '/Views/auth/register.php';
}

public function register(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?r=registro');
        exit;
    }

    $registerService = new \App\Services\RegisterService();

    $data = [
        'nombre1'       => trim($_POST['nombre1'] ?? ''),
        'apellido1'     => trim($_POST['apellido1'] ?? ''),
        'correo'        => trim($_POST['correo'] ?? ''),
        'nombre_usuario'=> trim($_POST['nombre_usuario'] ?? ''),
        'password'      => $_POST['password'] ?? '',
        'password_confirm' => $_POST['password_confirm'] ?? '',
        // campos opcionales
        'nombre2'       => trim($_POST['nombre2'] ?? ''),
        'apellido2'     => trim($_POST['apellido2'] ?? ''),
        'ci'            => $_POST['ci'] ?? null,
        'telefono'      => trim($_POST['telefono'] ?? ''),
        'nit'           => trim($_POST['nit'] ?? ''),
        'tipo_cuenta'   => trim($_POST['tipo_cuenta'] ?? 'Personal'),
    ];

    $result = $registerService->register($data);

    if ($result['success']) {
        $_SESSION['register_success'] = $result['message'];
    } else {
        $_SESSION['register_error'] = $result['message'];
        $_SESSION['register_old']   = $data; // para rellenar formulario
    }

    header('Location: index.php?r=registro');
    exit;
}
}
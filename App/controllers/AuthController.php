<?php
// app/Controllers/AuthController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    public function showLogin(): void
    {
        if (AuthService::check()) {
            $this->redirectSegunRol();
        }
        require_once APP_PATH . '/Views/auth/login.php';
    }

    public function login(): void
    {
        $login    = trim($_POST['login']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($login === '' || $password === '') {
            $error = 'Por favor ingresa tu usuario y contraseña.';
            require_once APP_PATH . '/Views/auth/login.php';
            return;
        }

        $result = AuthService::attempt($login, $password);

        if (!$result['success']) {
            $error = $result['message'];
            require_once APP_PATH . '/Views/auth/login.php';
            return;
        }

        $this->redirectSegunRol();
    }

    public function showRegister(): void
    {
        if (AuthService::check()) {
            $this->redirectSegunRol();
        }
        require_once APP_PATH . '/Views/auth/register.php';
    }

    public function register(): void
    {
        $nombre1        = trim($_POST['nombre1']        ?? '');
        $apellido1      = trim($_POST['apellido1']      ?? '');
        $correo         = trim($_POST['correo']         ?? '');
        $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
        $password       = trim($_POST['password']       ?? '');

        if (!$nombre1 || !$apellido1 || !$correo || !$nombre_usuario || !$password) {
            $error = 'Todos los campos son obligatorios.';
            require_once APP_PATH . '/Views/auth/register.php';
            return;
        }

        try {
            $repo = new \App\Repositories\UsuarioRepository();
            $repo->create([
                'nombre1'        => $nombre1,
                'nombre2'        => trim($_POST['nombre2']   ?? ''),
                'apellido1'      => $apellido1,
                'apellido2'      => trim($_POST['apellido2'] ?? ''),
                'ci'             => trim($_POST['ci']        ?? ''),
                'correo'         => $correo,
                'telefono'       => trim($_POST['telefono']  ?? ''),
                'nombre_usuario' => $nombre_usuario,
                'password'       => $password,
            ]);

            $result = AuthService::attempt($nombre_usuario, $password);
            if ($result['success']) {
                $this->redirectSegunRol();
            }

        } catch (\Exception $e) {
            $error = 'No se pudo crear la cuenta: ' . $e->getMessage();
            require_once APP_PATH . '/Views/auth/register.php';
        }
    }

    public function logout(): void
    {
        AuthService::logout();
        header('Location: ' . BASE_URL . '?r=login');
        exit;
    }

    private function redirectSegunRol(): void
    {
        $user = AuthService::user();

        if (!$user) {
            header('Location: ' . BASE_URL . '?r=login');
            exit;
        }

        if ($user->esAdministrador()) {
            header('Location: ' . BASE_URL . '?r=admin/dashboard');
        } elseif ($user->esGuia()) {
            header('Location: ' . BASE_URL . '?r=guias/dashboard');
        } else {
            header('Location: ' . BASE_URL . '?r=/');
        }
        exit;
    }
}
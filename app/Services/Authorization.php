<?php
// core/Authorization.php
declare(strict_types=1);

namespace Core;

use App\Services\AuthService;
use App\Models\Usuario;

class Authorization
{
    /**
     * Requiere que el usuario esté autenticado.
     * Si no, redirige al login.
     */
    public static function requireAuth(): Usuario
    {
        if (!AuthService::check()) {
            header('Location: ' . BASE_URL . '?r=login');
            exit;
        }
        return AuthService::user();
    }

    /**
     * Requiere que el usuario sea administrador.
     */
    public static function requireAdmin(): Usuario
    {
        $user = self::requireAuth();

        if (!$user->esAdministrador()) {
            http_response_code(403);
            require_once APP_PATH . '/Views/errors/403.php';
            exit;
        }

        return $user;
    }

    /**
     * Requiere que el usuario sea guía (o admin).
     */
    public static function requireGuia(): Usuario
    {
        $user = self::requireAuth();

        if (!$user->esGuia() && !$user->esAdministrador()) {
            http_response_code(403);
            require_once APP_PATH . '/Views/errors/403.php';
            exit;
        }

        return $user;
    }

    /**
     * Requiere que el usuario sea cliente (o admin).
     */
    public static function requireCliente(): Usuario
    {
        $user = self::requireAuth();

        if (!$user->esCliente() && !$user->esAdministrador()) {
            http_response_code(403);
            require_once APP_PATH . '/Views/errors/403.php';
            exit;
        }

        return $user;
    }
}
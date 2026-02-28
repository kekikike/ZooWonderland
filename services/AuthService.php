<?php
// app/Services/AuthService.php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\UsuarioRepository;

class AuthService {
    private UsuarioRepository $repo;

    public function __construct() {
        $this->repo = new UsuarioRepository();
    }

    public function attempt(string $login, string $password): array {
        $user = $this->repo->findByCredentials($login, $password);

        if (!$user) {
            return ['success' => false, 'message' => 'Credenciales inválidas'];
        }

        $_SESSION['user_id'] = $user->id;
        return ['success' => true, 'message' => 'Login exitoso'];
    }

    public function check(): bool {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
    }

    public function user(): ?Usuario {
        if (!$this->check()) {
            return null;
        }

        static $cachedUser = null;
        if ($cachedUser === null) {
            $repo = new UsuarioRepository();
            $cachedUser = $repo->findById((int) $_SESSION['user_id']);
        }
        return $cachedUser;
    }

    public function logout(): void {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }
}
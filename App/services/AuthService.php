<?php
// app/Services/AuthService.php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\UsuarioRepository;
use App\Models\Usuario;

class AuthService
{
    private UsuarioRepository $repo;

    public function __construct()
    {
        $this->repo = new UsuarioRepository();
    }

    /**
     * Intenta autenticar y guarda solo el ID en sesión
     * @return array ['success' => bool, 'message' => string]
     */
    public function attempt(string $login, string $password): array
    {
        $usuario = $this->repo->authenticate($login, $password);

        if (!$usuario) {
            return [
                'success' => false,
                'message' => 'Credenciales incorrectas o usuario no encontrado.'
            ];
        }

        // Opcional: verificar que sea cliente si el sistema lo requiere estrictamente
        if (!$this->repo->esCliente($usuario->id_usuario)) {
            return [
                'success' => false,
                'message' => 'Solo los clientes pueden iniciar sesión en esta interfaz.'
            ];
        }

        $_SESSION['user_id'] = $usuario->id_usuario;
        session_regenerate_id(true);

        return [
            'success' => true,
            'message' => 'Inicio de sesión exitoso.'
        ];
    }

    public function check(): bool
    {
        return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
    }

    public function user(): ?Usuario
    {
        if (!$this->check()) {
            return null;
        }

        // Cache estático simple para evitar múltiples consultas en la misma request
        static $cached = null;
        if ($cached === null) {
            $cached = $this->repo->findById((int) $_SESSION['user_id']);
        }

        return $cached;
    }

    public function logout(): void
    {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }
}
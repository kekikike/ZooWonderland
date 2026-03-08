<?php
// app/Services/AuthService.php
declare(strict_types=1);

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\UsuarioRepository;
use App\Repositories\ApiTokenRepository;
use Illuminate\Support\Facades\Cookie;

class AuthService
{
    private const COOKIE_NAME = 'zoo_token';
    private const COOKIE_DAYS = 7;

    private UsuarioRepository $usuarioRepo;
    private ApiTokenRepository $tokenRepo;

    public function __construct()
    {
        $this->usuarioRepo = new UsuarioRepository();
        $this->tokenRepo   = new ApiTokenRepository();
    }

    public function attempt(string $login, string $password): array
    {
        $usuario = $this->usuarioRepo->authenticate($login, $password);

        if (!$usuario) {
            return ['success' => false, 'message' => 'Credenciales incorrectas.'];
        }
        if (!$usuario->estaActivo()) {
            return ['success' => false, 'message' => 'Tu cuenta está desactivada.'];
        }

        $this->tokenRepo->revocarTodos($usuario->id_usuario);

        $token    = bin2hex(random_bytes(32));
        $expireAt = now()->addDays(self::COOKIE_DAYS)->format('Y-m-d H:i:s');
        $ip       = request()->ip();

        $this->tokenRepo->crear($usuario->id_usuario, $token, $expireAt, $ip);

        Cookie::queue(self::COOKIE_NAME, $token, self::COOKIE_DAYS * 60 * 24, '/', null, false, true);

        return ['success' => true, 'message' => 'Inicio de sesión exitoso.'];
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function user(): ?Usuario
    {
        static $cached = null;
        if ($cached === null) {
            $cached = $this->getUsuarioDesdeToken();
        }
        return $cached;
    }

    public function logout(): void
    {
        $token = request()->cookie(self::COOKIE_NAME);
        if ($token) {
            $this->tokenRepo->revocar($token);
        }
        Cookie::queue(Cookie::forget(self::COOKIE_NAME));
    }

    private function getUsuarioDesdeToken(): ?Usuario
    {
        $token = request()->cookie(self::COOKIE_NAME);
        if (!$token) return null;

        $apiToken = $this->tokenRepo->findActivo($token);
        if (!$apiToken) return null;

        $this->tokenRepo->marcarUso($token);

        return $this->usuarioRepo->findById($apiToken->id_usuario);
    }
}
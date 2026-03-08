<?php
// app/Http/Middleware/AuthMiddleware.php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Repositories\ApiTokenRepository;
use App\Repositories\UsuarioRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    private ApiTokenRepository $tokenRepo;
    private UsuarioRepository $usuarioRepo;

    public function __construct()
    {
        $this->tokenRepo   = new ApiTokenRepository();
        $this->usuarioRepo = new UsuarioRepository();
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->extractToken($request);

        if (!$token) {
            return $this->unauthorized('Token no proporcionado.');
        }

        $apiToken = $this->tokenRepo->findActivo($token);

        if (!$apiToken) {
            return $this->unauthorized('Token inválido o expirado.');
        }

        $usuario = $this->usuarioRepo->findById($apiToken->id_usuario);

        if (!$usuario || !$usuario->estaActivo()) {
            return $this->forbidden('Tu cuenta está desactivada.');
        }

        $this->tokenRepo->marcarUso($token);

        // Compartir usuario con el request para que los controllers lo usen
        $request->merge(['auth_user' => $usuario]);
        $request->attributes->set('auth_user', $usuario);

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        $header = $request->header('Authorization', '');

        if (str_starts_with($header, 'Bearer ')) {
            return trim(substr($header, 7));
        }

        // Fallback para cookies (vistas web)
        return $request->cookie('zoo_token');
    }

    private function unauthorized(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message], 401);
    }

    private function forbidden(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message], 403);
    }
}
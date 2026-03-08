<?php
// app/Http/Middleware/ClienteMiddleware.php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClienteMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->attributes->get('auth_user');

        if (!$usuario || (!$usuario->esCliente() && !$usuario->esAdministrador())) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Se requiere rol de cliente.'], 403);
            }
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}
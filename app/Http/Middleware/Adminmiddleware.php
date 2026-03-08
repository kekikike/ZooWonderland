<?php
// app/Http/Middleware/AdminMiddleware.php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->attributes->get('auth_user');

        if (!$usuario || !$usuario->esAdministrador()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Se requiere rol de administrador.'], 403);
            }
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}
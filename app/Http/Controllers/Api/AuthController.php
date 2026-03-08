<?php
// app/Http/Controllers/Api/AuthController.php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ApiTokenRepository;
use App\Repositories\UsuarioRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private UsuarioRepository $repo;
    private ApiTokenRepository $tokenRepo;

    public function __construct()
    {
        $this->repo      = new UsuarioRepository();
        $this->tokenRepo = new ApiTokenRepository();
    }

    // POST /api/auth/login
    public function login(Request $request)
    {
        $login    = trim($request->json('login',    ''));
        $password = trim($request->json('password', ''));

        if ($login === '' || $password === '') {
            return response()->json(['success' => false, 'message' => 'login y password son obligatorios.'], 400);
        }

        $usuario = $this->repo->authenticate($login, $password);

        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Credenciales incorrectas.'], 401);
        }

        if (!$usuario->estaActivo()) {
            return response()->json(['success' => false, 'message' => 'Tu cuenta está desactivada.'], 403);
        }

        $this->tokenRepo->revocarTodos($usuario->id_usuario);

        $token    = bin2hex(random_bytes(32));
        $expireAt = now()->addDay()->format('Y-m-d H:i:s');

        $this->tokenRepo->crear($usuario->id_usuario, $token, $expireAt, $request->ip());

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'data'    => [
                'token'      => $token,
                'expires_at' => $expireAt,
                'usuario'    => [
                    'id_usuario'     => $usuario->id_usuario,
                    'nombre'         => $usuario->getNombreCompleto(),
                    'correo'         => $usuario->correo,
                    'nombre_usuario' => $usuario->nombre_usuario,
                    'rol'            => $usuario->rol->nombre_rol ?? null,
                    'id_rol'         => $usuario->id_rol,
                ],
            ],
        ]);
    }

    // POST /api/auth/logout
    public function logout(Request $request)
    {
        $authUser = $request->attributes->get('auth_user');
        $this->tokenRepo->revocarTodos($authUser->id_usuario);

        return response()->json(['success' => true, 'message' => 'Sesión cerrada correctamente.']);
    }

    // GET /api/auth/me
    public function me(Request $request)
    {
        $authUser = $request->attributes->get('auth_user');
        $usuario  = $this->repo->getUsuarioPorId($authUser->id_usuario);

        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
        }

        return response()->json(['success' => true, 'data' => $usuario]);
    }
}
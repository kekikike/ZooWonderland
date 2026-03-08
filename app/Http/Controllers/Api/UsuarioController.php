<?php
// app/Http/Controllers/Api/UsuarioController.php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UsuarioRepository;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    private UsuarioRepository $repo;

    public function __construct()
    {
        $this->repo = new UsuarioRepository();
    }

    // GET /api/usuarios
    public function index(Request $request)
    {
        $authUser = $request->attributes->get('auth_user');

        $usuarios = $this->repo->getUsuariosFiltrados(
            trim($request->input('busqueda', '')),
            trim($request->input('rol',      '')),
            (int)$request->input('recorrido', 0),
            trim($request->input('estado',   '')),
            (int)$authUser->id_usuario
        );

        return response()->json(['success' => true, 'data' => $usuarios]);
    }

    // GET /api/usuarios/{id}
    public function show(Request $request, int $id)
    {
        $usuario = $this->repo->getUsuarioPorId($id);

        if (!$usuario) {
            return response()->json(['success' => false, 'message' => "Usuario #{$id} no encontrado."], 404);
        }

        return response()->json(['success' => true, 'data' => $usuario]);
    }

    // POST /api/usuarios
    public function store(Request $request)
    {
        $body   = $request->json()->all();
        $errors = $this->validarDatos($body);

        if (!empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors], 422);
        }

        $usuario = $this->repo->create($body);
        return response()->json(['success' => true, 'data' => $usuario, 'message' => 'Usuario creado.'], 201);
    }

    // PUT /api/usuarios/{id}
    public function update(Request $request, int $id)
    {
        $body   = $request->json()->all();
        $errors = $this->validarDatos($body);

        if (!empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors], 422);
        }

        try {
            $this->repo->actualizarUsuario(
                $id,
                trim($body['nombre1']        ?? ''),
                trim($body['nombre2']        ?? ''),
                trim($body['apellido1']      ?? ''),
                trim($body['apellido2']      ?? ''),
                (int)($body['ci']            ?? 0),
                trim($body['telefono']       ?? ''),
                trim($body['rol']            ?? ''),
                trim($body['correo']         ?? ''),
                trim($body['nombre_usuario'] ?? '')
            );

            return response()->json([
                'success' => true,
                'data'    => $this->repo->getUsuarioPorId($id),
                'message' => 'Usuario actualizado.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
        }
    }

    // PATCH /api/usuarios/{id}/estado
    public function toggleEstado(Request $request, int $id)
    {
        $authUser = $request->attributes->get('auth_user');
        $estado   = (int)$request->json('estado', -1);

        if ($id === (int)$authUser->id_usuario) {
            return response()->json(['success' => false, 'message' => 'No puedes cambiar tu propio estado.'], 403);
        }

        if (!in_array($estado, [0, 1], true)) {
            return response()->json(['success' => false, 'message' => 'El campo estado debe ser 0 o 1.'], 400);
        }

        $ok = $this->repo->cambiarEstado($id, $estado);
        if (!$ok) {
            return response()->json(['success' => false, 'message' => 'No se pudo cambiar el estado.'], 500);
        }

        return response()->json([
            'success' => true,
            'data'    => ['id_usuario' => $id, 'estado' => $estado],
            'message' => $estado === 1 ? 'Cuenta activada.' : 'Cuenta desactivada.',
        ]);
    }

    // DELETE /api/usuarios/{id}
    public function destroy(Request $request, int $id)
    {
        $authUser = $request->attributes->get('auth_user');

        if ($id === (int)$authUser->id_usuario) {
            return response()->json(['success' => false, 'message' => 'No puedes eliminarte a ti mismo.'], 403);
        }

        $ok = $this->repo->cambiarEstado($id, 0);
        if (!$ok) {
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el usuario.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Usuario desactivado.']);
    }

    private function validarDatos(array $body): array
    {
        $errors = [];
        if (empty($body['nombre1']))   $errors[] = 'nombre1 es obligatorio.';
        if (empty($body['apellido1'])) $errors[] = 'apellido1 es obligatorio.';
        if (empty($body['correo']) || !filter_var($body['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'correo inválido.';
        }
        if (empty($body['nombre_usuario'])) $errors[] = 'nombre_usuario es obligatorio.';
        if (!in_array($body['rol'] ?? '', ['cliente', 'guia', 'administrador'], true)) {
            $errors[] = 'rol inválido.';
        }
        return $errors;
    }
}
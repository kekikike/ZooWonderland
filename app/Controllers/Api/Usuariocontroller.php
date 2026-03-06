<?php
// app/Controllers/Api/UsuarioController.php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Repositories\UsuarioRepository;

class UsuarioController
{
    private UsuarioRepository $repo;

    public function __construct()
    {
        $this->repo = new UsuarioRepository();
    }

    // ── GET /api/usuarios ────────────────────────────────────────
    public function index(): void
    {
        $authUser = (new AuthMiddleware())->handle();
        (new AdminMiddleware())->handle($authUser);

        $busqueda    = trim($_GET['busqueda']     ?? '');
        $rol         = trim($_GET['rol']          ?? '');
        $idRecorrido = (int)($_GET['id_recorrido'] ?? 0);
        $estado      = $_GET['estado']             ?? '';

        $usuarios = $this->repo->getUsuariosFiltrados(
            $busqueda,
            $rol,
            $idRecorrido,
            $estado,
            $authUser['id_usuario']
        );

        Response::ok($usuarios);
    }

    // ── GET /api/usuarios/{id} ───────────────────────────────────
    public function show(int $id): void
    {
        $authUser = (new AuthMiddleware())->handle();
        (new AdminMiddleware())->handle($authUser);

        $usuario = $this->repo->getUsuarioPorId($id);

        if (!$usuario) {
            Response::notFound('Usuario no encontrado.');
        }

        Response::ok($usuario);
    }

    // ── PUT /api/usuarios/{id} ───────────────────────────────────
    public function update(int $id): void
    {
        $authUser = (new AuthMiddleware())->handle();
        (new AdminMiddleware())->handle($authUser);

        $body = $this->getJson();

        $nombre1        = trim($body['nombre1']        ?? '');
        $apellido1      = trim($body['apellido1']      ?? '');
        $correo         = trim($body['correo']         ?? '');
        $nombre_usuario = trim($body['nombre_usuario'] ?? '');
        $rol            = trim($body['rol']            ?? '');

        if (!$nombre1 || !$apellido1 || !$correo || !$nombre_usuario || !$rol) {
            Response::badRequest('Los campos nombre1, apellido1, correo, nombre_usuario y rol son obligatorios.');
        }

        try {
            $this->repo->actualizarUsuario(
                $id,
                $nombre1,
                trim($body['nombre2']   ?? ''),
                $apellido1,
                trim($body['apellido2'] ?? ''),
                (int)($body['ci']       ?? 0),
                trim($body['telefono']  ?? ''),
                $rol,
                $correo,
                $nombre_usuario
            );

            $usuario = $this->repo->getUsuarioPorId($id);
            Response::ok($usuario, 'Usuario actualizado correctamente.');

        } catch (\Exception $e) {
            Response::conflict($e->getMessage());
        }
    }

    // ── PATCH /api/usuarios/{id}/estado ─────────────────────────
    public function toggleEstado(int $id): void
    {
        $authUser = (new AuthMiddleware())->handle();
        (new AdminMiddleware())->handle($authUser);

        if ($id === $authUser['id_usuario']) {
            Response::forbidden('No puedes cambiar tu propio estado.');
        }

        $body   = $this->getJson();
        $estado = isset($body['estado']) ? (int)$body['estado'] : -1;

        if ($estado !== 0 && $estado !== 1) {
            Response::badRequest('El campo estado debe ser 0 o 1.');
        }

        $this->repo->cambiarEstado($id, $estado);

        Response::ok(['id_usuario' => $id, 'estado' => $estado], 'Estado actualizado.');
    }

    // ── Helper ───────────────────────────────────────────────────
    private function getJson(): array
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? [];
    }
}
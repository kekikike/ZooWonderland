<?php
// app/controllers/Api/UsuarioController.php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Repositories\UsuarioRepository;

class UsuarioController
{
    private UsuarioRepository $repo;
    private AuthMiddleware    $auth;
    private AdminMiddleware   $admin;

    public function __construct()
    {
        $this->repo  = new UsuarioRepository();
        $this->auth  = new AuthMiddleware();
        $this->admin = new AdminMiddleware();
    }

    // ── GET /api/usuarios ────────────────────────────────────────
    /**
     * Lista de usuarios con filtros opcionales.
     * Query params: busqueda, rol, recorrido, estado
     * Solo accesible por administradores.
     */
    public function index(array $params = []): void
    {
        $authUser = $this->auth->handle();
        $this->admin->handle($authUser);

        $busqueda  = trim($_GET['busqueda']   ?? '');
        $rol       = trim($_GET['rol']        ?? '');
        $recorrido = (int)($_GET['recorrido'] ?? 0);
        $estado    = trim($_GET['estado']     ?? '');

        $usuarios = $this->repo->getUsuariosFiltrados(
            $busqueda, $rol, $recorrido, $estado,
            $authUser['id_usuario']
        );

        Response::ok($usuarios);
    }

    // ── GET /api/usuarios/{id} ───────────────────────────────────
    public function show(array $params): void
    {
        $authUser = $this->auth->handle();
        $this->admin->handle($authUser);

        $id = (int)($params['id'] ?? 0);
        $usuario = $this->repo->getUsuarioPorId($id);

        if (!$usuario) {
            Response::notFound("Usuario #{$id} no encontrado.");
        }

        Response::ok($usuario);
    }

    // ── PUT /api/usuarios/{id} ───────────────────────────────────
    /**
     * Actualiza nombre, apellido, CI, teléfono, rol, correo, nombre_usuario.
     * Body JSON con los campos a actualizar.
     */
    public function update(array $params): void
    {
        $authUser = $this->auth->handle();
        $this->admin->handle($authUser);

        $id   = (int)($params['id'] ?? 0);
        $body = $this->getJson();

        // Validaciones
        $errors = [];
        $nombre1        = trim($body['nombre1']        ?? '');
        $nombre2        = trim($body['nombre2']        ?? '');
        $apellido1      = trim($body['apellido1']      ?? '');
        $apellido2      = trim($body['apellido2']      ?? '');
        $ci             = (int)($body['ci']            ?? 0);
        $telefono       = trim($body['telefono']       ?? '');
        $rol            = trim($body['rol']            ?? '');
        $correo         = trim($body['correo']         ?? '');
        $nombre_usuario = trim($body['nombre_usuario'] ?? '');

        if ($nombre1 === '')   $errors[] = 'nombre1 es obligatorio.';
        if ($apellido1 === '') $errors[] = 'apellido1 es obligatorio.';
        if ($ci <= 0)          $errors[] = 'ci debe ser un número positivo.';
        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'correo inválido.';
        }
        if ($nombre_usuario === '') $errors[] = 'nombre_usuario es obligatorio.';
        if (!in_array($rol, ['cliente', 'guia', 'administrador'], true)) {
            $errors[] = 'rol inválido.';
        }

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        try {
            $ok = $this->repo->actualizarUsuario(
                $id, $nombre1, $nombre2, $apellido1, $apellido2,
                $ci, $telefono, $rol, $correo, $nombre_usuario
            );

            if (!$ok) {
                Response::serverError('No se pudo actualizar el usuario.');
            }

            Response::ok($this->repo->getUsuarioPorId($id), 'Usuario actualizado.');

        } catch (\Exception $e) {
            Response::conflict($e->getMessage());
        }
    }

    // ── PATCH /api/usuarios/{id}/estado ─────────────────────────
    /**
     * Activa o desactiva la cuenta.
     * Body JSON: { "estado": 0 } o { "estado": 1 }
     */
    public function toggleEstado(array $params): void
    {
        $authUser = $this->auth->handle();
        $this->admin->handle($authUser);

        $id     = (int)($params['id'] ?? 0);
        $body   = $this->getJson();
        $estado = isset($body['estado']) ? (int)$body['estado'] : -1;

        if ($id === $authUser['id_usuario']) {
            Response::forbidden('No puedes cambiar tu propio estado.');
        }

        if (!in_array($estado, [0, 1], true)) {
            Response::badRequest('El campo estado debe ser 0 o 1.');
        }

        $ok = $this->repo->cambiarEstado($id, $estado);

        if (!$ok) {
            Response::serverError('No se pudo cambiar el estado.');
        }

        Response::ok(
            ['id_usuario' => $id, 'estado' => $estado],
            $estado === 1 ? 'Cuenta activada.' : 'Cuenta desactivada.'
        );
    }

    // ── Helper ───────────────────────────────────────────────────
    private function getJson(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}
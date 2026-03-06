<?php
// app/Controllers/Api/AuthController.php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use App\Repositories\UsuarioRepository;
use Core\Database;

class AuthController
{
    private \PDO $db;
    private UsuarioRepository $repo;

    public function __construct()
    {
        $this->db   = Database::getInstance()->getConnection();
        $this->repo = new UsuarioRepository();
    }

    // ── POST /api/auth/login ─────────────────────────────────────
    public function login(): void
    {
        $body     = $this->getJson();
        $login    = trim($body['login']    ?? '');
        $password = trim($body['password'] ?? '');

        if ($login === '' || $password === '') {
            Response::badRequest('Los campos login y password son obligatorios.');
        }

        $usuario = $this->repo->authenticate($login, $password);

        if (!$usuario) {
            Response::unauthorized('Credenciales incorrectas.');
        }

        if (!$usuario->estaActivo()) {
            Response::forbidden('Tu cuenta está desactivada.');
        }

        // Revocar tokens anteriores
        $this->db->prepare("UPDATE api_token SET activo = 0 WHERE id_usuario = :id")
                 ->execute([':id' => $usuario->id_usuario]);

        // Generar nuevo token
        $token    = bin2hex(random_bytes(32));
        $expireAt = (new \DateTime())->modify('+8 hours')->format('Y-m-d H:i:s');

        $this->db->prepare("
            INSERT INTO api_token (id_usuario, token, expire_at, ip_origen, activo)
            VALUES (:id, :token, :expire, :ip, 1)
        ")->execute([
            ':id'     => $usuario->id_usuario,
            ':token'  => $token,
            ':expire' => $expireAt,
            ':ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        Response::ok([
            'token'      => $token,
            'expires_at' => $expireAt,
            'usuario'    => [
                'id_usuario'     => $usuario->id_usuario,
                'nombre'         => $usuario->getNombreCompleto(),
                'correo'         => $usuario->correo,
                'nombre_usuario' => $usuario->nombre_usuario,
                'rol'            => $usuario->rol,
                'id_rol'         => $usuario->id_rol,
            ],
        ], 'Inicio de sesión exitoso.');
    }

    // ── POST /api/auth/logout ────────────────────────────────────
    public function logout(): void
    {
        $authUser = (new AuthMiddleware())->handle();

        $this->db->prepare("
            UPDATE api_token SET activo = 0
            WHERE id_usuario = :id AND activo = 1
        ")->execute([':id' => $authUser['id_usuario']]);

        Response::ok(null, 'Sesión cerrada correctamente.');
    }

    // ── GET /api/auth/me ─────────────────────────────────────────
    public function me(): void
    {
        $authUser = (new AuthMiddleware())->handle();
        $usuario  = $this->repo->getUsuarioPorId($authUser['id_usuario']);

        if (!$usuario) {
            Response::notFound('Usuario no encontrado.');
        }

        Response::ok($usuario);
    }

    // ── Helper ───────────────────────────────────────────────────
    private function getJson(): array
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? [];
    }
}
<?php
// app/middleware/AuthMiddleware.php
declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\Response;
use Core\Database;

class AuthMiddleware
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Extrae y valida el Bearer token del header Authorization.
     * Si es válido, actualiza last_used y devuelve los datos del usuario.
     * Si no, responde 401 y termina.
     *
     * @return array  ['id_usuario', 'nombre_rol', 'id_rol', 'nombre1', 'apellido1', 'estado']
     */
    public function handle(): array
{
    
        $token = $this->extractToken();

        if (!$token) {
            Response::unauthorized('Token no proporcionado. Incluye Authorization: Bearer <token>');
        }

        $stmt = $this->db->prepare("
            SELECT
                at.id_usuario,
                at.expire_at,
                at.activo,
                u.nombre1,
                u.apellido1,
                u.estado       AS usuario_estado,
                r.nombre_rol,
                r.id_rol
            FROM api_token at
            INNER JOIN usuarios u ON u.id_usuario = at.id_usuario
            INNER JOIN roles    r ON r.id_rol     = u.id_rol
            WHERE at.token = :token
            LIMIT 1
        ");
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            Response::unauthorized('Token inválido.');
        }

        if (!(bool)$row['activo']) {
            Response::unauthorized('Token revocado.');
        }

        if (new \DateTime() > new \DateTime($row['expire_at'])) {
            // Marcar como inactivo
            $this->db->prepare("UPDATE api_token SET activo = 0 WHERE token = :token")
                     ->execute([':token' => $token]);
            Response::unauthorized('Token expirado. Vuelve a iniciar sesión.');
        }

        if (!(bool)$row['usuario_estado']) {
            Response::forbidden('Tu cuenta está desactivada.');
        }

        // Actualizar last_used e ip_origen
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $this->db->prepare("
            UPDATE api_token SET last_used = NOW(), ip_origen = :ip WHERE token = :token
        ")->execute([':ip' => $ip, ':token' => $token]);

        return [
            'id_usuario'  => (int)$row['id_usuario'],
            'nombre1'     => $row['nombre1'],
            'apellido1'   => $row['apellido1'],
            'id_rol'      => (int)$row['id_rol'],
            'nombre_rol'  => $row['nombre_rol'],
        ];
    }

    /**
     * Extrae el token del header Authorization: Bearer <token>
     */
private function extractToken(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    if (!$header) {
        $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    }

    if (!$header && function_exists('getallheaders')) {
        $headers = getallheaders();
        $header  = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }

    if (str_starts_with($header, 'Bearer ')) {
        return trim(substr($header, 7));
    }

    return null;
}
}
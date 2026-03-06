<?php
// app/Services/AuthService.php
declare(strict_types=1);

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\UsuarioRepository;
use Core\Database;

class AuthService
{
    private const COOKIE_NAME = 'zoo_token';
    private const COOKIE_DAYS = 1; // 1 día = 8 horas aprox, ajustar si quieres más

    // ── Intento de login ─────────────────────────────────────────
    public static function attempt(string $login, string $password): array
    {
        $repo    = new UsuarioRepository();
        $usuario = $repo->authenticate($login, $password);

        if (!$usuario) {
            return ['success' => false, 'message' => 'Credenciales incorrectas.'];
        }

        if (!$usuario->estaActivo()) {
            return ['success' => false, 'message' => 'Tu cuenta está desactivada.'];
        }

        // Generar token y guardarlo en BD
        $db       = Database::getInstance()->getConnection();
        $token    = bin2hex(random_bytes(32)); // 64 chars
        $expireAt = (new \DateTime())->modify('+8 hours')->format('Y-m-d H:i:s');
        $ip       = $_SERVER['REMOTE_ADDR'] ?? null;

        // Revocar tokens anteriores
        $db->prepare("UPDATE api_token SET activo = 0 WHERE id_usuario = :id")
           ->execute([':id' => $usuario->id_usuario]);

        // Insertar nuevo token
        $db->prepare("
            INSERT INTO api_token (id_usuario, token, expire_at, ip_origen, activo)
            VALUES (:id, :token, :expire, :ip, 1)
        ")->execute([
            ':id'     => $usuario->id_usuario,
            ':token'  => $token,
            ':expire' => $expireAt,
            ':ip'     => $ip,
        ]);

        // Guardar token en cookie HttpOnly (no accesible por JS)
        $expireTimestamp = time() + (self::COOKIE_DAYS * 24 * 60 * 60);
        setcookie(self::COOKIE_NAME, $token, [
            'expires'  => $expireTimestamp,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        return ['success' => true, 'message' => 'Inicio de sesión exitoso.'];
    }

    // ── Verifica si hay sesión activa ────────────────────────────
    public static function check(): bool
    {
        return self::getUsuarioDesdeToken() !== null;
    }

    // ── Devuelve el usuario autenticado ──────────────────────────
    public static function user(): ?Usuario
    {
        static $cached = null;
        if ($cached === null) {
            $cached = self::getUsuarioDesdeToken();
        }
        return $cached;
    }

    // ── Cierra sesión ────────────────────────────────────────────
    public static function logout(): void
    {
        $token = $_COOKIE[self::COOKIE_NAME] ?? null;

        if ($token) {
            $db = Database::getInstance()->getConnection();
            $db->prepare("UPDATE api_token SET activo = 0 WHERE token = :token")
               ->execute([':token' => $token]);
        }

        // Borrar cookie
        setcookie(self::COOKIE_NAME, '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    // ── Helper: valida token de cookie contra BD ─────────────────
    private static function getUsuarioDesdeToken(): ?Usuario
    {
        $token = $_COOKIE[self::COOKIE_NAME] ?? null;
        if (!$token) return null;

        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT
                u.id_usuario, u.nombre1, u.nombre2, u.apellido1, u.apellido2,
                u.ci, u.correo, u.telefono, u.nombre_usuario, u.estado,
                r.nombre_rol AS rol,
                r.id_rol
            FROM api_token t
            INNER JOIN usuarios u ON u.id_usuario = t.id_usuario
            INNER JOIN roles    r ON r.id_rol      = u.id_rol
            WHERE t.token   = :token
              AND t.activo  = 1
              AND t.expire_at > NOW()
              AND u.estado  = 1
            LIMIT 1
        ");
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        // Actualizar last_used
        $db->prepare("UPDATE api_token SET last_used = NOW() WHERE token = :token")
           ->execute([':token' => $token]);

        return new Usuario($row);
    }
}
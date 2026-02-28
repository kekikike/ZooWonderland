<?php
// app/Repositories/UsuarioRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Usuario;
use Core\Database;

class UsuarioRepository {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByCredentials(string $usernameOrEmail, string $password): ?Usuario {
        $stmt = $this->db->prepare("
            SELECT id, nombre_usuario, email, nombre_completo, rol, password_hash
            FROM usuarios
            WHERE nombre_usuario = :login OR email = :login
            LIMIT 1
        ");
        $stmt->execute(['login' => $usernameOrEmail]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        if (!password_verify($password, $row['password_hash'])) {
            return null;
        }

        unset($row['password_hash']); // nunca exponer hash
        return new Usuario($row);
    }

    public function findById(int $id): ?Usuario {
        $stmt = $this->db->prepare("SELECT id, nombre_usuario, email, nombre_completo, rol FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? new Usuario($row) : null;
    }
}
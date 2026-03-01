<?php
// app/Repositories/UsuarioRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Usuario;
use Core\Database;

class UsuarioRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Busca usuario por nombre_usuario o correo + verifica contraseña
     * Retorna Usuario completo si credenciales correctas
     */
    public function authenticate(string $login, string $password): ?Usuario
    {
       $stmt = $this->db->prepare("
    SELECT 
        u.id_usuario, u.nombre1, u.nombre2, u.apellido1, u.apellido2,
        u.ci, u.correo, u.telefono, u.nombre_usuario, u.rol,
        u.contrasena AS password
    FROM usuarios u
    WHERE u.nombre_usuario = :usuario OR u.correo = :correo
    LIMIT 1
");

$stmt->execute([
    ':usuario' => $login,
    ':correo'  => $login
]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        if (!password_verify($password, $row['password'])) {
            return null;
        }

        // No exponemos el password
        unset($row['password']);

        return new Usuario($row);
    }

    /**
     * Busca usuario por ID (usado después de login para cargar datos frescos)
     */
    public function findById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("
            SELECT 
                id_usuario, nombre1, nombre2, apellido1, apellido2,
                ci, correo, telefono, nombre_usuario, rol
            FROM usuarios
            WHERE id_usuario = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? new Usuario($row) : null;
    }

    /**
     * Verifica si el usuario tiene registro en la tabla Cliente
     * (para confirmar que es un cliente real)
     */
    public function esCliente(int $id_usuario): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM clientes WHERE id_usuario = :id LIMIT 1");
        $stmt->execute([':id' => $id_usuario]);
        return (bool) $stmt->fetchColumn();
    }

    /**
 * Crea un nuevo usuario y retorna su ID
 * @throws Exception si falla la inserción
 */
public function create(array $data): int
{
    $stmt = $this->db->prepare("
        INSERT INTO usuarios (
            nombre1, nombre2, apellido1, apellido2,
            ci, correo, telefono, nombre_usuario,
            contrasena, rol
        ) VALUES (
            :nombre1, :nombre2, :apellido1, :apellido2,
            :ci, :correo, :telefono, :nombre_usuario,
            :contrasena, :rol
        )
    ");

    $success = $stmt->execute([
        ':nombre1'        => $data['nombre1'],
        ':nombre2'        => $data['nombre2'] ?? null,
        ':apellido1'      => $data['apellido1'],
        ':apellido2'      => $data['apellido2'] ?? null,
        ':ci'             => $data['ci'] ?? null,
        ':correo'         => $data['correo'],
        ':telefono'       => $data['telefono'] ?? null,
        ':nombre_usuario' => $data['nombre_usuario'],
        ':contrasena'     => password_hash($data['password'], PASSWORD_DEFAULT),
        ':rol'            => 'cliente'
    ]);

   if (!$success) {
    throw new \Exception("No se pudo crear el usuario");
}

    return (int) $this->db->lastInsertId();
}
}

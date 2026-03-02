<?php
declare(strict_types=1);

namespace App\Repositories;

use Core\Database;

class CompraRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener todos los registros de compras
    public function findAll(): array
    {
        $sql = "SELECT * FROM compras ORDER BY fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener compra por su ID
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM compras WHERE id_compra = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // Obtener todas las compras de un cliente
    public function findByCliente(int $clienteId, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $sql = "SELECT * FROM compras WHERE id_cliente = :id";

        // Filtrado por fechas si se pasan
        $params = ['id' => $clienteId];
        if ($fechaInicio) {
            $sql .= " AND fecha >= :fechaInicio";
            $params['fechaInicio'] = $fechaInicio;
        }
        if ($fechaFin) {
            $sql .= " AND fecha <= :fechaFin";
            $params['fechaFin'] = $fechaFin;
        }

        $sql .= " ORDER BY fecha DESC, hora DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // Crear una nueva compra
    public function create(array $data): void
    {
        $sql = "
            INSERT INTO compras (id_cliente, fecha, hora, monto, estado_pago)
            VALUES (:id_cliente, :fecha, :hora, :monto, :estado_pago)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    // Obtener cliente por id_usuario
    public function findClienteByUsuario(int $usuarioId): ?array
    {
        $sql = "SELECT * FROM clientes WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $usuarioId]);
        $cliente = $stmt->fetch();
        return $cliente ?: null;
    }
}
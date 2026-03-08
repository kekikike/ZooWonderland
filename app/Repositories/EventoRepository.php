<?php
// app/Repositories/EventoRepository.php
declare(strict_types=1);

namespace App\Repositories;

use Core\Database;
use PDO;

class EventoRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

   /**
     * Obtiene todos los eventos con filtros opcionales
     */
    public function findAll(array $filtros = []): array
    {
        $sql = "
            SELECT 
                e.id_evento,
                e.nombre_evento,
                e.descripcion,
                e.fecha_inicio,
                e.fecha_fin,
                e.tiene_costo,
                e.precio,
                e.encargado_id,
                CONCAT(u.nombre1, ' ', u.apellido1) AS encargado_nombre,
                e.lugar,
                e.limite_participantes,
                e.estado,
                CASE 
                    WHEN e.fecha_inicio > NOW() THEN 'Pendiente'
                    WHEN e.fecha_fin < NOW() THEN 'Pasado'
                    ELSE 'Vigente'
                END AS vigencia
            FROM eventos e
            LEFT JOIN guias g ON g.id_guia = e.encargado_id
            LEFT JOIN usuarios u ON u.id_usuario = g.id_usuario
            WHERE e.estado = 1  -- ← SOLO EVENTOS ACTIVOS
        ";

        $params = [];

        // Filtro por vigencia
        if (!empty($filtros['vigencia'])) {
            if ($filtros['vigencia'] === 'vigente') {
                $sql .= " AND e.fecha_inicio <= NOW() AND e.fecha_fin >= NOW()";
            } elseif ($filtros['vigencia'] === 'pasado') {
                $sql .= " AND e.fecha_fin < NOW()";
            } elseif ($filtros['vigencia'] === 'pendiente') {
                $sql .= " AND e.fecha_inicio > NOW()";
            }
        }

        // Filtro por fecha
        if (!empty($filtros['fecha'])) {
            $sql .= " AND DATE(e.fecha_inicio) = :fecha";
            $params[':fecha'] = $filtros['fecha'];
        }

        // Filtro por nombre
        if (!empty($filtros['nombre'])) {
            $sql .= " AND e.nombre_evento LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }

        // Filtro por encargado
        if (!empty($filtros['encargado_id'])) {
            $sql .= " AND e.encargado_id = :encargado_id";
            $params[':encargado_id'] = $filtros['encargado_id'];
        }

        $sql .= " ORDER BY CASE WHEN vigencia = 'Vigente' THEN 1 ELSE 2 END, e.fecha_inicio DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ... resto de métodos como getById, create, update, delete, saveActividades, getActividades (sin cambios)


    /**
     * Obtiene un evento por ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM eventos
            WHERE id_evento = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Crea un nuevo evento y retorna su ID
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO eventos (
                nombre_evento, descripcion, fecha_inicio, fecha_fin,
                tiene_costo, precio, encargado_id, lugar,
                limite_participantes, estado
            ) VALUES (
                :nombre_evento, :descripcion, :fecha_inicio, :fecha_fin,
                :tiene_costo, :precio, :encargado_id, :lugar,
                :limite_participantes, :estado
            )
        ");

        $stmt->execute([
            ':nombre_evento' => $data['nombre_evento'],
            ':descripcion' => $data['descripcion'],
            ':fecha_inicio' => $data['fecha_inicio'],
            ':fecha_fin' => $data['fecha_fin'],
            ':tiene_costo' => $data['tiene_costo'],
            ':precio' => $data['precio'],
            ':encargado_id' => $data['encargado_id'],
            ':lugar' => $data['lugar'],
            ':limite_participantes' => $data['limite_participantes'],
            ':estado' => 1
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Actualiza un evento
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE eventos SET
                nombre_evento = :nombre_evento,
                descripcion = :descripcion,
                fecha_inicio = :fecha_inicio,
                fecha_fin = :fecha_fin,
                tiene_costo = :tiene_costo,
                precio = :precio,
                encargado_id = :encargado_id,
                lugar = :lugar,
                limite_participantes = :limite_participantes
            WHERE id_evento = :id
        ");

        return $stmt->execute([
            ':nombre_evento' => $data['nombre_evento'],
            ':descripcion' => $data['descripcion'],
            ':fecha_inicio' => $data['fecha_inicio'],
            ':fecha_fin' => $data['fecha_fin'],
            ':tiene_costo' => $data['tiene_costo'],
            ':precio' => $data['precio'],
            ':encargado_id' => $data['encargado_id'],
            ':lugar' => $data['lugar'],
            ':limite_participantes' => $data['limite_participantes'],
            ':id' => $id
        ]);
    }

    /**
     * Elimina un evento
     */
    public function delete(int $id_usuario, int $estado): bool
    {
        $stmt = $this->db->prepare("
        UPDATE eventos SET estado = :estado WHERE id_evento = :id");
        return $stmt->execute([':estado' => $estado, ':id' => $id_usuario]);
    }

    

    /**
     * Agrega o actualiza actividades de un evento
     */
    public function saveActividades(int $eventoId, array $actividades): bool
    {
        // Primero eliminar actividades existentes
        $stmt = $this->db->prepare("DELETE FROM evento_actividades WHERE evento_id = :evento_id");
        $stmt->execute([':evento_id' => $eventoId]);

        // Insertar nuevas
        $stmt = $this->db->prepare("
            INSERT INTO evento_actividades (
                evento_id, nombre_actividad, descripcion
            ) VALUES (
                :evento_id, :nombre, :descripcion
            )
        ");

        foreach ($actividades as $act) {
            $stmt->execute([
                ':evento_id' => $eventoId,
                ':nombre' => $act['nombre'],
                ':descripcion' => $act['descripcion']
            ]);
        }

        return true;
    }

    /**
     * Obtiene actividades de un evento
     */
    public function getActividades(int $eventoId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM evento_actividades
            WHERE evento_id = :id
        ");
        $stmt->execute([':id' => $eventoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
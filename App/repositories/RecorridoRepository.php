<?php
declare(strict_types=1);

namespace App\Repositories;

/**
 * Repositorio de recorridos persistente en base de datos.
 */
use Core\Database;

class RecorridoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene todos los recorridos ordenados por ID.
     * @return array[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM recorridos ORDER BY id_recorrido');
        return $stmt->fetchAll();
    }

    /**
     * Busca un recorrido por su id.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM recorridos WHERE id_recorrido = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Retorna los recorridos filtrados por tipo.
     * @param string $tipo  'Guiado' o 'No Guiado'.
     * @return array[]
     */
    public function findByTipo(string $tipo): array
    {
        $stmt = $this->db->prepare('SELECT * FROM recorridos WHERE tipo = ?');
        $stmt->execute([$tipo]);
        return $stmt->fetchAll();
    }

    /**
     * Búsqueda de texto en el nombre (case insensitive).
     */
    public function search(string $query): array
    {
        $q = '%' . strtolower($query) . '%';
        $stmt = $this->db->prepare('SELECT * FROM recorridos WHERE LOWER(nombre) LIKE ?');
        $stmt->execute([$q]);
        return $stmt->fetchAll();
    }

    /**
     * Crea un nuevo recorrido + relaciones a áreas.
     * Devuelve el id generado o null en caso de fallo.
     */
    public function create(array $data): ?int
    {
        $this->db->beginTransaction();
        $stmt = $this->db->prepare(
            'INSERT INTO recorridos (nombre,tipo,precio,duracion,capacidad) VALUES (?,?,?,?,?)'
        );
        $ok = $stmt->execute([
            $data['nombre'],
            $data['tipo'],
            $data['precio'],
            $data['duracion'],
            $data['capacidad'],
        ]);
        if (!$ok) {
            $this->db->rollBack();
            return null;
        }
        $id = (int)$this->db->lastInsertId();
        if (!empty($data['areas']) && is_array($data['areas'])) {
            $this->setAreas($id, $data['areas']);
        }
        $this->db->commit();
        return $id;
    }

    /**
     * Actualiza un recorrido existente y sus áreas asociadas.
     */
    public function update(int $id, array $data): bool
    {
        $this->db->beginTransaction();
        $stmt = $this->db->prepare(
            'UPDATE recorridos SET nombre = ?, tipo = ?, precio = ?, duracion = ?, capacidad = ? WHERE id_recorrido = ?'
        );
        $ok = $stmt->execute([
            $data['nombre'],
            $data['tipo'],
            $data['precio'],
            $data['duracion'],
            $data['capacidad'],
            $id,
        ]);
        if (!$ok) {
            $this->db->rollBack();
            return false;
        }
        $this->setAreas($id, $data['areas'] ?? []);
        $this->db->commit();
        return true;
    }

    /**
     * Elimina un recorrido (las restricciones de FK en la base se encargan
     * de prevenir borrados si existen reservas activas).
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM recorridos WHERE id_recorrido = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene las áreas asociadas a un recorrido.
     */
    public function getAreas(int $recorridoId): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.* FROM areas a
             INNER JOIN recorrido_area ra ON a.id_area = ra.id_area
             WHERE ra.id_recorrido = ?'
        );
        $stmt->execute([$recorridoId]);
        return $stmt->fetchAll();
    }

    /**
     * Reemplaza las asociaciones área&#x2192;recorrido.
     */
    private function setAreas(int $recorridoId, array $areaIds): void
    {
        $del = $this->db->prepare('DELETE FROM recorrido_area WHERE id_recorrido = ?');
        $del->execute([$recorridoId]);
        if (empty($areaIds)) {
            return;
        }
        $ins = $this->db->prepare('INSERT INTO recorrido_area (id_recorrido,id_area) VALUES (?,?)');
        foreach ($areaIds as $aid) {
            $ins->execute([$recorridoId, $aid]);
        }
    }
}

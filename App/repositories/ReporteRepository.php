<?php
declare(strict_types=1);

namespace App\Repositories;

use Core\Database;
use App\Models\Reporte;

class ReporteRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Guarda un nuevo reporte en la base de datos.
     */
    public function save(int $id_guia_recorrido, string $observaciones): bool
    {
        $sql = "INSERT INTO reportes (id_guia_recorrido, observaciones) VALUES (:id_gr, :obs)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_gr' => $id_guia_recorrido,
            ':obs'   => $observaciones
        ]);
    }

    /**
     * Obtiene el reporte asociado a un recorrido asignado.
     */
    public function findByGuiaRecorrido(int $id_guia_recorrido): ?array
    {
        $sql = "SELECT * FROM reportes WHERE id_guia_recorrido = :id_gr LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_gr' => $id_guia_recorrido]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Obtiene todos los reportes realizados por un guía.
     */
    public function getReportesPorGuia(int $id_usuario): array
    {
        $sql = "
            SELECT 
                rep.*,
                r.nombre AS recorrido_nombre,
                gr.fecha_asignacion
            FROM reportes rep
            INNER JOIN guia_recorrido gr ON gr.id_guia_recorrido = rep.id_guia_recorrido
            INNER JOIN guias g          ON g.id_guia           = gr.id_guia
            INNER JOIN recorridos r     ON r.id_recorrido      = gr.id_recorrido
            WHERE g.id_usuario = :id_usuario
            ORDER BY rep.fecha_reporte DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

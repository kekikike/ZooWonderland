<?php
// app/Repositories/GuiaRepository.php
declare(strict_types=1);

namespace App\Repositories;

use Core\Database;

class GuiaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene todos los recorridos asignados al guía autenticado
     */
    public function getRecorridosAsignados(int $id_usuario): array
    {
        $sql = "
            SELECT 
                gr.fecha_asignacion,
                r.id_recorrido,
                r.nombre,
                r.tipo,
                r.duracion,
                r.capacidad,
                COUNT(res.id_reserva) AS personas_asignadas,
                CONCAT(r.nombre, ' - ', DATE_FORMAT(gr.fecha_asignacion, '%d/%m/%Y')) AS titulo
            FROM Guia_Recorrido gr
            INNER JOIN guias g ON g.id_guia = gr.id_guia
            INNER JOIN usuarios u ON u.id_usuario = g.id_usuario
            INNER JOIN recorridos r ON r.id_recorrido = gr.id_recorrido
            LEFT JOIN reservas res ON res.id_recorrido = r.id_recorrido 
                                  AND res.fecha = gr.fecha_asignacion
            WHERE u.id_usuario = :id_usuario
            GROUP BY gr.id_guia_recorrido, r.id_recorrido, gr.fecha_asignacion
            ORDER BY gr.fecha_asignacion ASC, r.nombre ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);

        return $stmt->fetchAll();
    }

   /**
 * Obtiene los datos de horarios y días de trabajo del guía autenticado
 * @param int $id_usuario ID del usuario guía
 * @return array|null Datos del guía o null si no existe
 */
public function getHorariosGuia(int $id_usuario): ?array
{
    $stmt = $this->db->prepare("
        SELECT 
            horarios,
            dias_trabajo
        FROM guias
        WHERE id_usuario = :id_usuario
        LIMIT 1
    ");

    $stmt->execute([':id_usuario' => $id_usuario]);
    $row = $stmt->fetch();

    return $row ?: null;
}
}
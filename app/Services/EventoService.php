<?php
// app/Services/EventoService.php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\EventoRepository;
use App\Repositories\GuiaRepository;
use App\Repositories\AreaRepository;  // Asumiendo que tienes uno para áreas
use Exception;

class EventoService
{
    private EventoRepository $repo;
    private GuiaRepository $guiaRepo;
    private AreaRepository $areaRepo;

    public function __construct()
    {
        $this->repo = new EventoRepository();
        $this->guiaRepo = new GuiaRepository();
        $this->areaRepo = new AreaRepository();  // Crea si no existe
    }

    public function getAll(array $filtros = []): array
    {
        return $this->repo->findAll($filtros);
    }

    public function getById(int $id): ?array
    {
        $evento = $this->repo->findById($id);
        if ($evento) {
            $evento['actividades'] = $this->repo->getActividades($id);
        }
        return $evento;
    }

    public function create(array $data): array
    {
        try {
            $this->validateData($data, true);

            $id = $this->repo->create($data);

            if (!empty($data['actividades'])) {
                $this->repo->saveActividades($id, $data['actividades']);
            }

            return ['success' => true, 'message' => 'Evento creado correctamente', 'id' => $id];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $this->validateData($data, false);

            $updated = $this->repo->update($id, $data);

            if (!$updated) {
                throw new Exception("No se pudo actualizar el evento");
            }

            if (!empty($data['actividades'])) {
                $this->repo->saveActividades($id, $data['actividades']);
            }

            return ['success' => true, 'message' => 'Evento actualizado correctamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function delete(int $id): array
    {
        try {
            $deleted = $this->repo->delete($id,$estado=0 );
            if (!$deleted) {
                throw new Exception("No se pudo eliminar el evento");
            }

            return ['success' => true, 'message' => 'Evento eliminado correctamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function validateData(array $data, bool $isCreate = false): void
    {
        if (empty($data['nombre_evento'])) {
            throw new Exception("El nombre del evento es obligatorio");
        }

        if (empty($data['fecha_inicio']) || empty($data['fecha_fin'])) {
            throw new Exception("Las fechas de inicio y fin son obligatorias");
        }

        $inicio = new \DateTime($data['fecha_inicio']);
        $fin = new \DateTime($data['fecha_fin']);

        if ($inicio > $fin) {
            throw new Exception("La fecha de inicio no puede ser posterior a la fecha de fin");
        }

        if ($data['tiene_costo'] && (empty($data['precio']) || $data['precio'] <= 0)) {
            throw new Exception("Si el evento tiene costo, el precio debe ser mayor a 0");
        }

        if (empty($data['descripcion'])) {
            throw new Exception("La descripción es obligatoria");
        }

        if (empty($data['lugar'])) {
            throw new Exception("El lugar es obligatorio");
        }

        if ($data['limite_participantes'] < 0) {
            throw new Exception("El límite de participantes no puede ser negativo");
        }

        if (!empty($data['actividades'])) {
            foreach ($data['actividades'] as $act) {
                if (empty($act['nombre'])) {
                    throw new Exception("Cada actividad debe tener un nombre");
                }
            }
        }

        // Para editar: verificar si el evento ya empezó
        if (!$isCreate) {
            $eventoActual = $this->getById($data['id']);
            $inicioActual = new \DateTime($eventoActual['fecha_inicio']);
            if ($inicioActual < new \DateTime()) {
                throw new Exception("No se puede editar un evento que ya inició");
            }
        }
    }

    /**
     * Obtiene guías disponibles para encargados
     */
    public function getGuiasDisponibles(): array
    {
        return $this->guiaRepo->findAll();  // Asumiendo un método findAll en GuiaRepository
    }

    /**
     * Obtiene áreas para lugares
     */
    public function getAreas(): array
    {
        return $this->areaRepo->findAll();  // Asumiendo findAll en AreaRepository
    }
}
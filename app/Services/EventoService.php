<?php
// app/Services/EventoService.php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\EventoRepository;
use App\Repositories\GuiaRepository;
use App\Repositories\AreaRepository;
use Exception;

class EventoService
{
    private EventoRepository $repo;
    private GuiaRepository $guiaRepo;
    private AreaRepository $areaRepo;

    public function __construct()
    {
        $this->repo     = new EventoRepository();
        $this->guiaRepo = new GuiaRepository();
        $this->areaRepo = new AreaRepository();
    }

    public function getAll(array $filtros = []): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repo->findAll($filtros);
    }

    public function getById(int $id): ?\App\Models\Evento
    {
        return $this->repo->findById($id);
    }

    public function create(array $data): array
    {
        try {
            $this->validar($data, true);
            $evento = $this->repo->create($data);
            if (!empty($data['actividades'])) {
                $this->repo->saveActividades($evento->id_evento, $data['actividades']);
            }
            return ['success' => true, 'message' => 'Evento creado correctamente.', 'id' => $evento->id_evento];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $this->validar($data, false, $id);
            $this->repo->update($id, $data);
            if (!empty($data['actividades'])) {
                $this->repo->saveActividades($id, $data['actividades']);
            }
            return ['success' => true, 'message' => 'Evento actualizado correctamente.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function delete(int $id): array
    {
        try {
            $this->repo->delete($id, 0);
            return ['success' => true, 'message' => 'Evento eliminado correctamente.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getGuiasDisponibles(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->guiaRepo->getGuiasDisponibles();
    }

    public function getAreas(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->areaRepo->findAll();
    }

    private function validar(array $data, bool $isCreate, int $id = 0): void
    {
        if (empty($data['nombre_evento']))  throw new Exception("El nombre del evento es obligatorio.");
        if (empty($data['descripcion']))    throw new Exception("La descripción es obligatoria.");
        if (empty($data['lugar']))          throw new Exception("El lugar es obligatorio.");
        if (empty($data['fecha_inicio']) || empty($data['fecha_fin'])) throw new Exception("Las fechas son obligatorias.");

        $inicio = new \DateTime($data['fecha_inicio']);
        $fin    = new \DateTime($data['fecha_fin']);

        if ($inicio > $fin) throw new Exception("La fecha de inicio no puede ser posterior a la de fin.");
        if (!empty($data['tiene_costo']) && (empty($data['precio']) || $data['precio'] <= 0)) {
            throw new Exception("Si el evento tiene costo, el precio debe ser mayor a 0.");
        }
        if (($data['limite_participantes'] ?? 0) < 0) throw new Exception("El límite no puede ser negativo.");

        foreach ($data['actividades'] ?? [] as $act) {
            if (empty($act['nombre'])) throw new Exception("Cada actividad debe tener un nombre.");
        }

        if (!$isCreate && $id > 0) {
            $evento = $this->repo->findById($id);
            if ($evento && $evento->fecha_inicio < now()) {
                throw new Exception("No se puede editar un evento que ya inició.");
            }
        }
    }
}
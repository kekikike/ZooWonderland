<?php
// app/Repositories/EventoRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Evento;
use App\Models\EventoActividad;
use Illuminate\Database\Eloquent\Collection;

class EventoRepository
{
    public function findAll(array $filtros = []): Collection
    {
        return Evento::with('guia.usuario')
            ->where('estado', 1)
            ->when(!empty($filtros['estado']), function ($b) use ($filtros) {
                match ($filtros['estado']) {
                    'vigente'   => $b->where('fecha_inicio', '<=', now())->where('fecha_fin', '>=', now()),
                    'pasado'    => $b->where('fecha_fin', '<', now()),
                    'pendiente' => $b->where('fecha_inicio', '>', now()),
                    default     => null,
                };
            })
            ->when(!empty($filtros['fecha_inicio']), fn($b) => $b->whereDate('fecha_inicio', '>=', $filtros['fecha_inicio']))
            ->when(!empty($filtros['fecha_fin']),    fn($b) => $b->whereDate('fecha_fin',    '<=', $filtros['fecha_fin']))
            ->when(!empty($filtros['nombre']),       fn($b) => $b->where('nombre_evento', 'like', '%' . $filtros['nombre'] . '%'))
            ->when(!empty($filtros['encargado_id']), fn($b) => $b->where('encargado_id', $filtros['encargado_id']))
            ->orderByDesc('fecha_inicio')
            ->get();
    }

    public function findById(int $id): ?Evento
    {
        return Evento::with('actividades')->find($id);
    }

    public function create(array $data): Evento
    {
        return Evento::create($data + ['estado' => 1]);
    }

    public function update(int $id, array $data): bool
    {
        $evento = Evento::find($id);
        if (!$evento) return false;
        return $evento->update($data);
    }

    public function delete(int $id, int $estado): bool
    {
        $evento = Evento::find($id);
        if (!$evento) return false;
        return $evento->update(['estado' => $estado]);
    }

    public function saveActividades(int $eventoId, array $actividades): void
    {
        EventoActividad::where('evento_id', $eventoId)->delete();

        foreach ($actividades as $act) {
            EventoActividad::create([
                'evento_id'        => $eventoId,
                'nombre_actividad' => $act['nombre'],
                'descripcion'      => $act['descripcion'],
            ]);
        }
    }

    public function getActividades(int $eventoId): Collection
    {
        return EventoActividad::where('evento_id', $eventoId)->get();
    }
}
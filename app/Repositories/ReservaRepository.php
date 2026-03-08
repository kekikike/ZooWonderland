<?php
// app/Repositories/ReservaRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Reserva;
use Illuminate\Database\Eloquent\Collection;

class ReservaRepository
{
    public function create(array $data): Reserva
    {
        return Reserva::create([
            'fecha'        => $data['fecha'],
            'hora'         => $data['hora'],
            'cupos'        => $data['cupos'],
            'institucion'  => $data['institucion'],
            'comentario'   => $data['comentario']  ?? null,
            'estado_pago'  => $data['estado_pago'] ?? 0,
            'estado'       => $data['estado']      ?? 1,
            'id_cliente'   => $data['id_cliente'],
            'id_recorrido' => $data['id_recorrido'],
        ]);
    }

    public function findAll(?int $clienteId = null): Collection
    {
        return Reserva::when($clienteId, fn($b) => $b->where('id_cliente', $clienteId))
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();
    }

    public function findById(int $id): ?Reserva
    {
        return Reserva::with(['cliente.usuario', 'recorrido'])->find($id);
    }

    public function findByCliente(int $clienteId): Collection
    {
        return $this->findAll($clienteId);
    }

    public function countByRecorridoFechaHora(int $recorridoId, string $fecha, string $hora): int
    {
        return (int) Reserva::where('id_recorrido', $recorridoId)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->sum('cupos');
    }

    public function countActivasByRecorrido(int $recorridoId): int
    {
        return Reserva::where('id_recorrido', $recorridoId)
            ->where('estado', 1)
            ->count();
    }
}
<?php
// app/Repositories/TicketRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository
{
    public function create(int $compraId, int $recorridoId, string $fecha, string $hora, string $codigoQR): Ticket
    {
        return Ticket::create([
            'hora'         => $hora,
            'fecha'        => $fecha,
            'codigo_qr'    => $codigoQR,
            'id_compra'    => $compraId,
            'id_recorrido' => $recorridoId,
        ]);
    }

    public function findByCompra(int $compraId): Collection
    {
        return Ticket::with('recorrido')->where('id_compra', $compraId)->get();
    }

    public function findByCodigoQR(string $codigo): ?Ticket
    {
        return Ticket::with('recorrido')->where('codigo_qr', $codigo)->first();
    }

    public function countByRecorridoFechaHora(int $recorridoId, string $fecha, string $hora): int
    {
        return Ticket::where('id_recorrido', $recorridoId)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->count();
    }

    public function findById(int $id): ?Ticket
    {
        return Ticket::with(['compra', 'recorrido'])->find($id);
    }

    public function delete(int $id): bool
    {
        $ticket = Ticket::find($id);
        if (!$ticket) return false;
        return (bool) $ticket->delete();
    }
}
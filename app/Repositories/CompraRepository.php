<?php
// app/Repositories/CompraRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Compra;
use App\Models\Cliente;
use App\Models\Ticket;
use App\Models\DetalleCompra;
use Illuminate\Database\Eloquent\Collection;

class CompraRepository
{
    public function findAll(): Collection
    {
        return Compra::orderByDesc('fecha')->get();
    }

    public function findById(int $id): ?Compra
    {
        return Compra::with(['cliente', 'detalles.ticket.recorrido'])->find($id);
    }

    public function findByCliente(int $clienteId, ?string $fechaInicio = null, ?string $fechaFin = null): Collection
    {
        return Compra::where('id_cliente', $clienteId)
            ->when($fechaInicio, fn($b) => $b->where('fecha', '>=', $fechaInicio))
            ->when($fechaFin,    fn($b) => $b->where('fecha', '<=', $fechaFin))
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();
    }

    public function findClienteByUsuario(int $usuarioId): ?Cliente
    {
        return Cliente::where('id_usuario', $usuarioId)->first();
    }

    public function create(array $data): Compra
    {
        return Compra::create([
            'id_cliente'  => $data['id_cliente'],
            'fecha'       => $data['fecha'],
            'hora'        => $data['hora'],
            'monto'       => $data['monto'],
            'estado_pago' => $data['estado_pago'] ?? 0,
        ]);
    }

    public function addDetalle(int $compraId, int $ticketId, float $precioUnitario, int $cantidad = 1): DetalleCompra
    {
        return DetalleCompra::create([
            'id_compra'      => $compraId,
            'id_ticket'      => $ticketId,
            'precio_unitario' => $precioUnitario,
            'cantidad'       => $cantidad,
        ]);
    }

    public function countTicketsSold(int $recorridoId, string $fecha, string $hora): int
    {
        return Ticket::where('id_recorrido', $recorridoId)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->count();
    }
}
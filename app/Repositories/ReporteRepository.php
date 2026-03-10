<?php
// app/Repositories/ReporteRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Reporte;
use App\Models\GuiaRecorrido;
use Illuminate\Database\Eloquent\Collection;

class ReporteRepository
{
    public function save(int $idGuiaRecorrido, string $observaciones): Reporte
    {
        return Reporte::create([
            'id_guia_recorrido' => $idGuiaRecorrido,
            'observaciones'     => $observaciones,
            'fecha_reporte'     => now(),
        ]);
    }

    public function findByGuiaRecorrido(int $idGuiaRecorrido): ?Reporte
    {
        return Reporte::where('id_guia_recorrido', $idGuiaRecorrido)->first();
    }

    public function getReportesPorGuia(int $idUsuario): Collection
    {
        return Reporte::with(['guiaRecorrido.recorrido', 'guiaRecorrido.guia'])
            ->whereHas('guiaRecorrido.guia', fn($b) => $b->where('id_usuario', $idUsuario))
            ->orderByDesc('fecha_registro')
            ->get();
    }

    public function getRecorridosSinReporte(int $idUsuario): Collection
    {
        return GuiaRecorrido::with('recorrido')
            ->whereHas('guia', fn($b) => $b->where('id_usuario', $idUsuario))
            ->whereDate('fecha_asignacion', '<=', now())
            ->whereDoesntHave('reporte')
            ->get();
    }

    public function existeReporte(int $idGuiaRecorrido): bool
    {
        return Reporte::where('id_guia_recorrido', $idGuiaRecorrido)->exists();
    }

    public function getDetalle(int $idGuiaRecorrido): ?GuiaRecorrido
    {
        return GuiaRecorrido::with(['recorrido', 'reporte'])
            ->find($idGuiaRecorrido);
    }

    public function getAll(): Collection
    {
        return Reporte::with(['guiaRecorrido.guia.usuario', 'guiaRecorrido.recorrido'])
            ->orderByDesc('fecha_registro')
            ->get();
    }
}
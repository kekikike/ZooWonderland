<?php
// app/Repositories/GuiaRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Guia;
use App\Models\GuiaRecorrido;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class GuiaRepository
{
    public function findAll(): Collection
    {
        return Guia::with('usuario')->orderBy('id_guia')->get();
    }

    public function findById(int $id): ?Guia
    {
        return Guia::with('usuario')->find($id);
    }

    public function getGuiasDisponibles(): Collection
    {
        return Guia::with('usuario')
            ->whereHas('usuario', fn($b) => $b->where('id_rol', 2)->where('estado', 1))
            ->orderBy('id_guia')
            ->get();
    }

    public function getHorariosGuia(int $idUsuario): ?Guia
    {
        return Guia::where('id_usuario', $idUsuario)
            ->select('horarios', 'dias_trabajo')
            ->first();
    }

    public function getRecorridosAsignados(int $idUsuario): Collection
    {
        return GuiaRecorrido::with(['recorrido', 'reporte'])
            ->whereHas('guia', fn($b) => $b->where('id_usuario', $idUsuario))
            ->orderBy('fecha_asignacion')
            ->get();
    }

    public function getRecorridosPorSemana(int $idUsuario, string $fechaInicio, string $fechaFin): array
    {
        $rows = GuiaRecorrido::with('recorrido')
            ->whereHas('guia', fn($b) => $b->where('id_usuario', $idUsuario))
            ->whereBetween('fecha_asignacion', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_asignacion')
            ->get();

        // Agrupar por fecha igual que antes
        $porFecha = [];
        foreach ($rows as $row) {
            $porFecha[$row->fecha_asignacion][] = $row;
        }
        return $porFecha;
    }

    public function getAreasPorRecorrido(int $idRecorrido): Collection
    {
        return \App\Models\Area::whereHas('recorridos', fn($b) => $b->where('recorridos.id_recorrido', $idRecorrido))
            ->orderBy('nombre')
            ->get();
    }

    public function getDetalleRecorrido(int $idRecorrido, int $idUsuario): ?GuiaRecorrido
    {
        return GuiaRecorrido::with(['recorrido.areas', 'reporte'])
            ->whereHas('guia', fn($b) => $b->where('id_usuario', $idUsuario))
            ->where('id_recorrido', $idRecorrido)
            ->first();
    }

    public function existsAsignacion(int $idGuia, string $fecha, string $horaInicio, int $duracionMinutos): bool
    {
        // La tabla no tiene hora_inicio — solo verificamos que el guía no tenga
        // otro recorrido asignado el mismo día
        return GuiaRecorrido::where('id_guia', $idGuia)
            ->where('fecha_asignacion', $fecha)
            ->exists();
    }

    public function asignarGuia(int $idGuia, int $idRecorrido, string $fecha): GuiaRecorrido
    {
        return GuiaRecorrido::create([
            'id_guia'          => $idGuia,
            'id_recorrido'     => $idRecorrido,
            'fecha_asignacion' => $fecha,
        ]);
    }

    public function getAllAsignaciones(): Collection
    {
        return GuiaRecorrido::with(['guia.usuario', 'recorrido'])
            ->orderByDesc('fecha_asignacion')
            ->get();
    }

    public function deleteAsignacion(int $id): bool
    {
        $asignacion = GuiaRecorrido::find($id);
        if (!$asignacion) return false;
        return (bool) $asignacion->delete();
    }
}
<?php
// app/Repositories/AnimalRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Collection;

class AnimalRepository
{
    public function findAll(): Collection
    {
        return Animal::with('area')->orderBy('id_animal')->get();
    }

    public function findById(int $id): ?Animal
    {
        return Animal::with('area')->find($id);
    }

    public function findByArea(int $areaId): Collection
    {
        return Animal::with('area')
            ->where('id_area', $areaId)
            ->orderBy('id_animal')
            ->get();
    }

    public function search(string $query, ?int $areaId = null): Collection
    {
        $q = '%' . strtolower($query) . '%';

        return Animal::with('area')
            ->where(function ($builder) use ($q) {
                $builder->whereRaw('LOWER(especie) LIKE ?', [$q])
                    ->orWhereRaw('LOWER(nombre_comun) LIKE ?', [$q])
                    ->orWhereRaw('LOWER(descripcion) LIKE ?', [$q])
                    ->orWhereRaw('LOWER(habitat) LIKE ?', [$q]);
            })
            ->when($areaId, fn($b) => $b->where('id_area', $areaId))
            ->orderBy('id_animal')
            ->get();
    }

    public function create(array $data): Animal
    {
        return Animal::create([
            'especie'      => $data['especie'],
            'nombre_comun' => $data['nombre_comun'] ?? null,
            'habitat'      => $data['habitat']      ?? null,
            'descripcion'  => $data['descripcion']  ?? null,
            'foto'         => $data['foto']         ?? null,
            'estado'       => $data['estado']       ?? 'Activo',
            'id_area'      => $data['id_area'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $animal = Animal::find($id);
        if (!$animal) return false;

        return $animal->update([
            'especie'      => $data['especie']      ?? $animal->especie,
            'nombre_comun' => $data['nombre_comun'] ?? $animal->nombre_comun,
            'habitat'      => $data['habitat']      ?? $animal->habitat,
            'descripcion'  => $data['descripcion']  ?? $animal->descripcion,
            'foto'         => $data['foto']         ?? $animal->foto,
            'estado'       => $data['estado']       ?? $animal->estado,
            'id_area'      => $data['id_area']      ?? $animal->id_area,
        ]);
    }

    public function delete(int $id): bool
    {
        $animal = Animal::find($id);
        if (!$animal) return false;
        return (bool) $animal->delete();
    }

    // Soft delete — cambia estado a Inactivo
    public function desactivar(int $id): bool
    {
        $animal = Animal::find($id);
        if (!$animal) return false;
        return $animal->update(['estado' => 'Inactivo']);
    }
}
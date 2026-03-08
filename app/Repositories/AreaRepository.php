<?php
// app/Repositories/AreaRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Collection;

class AreaRepository
{
    public function findAll(): Collection
    {
        return Area::orderBy('id_area')->get();
    }

    public function findById(int $id): ?Area
    {
        return Area::find($id);
    }

    public function findByRestriccion(bool $restringida): Collection
    {
        return Area::where('restringida', $restringida)->get();
    }

    public function search(string $query): Collection
    {
        $q = '%' . strtolower($query) . '%';
        return Area::whereRaw('LOWER(nombre) LIKE ?', [$q])
            ->orWhereRaw('LOWER(descripcion) LIKE ?', [$q])
            ->get();
    }

    public function create(array $data): Area
    {
        return Area::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $area = Area::find($id);
        if (!$area) return false;
        return $area->update($data);
    }

    public function delete(int $id): bool
    {
        $area = Area::find($id);
        if (!$area) return false;
        return (bool) $area->delete();
    }
}
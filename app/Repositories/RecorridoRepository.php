<?php
// app/Repositories/RecorridoRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Recorrido;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RecorridoRepository
{
    public function findAll(): Collection
    {
        return Recorrido::orderBy('id_recorrido')->get();
    }

    public function findById(int $id): ?Recorrido
    {
        return Recorrido::with('areas')->find($id);
    }

    public function findByTipo(string $tipo): Collection
    {
        return Recorrido::where('tipo', $tipo)->get();
    }

    public function search(string $query): Collection
    {
        return Recorrido::whereRaw('LOWER(nombre) LIKE ?', ['%'.strtolower($query).'%'])->get();
    }

    public function create(array $data): ?Recorrido
    {
        return DB::transaction(function () use ($data) {
            $recorrido = Recorrido::create([
                'nombre'    => $data['nombre'],
                'tipo'      => $data['tipo'],
                'precio'    => $data['precio'],
                'duracion'  => $data['duracion'],
                'capacidad' => $data['capacidad'],
            ]);

            if (!empty($data['areas'])) {
                $recorrido->areas()->sync($data['areas']);
            }

            return $recorrido;
        });
    }

    public function update(int $id, array $data): bool
    {
        $recorrido = Recorrido::find($id);
        if (!$recorrido) return false;

        return DB::transaction(function () use ($recorrido, $data) {
            $ok = $recorrido->update([
                'nombre'    => $data['nombre'],
                'tipo'      => $data['tipo'],
                'precio'    => $data['precio'],
                'duracion'  => $data['duracion'],
                'capacidad' => $data['capacidad'],
            ]);

            $recorrido->areas()->sync($data['areas'] ?? []);
            return $ok;
        });
    }

    public function delete(int $id): bool
    {
        $recorrido = Recorrido::find($id);
        if (!$recorrido) return false;
        return (bool) $recorrido->delete();
    }

    public function getAreas(int $recorridoId): Collection
    {
        return Recorrido::findOrFail($recorridoId)->areas;
    }
}
<?php
// app/Http/Controllers/Api/AreaController.php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(): JsonResponse
    {
        $areas = Area::where('estado', 1)->get();
        return response()->json($areas);
    }

    public function show(int $id): JsonResponse
    {
        $area = Area::where('id_area', $id)->where('estado', 1)->first();
        if (!$area) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        }
        return response()->json($area);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'restringida' => 'boolean',
        ]);

        $area = Area::create([
            'nombre'      => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'restringida' => $data['restringida'] ?? false,
            'estado'      => 1,
        ]);

        return response()->json($area, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $area = Area::where('id_area', $id)->where('estado', 1)->first();
        if (!$area) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        }

        $data = $request->validate([
            'nombre'      => 'sometimes|string|max:150',
            'descripcion' => 'nullable|string',
            'restringida' => 'boolean',
        ]);

        $area->update($data);

        return response()->json($area);
    }

    public function destroy(int $id): JsonResponse
    {
        $area = Area::where('id_area', $id)->where('estado', 1)->first();
        if (!$area) {
            return response()->json(['message' => 'Área no encontrada.'], 404);
        }

        $area->update(['estado' => 0]);

        return response()->json(['message' => 'Área eliminada correctamente.']);
    }
}
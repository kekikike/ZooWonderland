<?php
// app/Http/Controllers/Api/RecorridoController.php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recorrido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecorridoController extends Controller
{
    public function index(): JsonResponse
    {
        $recorridos = Recorrido::where('estado', 1)->get();
        return response()->json($recorridos);
    }

    public function show(int $id): JsonResponse
    {
        $recorrido = Recorrido::where('id_recorrido', $id)->where('estado', 1)->first();
        if (!$recorrido) {
            return response()->json(['message' => 'Recorrido no encontrado.'], 404);
        }
        return response()->json($recorrido);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'tipo'      => 'required|in:Guiado,Libre',
            'precio'    => 'required|numeric|min:0',
            'duracion'  => 'required|integer|min:1',
            'capacidad' => 'required|integer|min:1',
        ]);

        $recorrido = Recorrido::create([
            'nombre'    => $data['nombre'],
            'tipo'      => $data['tipo'],
            'precio'    => $data['precio'],
            'duracion'  => $data['duracion'],
            'capacidad' => $data['capacidad'],
            'estado'    => 1,
        ]);

        return response()->json($recorrido, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $recorrido = Recorrido::where('id_recorrido', $id)->where('estado', 1)->first();
        if (!$recorrido) {
            return response()->json(['message' => 'Recorrido no encontrado.'], 404);
        }

        $data = $request->validate([
            'nombre'    => 'sometimes|string|max:150',
            'tipo'      => 'sometimes|in:Guiado,Libre',
            'precio'    => 'sometimes|numeric|min:0',
            'duracion'  => 'sometimes|integer|min:1',
            'capacidad' => 'sometimes|integer|min:1',
        ]);

        $recorrido->update($data);

        return response()->json($recorrido);
    }

    public function destroy(int $id): JsonResponse
    {
        $recorrido = Recorrido::where('id_recorrido', $id)->where('estado', 1)->first();
        if (!$recorrido) {
            return response()->json(['message' => 'Recorrido no encontrado.'], 404);
        }

        $recorrido->update(['estado' => 0]);

        return response()->json(['message' => 'Recorrido eliminado correctamente.']);
    }
}
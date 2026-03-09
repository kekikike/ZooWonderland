<?php
// app/Http/Controllers/Api/EventoController.php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(): JsonResponse
    {
        $eventos = Evento::where('estado', 1)->get();
        return response()->json($eventos);
    }

    public function show(int $id): JsonResponse
    {
        $evento = Evento::where('id_evento', $id)->where('estado', 1)->first();
        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado.'], 404);
        }
        return response()->json($evento);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre_evento'        => 'required|string|max:200',
            'descripcion'          => 'nullable|string',
            'fecha_inicio'         => 'required|date',
            'fecha_fin'            => 'required|date|after_or_equal:fecha_inicio',
            'tiene_costo'          => 'boolean',
            'precio'               => 'nullable|numeric|min:0',
            'encargado_id'         => 'nullable|integer|exists:guias,id_guia',
            'lugar'                => 'nullable|string|max:200',
            'limite_participantes' => 'nullable|integer|min:1',
        ]);

        $evento = Evento::create([
            'nombre_evento'        => $data['nombre_evento'],
            'descripcion'          => $data['descripcion']          ?? null,
            'fecha_inicio'         => $data['fecha_inicio'],
            'fecha_fin'            => $data['fecha_fin'],
            'tiene_costo'          => $data['tiene_costo']          ?? false,
            'precio'               => $data['precio']               ?? null,
            'encargado_id'         => $data['encargado_id']         ?? null,
            'lugar'                => $data['lugar']                ?? null,
            'limite_participantes' => $data['limite_participantes'] ?? null,
            'estado'               => 1,
        ]);

        return response()->json($evento, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $evento = Evento::where('id_evento', $id)->where('estado', 1)->first();
        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado.'], 404);
        }

        $data = $request->validate([
            'nombre_evento'        => 'sometimes|string|max:200',
            'descripcion'          => 'nullable|string',
            'fecha_inicio'         => 'sometimes|date',
            'fecha_fin'            => 'sometimes|date|after_or_equal:fecha_inicio',
            'tiene_costo'          => 'boolean',
            'precio'               => 'nullable|numeric|min:0',
            'encargado_id'         => 'nullable|integer|exists:guias,id_guia',
            'lugar'                => 'nullable|string|max:200',
            'limite_participantes' => 'nullable|integer|min:1',
        ]);

        $evento->update($data);

        return response()->json($evento);
    }

    public function destroy(int $id): JsonResponse
    {
        $evento = Evento::where('id_evento', $id)->where('estado', 1)->first();
        if (!$evento) {
            return response()->json(['message' => 'Evento no encontrado.'], 404);
        }

        $evento->update(['estado' => 0]);

        return response()->json(['message' => 'Evento eliminado correctamente.']);
    }
}
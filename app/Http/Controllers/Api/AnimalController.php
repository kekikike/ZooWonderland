<?php
// app/Http/Controllers/Api/AnimalController.php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AnimalRepository;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    private AnimalRepository $repo;

    public function __construct()
    {
        $this->repo = new AnimalRepository();
    }

    // GET /api/animales
    public function index(Request $request)
    {
        $buscar = trim($request->input('q', ''));
        $area   = (int)$request->input('area', 0);

        if ($buscar && $area) {
            $animales = $this->repo->search($buscar, $area);
        } elseif ($buscar) {
            $animales = $this->repo->search($buscar);
        } elseif ($area) {
            $animales = $this->repo->findByArea($area);
        } else {
            $animales = $this->repo->findAll();
        }

        return response()->json(['success' => true, 'data' => $animales]);
    }

    // GET /api/animales/{id}
    public function show(Request $request, int $id)
    {
        $animal = $this->repo->findById($id);

        if (!$animal) {
            return response()->json(['success' => false, 'message' => "Animal #{$id} no encontrado."], 404);
        }

        return response()->json(['success' => true, 'data' => $animal]);
    }

    // POST /api/animales
    public function store(Request $request)
    {
        $body   = $request->json()->all();
        $errors = $this->validar($body);

        if (!empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors], 422);
        }

        $animal = $this->repo->create($body);
        return response()->json(['success' => true, 'data' => $animal, 'message' => 'Animal creado.'], 201);
    }

    // PUT /api/animales/{id}
    public function update(Request $request, int $id)
    {
        $animal = $this->repo->findById($id);
        if (!$animal) {
            return response()->json(['success' => false, 'message' => "Animal #{$id} no encontrado."], 404);
        }

        $body   = $request->json()->all();
        $errors = $this->validar($body);

        if (!empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors], 422);
        }

        $this->repo->update($id, $body);
        return response()->json(['success' => true, 'data' => $this->repo->findById($id), 'message' => 'Animal actualizado.']);
    }

    // DELETE /api/animales/{id}
    public function destroy(Request $request, int $id)
    {
        $animal = $this->repo->findById($id);
        if (!$animal) {
            return response()->json(['success' => false, 'message' => "Animal #{$id} no encontrado."], 404);
        }

        $this->repo->desactivar($id);
        return response()->json(['success' => true, 'message' => 'Animal desactivado.']);
    }

    private function validar(array $body): array
    {
        $errors = [];
        if (empty($body['especie']))     $errors[] = 'especie es obligatorio.';
        if (empty($body['descripcion'])) $errors[] = 'descripcion es obligatoria.';
        if (empty($body['id_area']))     $errors[] = 'id_area es obligatorio.';
        return $errors;
    }
}
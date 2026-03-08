<?php
// app/Http/Controllers/Web/AnimalController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\AnimalRepository;
use App\Repositories\AreaRepository;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    private AnimalRepository $animalRepo;
    private AreaRepository $areaRepo;

    public function __construct()
    {
        $this->animalRepo = new AnimalRepository();
        $this->areaRepo   = new AreaRepository();
    }

    public function index(Request $request)
    {
        $user    = $request->attributes->get('auth_user');
        $buscar  = trim($request->input('buscar', ''));
        $animales = $buscar
            ? $this->animalRepo->search($buscar)
            : $this->animalRepo->findAll();

        return view('admin.animales.index', compact('user', 'animales', 'buscar'));
    }

    public function crear(Request $request)
    {
        $user  = $request->attributes->get('auth_user');
        $areas = $this->areaRepo->findAll();
        return view('admin.animales.crear', compact('user', 'areas'));
    }

    public function guardar(Request $request)
    {
        $data = [
            'nombre'      => trim($request->input('nombre', '')),
            'especie'     => trim($request->input('especie', '')),
            'descripcion' => trim($request->input('descripcion', '')),
            'id_area'     => (int)$request->input('id_area', 0),
            'estado'      => 1,
        ];

        if (!$data['nombre'] || !$data['especie'] || !$data['id_area']) {
            $areas = $this->areaRepo->findAll();
            return view('admin.animales.crear', [
                'user'   => $request->attributes->get('auth_user'),
                'areas'  => $areas,
                'error'  => 'Nombre, especie y área son obligatorios.',
                'old'    => $data,
            ]);
        }

        $this->animalRepo->create($data);
        return redirect('/admin/animales')->with('success', 'Animal creado correctamente.');
    }

    public function editar(Request $request)
    {
        $user   = $request->attributes->get('auth_user');
        $id     = (int)$request->input('id', 0);
        $animal = $this->animalRepo->findById($id);
        if (!$animal) abort(404);

        $areas  = $this->areaRepo->findAll();
        return view('admin.animales.editar', compact('user', 'animal', 'areas'));
    }

    public function actualizar(Request $request)
    {
        $id   = (int)$request->input('id', 0);
        $data = [
            'nombre'      => trim($request->input('nombre', '')),
            'especie'     => trim($request->input('especie', '')),
            'descripcion' => trim($request->input('descripcion', '')),
            'id_area'     => (int)$request->input('id_area', 0),
        ];

        if (!$data['nombre'] || !$data['especie'] || !$data['id_area']) {
            $animal = $this->animalRepo->findById($id);
            $areas  = $this->areaRepo->findAll();
            return view('admin.animales.editar', [
                'user'   => $request->attributes->get('auth_user'),
                'animal' => $animal,
                'areas'  => $areas,
                'error'  => 'Nombre, especie y área son obligatorios.',
            ]);
        }

        $this->animalRepo->update($id, $data);
        return redirect('/admin/animales')->with('success', 'Animal actualizado.');
    }

    public function eliminar(Request $request)
    {
        $id = (int)$request->input('id', 0);
        $this->animalRepo->desactivar($id);
        return redirect('/admin/animales')->with('success', 'Animal desactivado.');
    }
}
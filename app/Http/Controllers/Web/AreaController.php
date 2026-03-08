<?php
// app/Http/Controllers/Web/AreaController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\AreaRepository;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    private AreaRepository $areaRepo;

    public function __construct()
    {
        $this->areaRepo = new AreaRepository();
    }

    public function index(Request $request)
    {
        $user  = $request->attributes->get('auth_user');
        $areas = $this->areaRepo->findAll();
        return view('admin.areas.index', compact('user', 'areas'));
    }

    public function crear(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        return view('admin.areas.crear', compact('user'));
    }

    public function guardar(Request $request)
    {
        $data = [
            'nombre'       => trim($request->input('nombre', '')),
            'descripcion'  => trim($request->input('descripcion', '')),
            'restriccion'  => trim($request->input('restriccion', '')),
        ];

        if (!$data['nombre']) {
            return view('admin.areas.crear', [
                'user'  => $request->attributes->get('auth_user'),
                'error' => 'El nombre del área es obligatorio.',
                'old'   => $data,
            ]);
        }

        $this->areaRepo->create($data);
        return redirect('/admin/areas')->with('success', 'Área creada correctamente.');
    }

    public function editar(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        $id   = (int)$request->input('id', 0);
        $area = $this->areaRepo->findById($id);
        if (!$area) abort(404);

        return view('admin.areas.editar', compact('user', 'area'));
    }

    public function actualizar(Request $request)
    {
        $id   = (int)$request->input('id', 0);
        $data = [
            'nombre'      => trim($request->input('nombre', '')),
            'descripcion' => trim($request->input('descripcion', '')),
            'restriccion' => trim($request->input('restriccion', '')),
        ];

        if (!$data['nombre']) {
            $area = $this->areaRepo->findById($id);
            return view('admin.areas.editar', [
                'user'  => $request->attributes->get('auth_user'),
                'area'  => $area,
                'error' => 'El nombre es obligatorio.',
            ]);
        }

        $this->areaRepo->update($id, $data);
        return redirect('/admin/areas')->with('success', 'Área actualizada.');
    }

    public function eliminar(Request $request)
    {
        $id = (int)$request->input('id', 0);
        $this->areaRepo->delete($id);
        return redirect('/admin/areas')->with('success', 'Área eliminada.');
    }
}
<?php
// app/Http/Controllers/Web/AdminController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\AnimalRepository;
use App\Repositories\AreaRepository;
use App\Repositories\EventoRepository;
use App\Repositories\GuiaRepository;
use App\Repositories\RecorridoRepository;
use App\Repositories\ReporteRepository;
use App\Repositories\UsuarioRepository;
use App\Services\EventoService;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private UsuarioRepository $usuarioRepo;
    private RecorridoRepository $recorridoRepo;
    private AnimalRepository $animalRepo;
    private AreaRepository $areaRepo;
    private GuiaRepository $guiaRepo;
    private EventoService $eventoService;
    private EventoRepository $eventoRepo;
    private ReporteRepository $reporteRepo;

    public function __construct()
    {
        $this->usuarioRepo   = new UsuarioRepository();
        $this->recorridoRepo = new RecorridoRepository();
        $this->animalRepo    = new AnimalRepository();
        $this->areaRepo      = new AreaRepository();
        $this->guiaRepo      = new GuiaRepository();
        $this->eventoService = new EventoService();
        $this->eventoRepo    = new EventoRepository();
        $this->reporteRepo   = new ReporteRepository();
    }

    // ── DASHBOARD ────────────────────────────────────────────────
    public function dashboard(Request $request)
    {
        $user = $request->attributes->get('auth_user');

        $recorridos      = $this->recorridoRepo->findAll();
        $totalRecorridos = $recorridos->count();
        $totalAreas      = $this->areaRepo->findAll()->count();
        $totalAnimales   = $this->animalRepo->findAll()->count();
        $totalGuias      = \App\Models\Guia::count();
        $totalReservas   = \App\Models\Reserva::count();
        $totalIngresos   = \App\Models\Compra::sum('monto') ?? 0;

        return view('admin.dashboard', compact(
            'user',
            'recorridos',
            'totalRecorridos',
            'totalAreas',
            'totalAnimales',
            'totalGuias',
            'totalReservas',
            'totalIngresos'
        ));
    }

    // ── USUARIOS ─────────────────────────────────────────────────
    public function usuarios(Request $request)
    {
        $user     = $request->attributes->get('auth_user');
        $filtros  = $request->only(['busqueda', 'rol', 'estado', 'recorrido']);
        $usuarios = $this->usuarioRepo->getUsuariosFiltrados(
            trim($filtros['busqueda']  ?? ''),
            trim($filtros['rol']       ?? ''),
            (int)($filtros['recorrido'] ?? 0),
            trim($filtros['estado']    ?? ''),
            (int)$user->id_usuario
        );
        $recorridos = $this->usuarioRepo->getRecorridosParaFiltro();

        return view('admin.usuarios.index', compact('user', 'usuarios', 'filtros', 'recorridos'));
    }

    public function editarUsuarioForm(Request $request)
    {
        $user    = $request->attributes->get('auth_user');
        $id      = (int)$request->input('id', 0);
        $usuario = $this->usuarioRepo->getUsuarioPorId($id);
        if (!$usuario) abort(404);

        return view('admin.usuarios.form', compact('user', 'usuario'));
    }

    public function editarUsuarioPost(Request $request)
    {
        $id = (int)$request->input('id', 0);

        $this->usuarioRepo->actualizarUsuario(
            $id,
            trim($request->input('nombre1',        '')),
            trim($request->input('nombre2',        '')),
            trim($request->input('apellido1',      '')),
            trim($request->input('apellido2',      '')),
            (int)$request->input('ci',             0),
            trim($request->input('telefono',       '')),
            trim($request->input('rol',            '')),
            trim($request->input('correo',         '')),
            trim($request->input('nombre_usuario', ''))
        );
        return redirect('/admin/usuarios')->with('success', 'Usuario actualizado.');
    }

    public function toggleEstado(Request $request)
    {
        $id     = (int)$request->input('id', 0);
        $estado = (int)$request->input('estado', 0);
        $this->usuarioRepo->cambiarEstado($id, $estado);
        return redirect('/admin/usuarios')->with('success', 'Estado actualizado.');
    }
    public function crearUsuario(Request $request)
    {
        $nombre1   = trim($request->input('nombre1', ''));
        $nombre2   = trim($request->input('nombre2', ''));
        $apellido1 = trim($request->input('apellido1', ''));
        $apellido2 = trim($request->input('apellido2', ''));
        $ci        = trim($request->input('ci', ''));
        $telefono  = trim($request->input('telefono', ''));
        $correo    = trim($request->input('correo', ''));
        $usuario   = trim($request->input('nombre_usuario', ''));
        $rol       = trim($request->input('rol', ''));
        $password  = $request->input('password', '');
        $confirm   = $request->input('password_confirm', '');

        $soloLetras = '/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/u';

        if (!$nombre1 || !preg_match($soloLetras, $nombre1))
            return redirect('/admin/usuarios')->with('modal_error', 'El primer nombre es inválido.');
        if ($nombre2 && !preg_match($soloLetras, $nombre2))
            return redirect('/admin/usuarios')->with('modal_error', 'El segundo nombre es inválido.');
        if (!$apellido1 || !preg_match($soloLetras, $apellido1))
            return redirect('/admin/usuarios')->with('modal_error', 'El primer apellido es inválido.');
        if ($apellido2 && !preg_match($soloLetras, $apellido2))
            return redirect('/admin/usuarios')->with('modal_error', 'El segundo apellido es inválido.');
        if (!preg_match('/^\d{7,8}$/', $ci))
            return redirect('/admin/usuarios')->with('modal_error', 'El CI debe tener entre 7 y 8 números.');
        if (!preg_match('/^\d{7,8}$/', $telefono))
            return redirect('/admin/usuarios')->with('modal_error', 'El teléfono debe tener entre 7 y 8 números.');
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL))
            return redirect('/admin/usuarios')->with('modal_error', 'El correo electrónico es inválido.');
        if (!$usuario)
            return redirect('/admin/usuarios')->with('modal_error', 'El nombre de usuario es obligatorio.');
        if (!in_array($rol, ['administrador', 'guia'], true))
            return redirect('/admin/usuarios')->with('modal_error', 'El rol debe ser administrador o guía.');
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || strlen($password) < 6)
            return redirect('/admin/usuarios')->with('modal_error', 'La contraseña debe tener al menos una mayúscula y un número.');
        if ($password !== $confirm)
            return redirect('/admin/usuarios')->with('modal_error', 'Las contraseñas no coinciden.');

        try {
            $this->usuarioRepo->createAdminOrGuia([
                'nombre1'        => $nombre1,
                'nombre2'        => $nombre2 ?: null,
                'apellido1'      => $apellido1,
                'apellido2'      => $apellido2 ?: null,
                'ci'             => (int)$ci,
                'telefono'       => $telefono,
                'correo'         => $correo,
                'nombre_usuario' => $usuario,
                'rol'            => $rol,
                'password'       => $password,
                'estado'         => 1,
            ]);
        } catch (\Exception $e) {
            $msg = str_contains($e->getMessage(), 'ci')             ? 'El CI ya está registrado.'       :
                (str_contains($e->getMessage(), 'correo')         ? 'El correo ya está registrado.'   :
                (str_contains($e->getMessage(), 'nombre_usuario') ? 'El nombre de usuario ya existe.' :
                'Error al crear el usuario: ' . $e->getMessage()));
            return redirect('/admin/usuarios')->with('modal_error', $msg);
        }

        return redirect('/admin/usuarios')->with('success', 'Usuario creado exitosamente.');
    }

    // ── RECORRIDOS ───────────────────────────────────────────────
    public function recorridos(Request $request)
    {
        $user       = $request->attributes->get('auth_user');
        $recorridos = $this->recorridoRepo->findAll();
        return view('admin.recorridos.index', compact('user', 'recorridos'));
    }

    public function crearRecorrido(Request $request)
    {
        $user  = $request->attributes->get('auth_user');
        $areas = $this->areaRepo->findAll();
        $recorrido      = null;
        $selectedAreas  = [];
        return view('admin.recorridos.form', compact('user', 'areas', 'recorrido', 'selectedAreas'));
    }

    public function guardarRecorrido(Request $request)
    {
        $data = [
            'nombre'      => trim($request->input('nombre', '')),
            'tipo'        => trim($request->input('tipo', '')),
            'descripcion' => trim($request->input('descripcion', '')),
            'precio'      => (float)$request->input('precio', 0),
            'duracion'    => (int)$request->input('duracion', 0),
            'capacidad'   => (int)$request->input('capacidad', 0),
            'areas'       => $request->input('areas', []),
        ];

        $this->recorridoRepo->create($data);
        return redirect('/admin/recorridos')->with('success', 'Recorrido creado.');
    }

    public function editarRecorrido(Request $request)
    {
        $user      = $request->attributes->get('auth_user');
        $id        = (int)$request->input('id', 0);
        $recorrido = $this->recorridoRepo->findById($id);
        if (!$recorrido) abort(404);

        $areas          = $this->areaRepo->findAll();
        $areasAsignadas = $this->recorridoRepo->getAreas($id)->pluck('id_area')->toArray();

        return view('admin.recorridos.form', compact('user', 'recorrido', 'areas', 'areasAsignadas'));
    }

    public function actualizarRecorrido(Request $request)
    {
        $id = (int)$request->input('id', 0);

        if ($id <= 0) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'ID de recorrido inválido.'], 400);
            }
            return redirect('/admin/recorridos')->with('error', 'ID de recorrido inválido.');
        }

        $data = [
            'nombre'    => trim($request->input('nombre', '')),
            'tipo'      => trim($request->input('tipo', '')),
            'precio'    => (float)$request->input('precio', 0),
            'duracion'  => (int)$request->input('duracion', 0),
            'capacidad' => (int)$request->input('capacidad', 0),
            'areas'     => $request->input('areas', []),
        ];

        try {
            $this->recorridoRepo->update($id, $data);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Recorrido actualizado exitosamente.']);
            }
            return redirect('/admin/recorridos')->with('success', 'Recorrido actualizado.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error al actualizar el recorrido: ' . $e->getMessage()], 500);
            }
            return redirect('/admin/recorridos')->with('error', 'Error al actualizar el recorrido.');
        }
    }

    public function eliminarRecorrido(Request $request)
    {
        $id = (int)$request->input('id_recorrido', 0);

        if ($id <= 0) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'ID de recorrido inválido.'], 400);
            }
            return redirect('/admin/recorridos')->with('error', 'ID de recorrido inválido.');
        }

        try {
            $this->recorridoRepo->desactivar($id);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Recorrido desactivado exitosamente.']);
            }
            return redirect('/admin/recorridos')->with('success', 'Recorrido desactivado.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error al desactivar el recorrido: ' . $e->getMessage()], 500);
            }
            return redirect('/admin/recorridos')->with('error', 'Error al desactivar el recorrido.');
        }
    }

    public function toggleEstadoRecorrido(Request $request)
    {
        $id = (int)$request->input('id_recorrido', 0);

        if ($id <= 0) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'ID de recorrido inválido.'], 400);
            }
            return redirect('/admin/recorridos')->with('error', 'ID de recorrido inválido.');
        }

        try {
            $recorrido = $this->recorridoRepo->findById($id);
            if (!$recorrido) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Recorrido no encontrado.'], 404);
                }
                return redirect('/admin/recorridos')->with('error', 'Recorrido no encontrado.');
            }

            $nuevoEstado = $recorrido->estado == 1 ? 0 : 1;
            $this->recorridoRepo->update($id, ['estado' => $nuevoEstado]);

            $accion = $nuevoEstado == 1 ? 'activado' : 'desactivado';

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => "Recorrido {$accion} exitosamente."]);
            }
            return redirect('/admin/recorridos')->with('success', "Recorrido {$accion}.");
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error al cambiar el estado del recorrido: ' . $e->getMessage()], 500);
            }
            return redirect('/admin/recorridos')->with('error', 'Error al cambiar el estado del recorrido.');
        }
    }

    // ── ANIMALES ─────────────────────────────────────────────────
    public function animales(Request $request)
    {
        $user     = $request->attributes->get('auth_user');
        $buscar   = trim($request->input('buscar', ''));
        $animales = $buscar
            ? $this->animalRepo->search($buscar)
            : $this->animalRepo->findAll();

        $areas = $this->areaRepo->findAll();

        return view('admin.animales.index', compact('user', 'animales', 'buscar', 'areas'));
    }

    public function crearAnimal(Request $request)
    {
        $user  = $request->attributes->get('auth_user');
        $areas = $this->areaRepo->findAll();
        $animal = null;
        return view('admin.animales.form', compact('user', 'areas', 'animal'));
    }

    public function guardarAnimal(Request $request)
    {
        $data = [
            'nombre'      => trim($request->input('nombre', '')),
            'especie'     => trim($request->input('especie', '')),
            'descripcion' => trim($request->input('descripcion', '')),
            'id_area'     => (int)$request->input('id_area', 0),
            'estado'      => 1,
        ];

        $this->animalRepo->create($data);
        return redirect('/admin/animales')->with('success', 'Animal creado.');
    }

    public function editarAnimal(Request $request)
    {
        $user   = $request->attributes->get('auth_user');
        $id     = (int)$request->input('id', 0);
        $animal = $this->animalRepo->findById($id);
        if (!$animal) abort(404);

        $areas = $this->areaRepo->findAll();
        return view('admin.animales.form', compact('user', 'animal', 'areas'));
    }

    public function actualizarAnimal(Request $request)
    {
        $id   = (int)$request->input('id', 0);
        $data = [
            'nombre'      => trim($request->input('nombre', '')),
            'especie'     => trim($request->input('especie', '')),
            'descripcion' => trim($request->input('descripcion', '')),
            'id_area'     => (int)$request->input('id_area', 0),
        ];

        $this->animalRepo->update($id, $data);
        return redirect('/admin/animales')->with('success', 'Animal actualizado.');
    }

    public function eliminarAnimal(Request $request)
    {
        $id = (int)$request->input('id', 0);
        $this->animalRepo->desactivar($id);
        return redirect('/admin/animales')->with('success', 'Animal desactivado.');
    }

    // ── ASIGNACIONES ─────────────────────────────────────────────
    public function asignaciones(Request $request)
    {
        $user         = $request->attributes->get('auth_user');
        $asignaciones = $this->guiaRepo->getAllAsignaciones();
        return view('admin.asignaciones.index', compact('user', 'asignaciones'));
    }

    public function crearAsignacion(Request $request)
    {
        $user       = $request->attributes->get('auth_user');
        $guias      = $this->guiaRepo->findAll();
        $recorridos = $this->recorridoRepo->findAll();
        return view('admin.asignaciones.crear', compact('user', 'guias', 'recorridos'));
    }

    public function guardarAsignacion(Request $request)
    {
        $idGuia      = (int)$request->input('id_guia', 0);
        $idRecorrido = (int)$request->input('id_recorrido', 0);
        $fecha       = $request->input('fecha', '');

        if (!$idGuia || !$idRecorrido || !$fecha) {
            return redirect('/admin/asignaciones/crear')->with('error', 'Todos los campos son obligatorios.');
        }

        if ($this->guiaRepo->existsAsignacion($idGuia, $fecha, '', 0)) {
            return redirect('/admin/asignaciones/crear')->with('error', 'El guía ya tiene una asignación ese día.');
        }

        try {
            $this->guiaRepo->asignarGuia($idGuia, $idRecorrido, $fecha);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return redirect('/admin/asignaciones/crear')->with('error', 'Este guía ya tiene asignado ese recorrido.');
        }

        return redirect('/admin/asignaciones')->with('success', 'Asignación creada.');
    }

    public function eliminarAsignacion(Request $request)
    {
        $id = (int)$request->input('id', 0);
        $this->guiaRepo->deleteAsignacion($id);
        return redirect('/admin/asignaciones')->with('success', 'Asignación eliminada.');
    }

    // ── EVENTOS ──────────────────────────────────────────────────
    public function eventos(Request $request)
    {
        $user    = $request->attributes->get('auth_user');
        $filtros = $request->only(['estado', 'fecha_inicio', 'fecha_fin', 'encargado_id', 'nombre']);
        $eventos = $this->eventoService->getAll($filtros);
        $guias   = $this->guiaRepo->findAll();
        return view('admin.eventos.index', compact('user', 'eventos', 'guias', 'filtros'));
    }

    public function eventoForm(Request $request)
    {
        $user   = $request->attributes->get('auth_user');
        $id     = (int)$request->input('id', 0);
        $evento = $id ? $this->eventoService->getById($id) : null;
        if ($evento && $evento->esPasado()) {
        return redirect('/admin/eventos')
            ->with('error', 'No se puede editar un evento que ya finalizó.');
    }
        $guias  = $this->eventoService->getGuiasDisponibles();
        $areas  = $this->areaRepo->findAll();

        return view('admin.eventos.form', compact('user', 'evento', 'guias', 'areas'));
    }

    public function saveEvento(Request $request)
    {
        $id   = (int)$request->input('id', 0);
        $data = [
            'nombre_evento'        => trim($request->input('nombre_evento', '')),
            'descripcion'          => trim($request->input('descripcion', '')),
            'fecha_inicio'         => $request->input('fecha_inicio', ''),
            'fecha_fin'            => $request->input('fecha_fin', ''),
            'tiene_costo'          => (bool)$request->input('tiene_costo', false),
            'precio'               => (float)$request->input('precio', 0),
            'encargado_id'         => (int)$request->input('encargado_id', 0),
            'lugar'                => trim($request->input('lugar', '')),
            'limite_participantes' => (int)$request->input('limite_participantes', 0),
            'estado'               => (int)$request->input('estado', 1),
            'actividades'          => (function() use ($request) {
                $nombres = $request->input('actividad_nombre', []);
                $descs   = $request->input('actividad_desc',   []);
                $result  = [];
                foreach ($nombres as $i => $nombre) {
                    if (!empty(trim($nombre))) {
                        $result[] = [
                            'nombre'      => trim($nombre),
                            'descripcion' => trim($descs[$i] ?? ''),
                        ];
                    }
                }
                return $result;
            })(),
            'id'                   => $id,
        ];

        $result = $id
            ? $this->eventoService->update($id, $data)
            : $this->eventoService->create($data);
 
       if (!$result['success']) {
    $guias  = $this->eventoService->getGuiasDisponibles();
    $areas  = $this->areaRepo->findAll();                    // ← agregar
    $evento = $id ? $this->eventoService->getById($id) : null;

    return view('admin.eventos.form', [
        'user'   => $request->attributes->get('auth_user'),
        'evento' => $evento,
        'guias'  => $guias,
        'areas'  => $areas,                                  // ← agregar
        'error'  => $result['message'],
    ]);
 
        }
       
        return redirect('/admin/eventos')->with('success', $result['message']);
    }

    public function deleteEvento(Request $request)
    {
        $id     = (int)$request->input('id', 0);
        $result = $this->eventoService->delete($id);

        return redirect('/admin/eventos')->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function detalleEvento(Request $request)
    {
        $user   = $request->attributes->get('auth_user');
        $id     = (int)$request->input('id', 0);
        $evento = $this->eventoService->getById($id);
        if (!$evento) abort(404);

        return view('admin.eventos.detalle', compact('user', 'evento'));
    }

        // ── REPORTES 
    public function reportes(Request $request)
    {
        $user   = $request->attributes->get('auth_user');
        $inicio = $request->input('inicio', '');
        $fin    = $request->input('fin',    '');

        // Compras
        $queryCompras = DB::table('compras')
            ->select([
                'fecha',
                DB::raw('COUNT(*) as total_compras'),
                DB::raw('SUM(monto) as total_ingresos'),
                DB::raw('SUM(CASE WHEN estado_pago = 1 THEN monto ELSE 0 END) as pagadas'),
                DB::raw('SUM(CASE WHEN estado_pago = 0 THEN monto ELSE 0 END) as pendientes'),
            ])
            ->where('estado', 1);

        if ($inicio) $queryCompras->whereDate('fecha', '>=', $inicio);
        if ($fin)    $queryCompras->whereDate('fecha', '<=', $fin);

        $compras = $queryCompras
            ->groupBy('fecha')
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(fn($c) => (array) $c)
            ->toArray();

        // Reservas
        $queryReservas = DB::table('reservas')
            ->select([
                DB::raw('DATE(fecha) as fecha'),
                DB::raw('COUNT(*) as total_reservas'),
                DB::raw('SUM(cupos) as total_cupos'),
                DB::raw('SUM(CASE WHEN estado_pago = 1 THEN 1 ELSE 0 END) as pagadas'),
                DB::raw('SUM(CASE WHEN estado_pago = 0 THEN 1 ELSE 0 END) as pendientes'),
            ]);

        if ($inicio) $queryReservas->whereDate('fecha', '>=', $inicio);
        if ($fin)    $queryReservas->whereDate('fecha', '<=', $fin);

        $reservas = $queryReservas
            ->groupBy(DB::raw('DATE(fecha)'))
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(fn($r) => (array) $r)
            ->toArray();

        // Guías
        $reportesGuias = $this->reporteRepo->getAll()
            ->map(fn($g) => [
                'id_reporte'        => $g->id_reporte,
                'id_guia_recorrido' => $g->id_guia_recorrido,
                'observaciones'     => $g->observaciones,
                'fecha_registro'    => optional($g->fecha_registro)->format('Y-m-d') ?? '—',
                'estado'            => $g->estado,
            ])
            ->toArray();

        return view('admin.reportes', compact(
            'user', 'compras', 'reservas', 'reportesGuias'
        ));
    }

    public function reportePDF(Request $request)
    {
        $tipo   = $request->input('tipo', 'guias');
        $inicio = $request->input('inicio', '');
        $fin    = $request->input('fin', '');

        $titulos = [
            'reservas' => 'Reporte de Reservas',
            'compras'  => 'Reporte de Compras',
            'guias'    => 'Reporte de Guías',
        ];
        $titulo = $titulos[$tipo] ?? 'Reporte';

        // Obtener datos según el tipo
        if ($tipo === 'compras') {
            $query = DB::table('compras')
                ->select(['fecha', DB::raw('COUNT(*) as total_compras'), DB::raw('SUM(monto) as total_ingresos')])
                ->where('estado', 1);
            if ($inicio) $query->whereDate('fecha', '>=', $inicio);
            if ($fin)    $query->whereDate('fecha', '<=', $fin);
            $datos = $query->groupBy('fecha')->orderBy('fecha','desc')->get()->map(fn($c) => (array)$c)->toArray();

        } elseif ($tipo === 'reservas') {
            $query = DB::table('reservas')
                ->select([DB::raw('DATE(fecha) as fecha'), DB::raw('COUNT(*) as total_reservas'), DB::raw('SUM(cupos) as total_cupos')]);
            if ($inicio) $query->whereDate('fecha', '>=', $inicio);
            if ($fin)    $query->whereDate('fecha', '<=', $fin);
            $datos = $query->groupBy(DB::raw('DATE(fecha)'))->orderBy('fecha','desc')->get()->map(fn($r) => (array)$r)->toArray();

        } else {
            $datos = $this->reporteRepo->getAll()
                ->map(fn($g) => [
                    'fecha_registro' => optional($g->fecha_registro)->format('Y-m-d') ?? '—',
                    'observaciones' => $g->observaciones,
                ])->toArray();
        }

        $html = view('admin.reportes.pdf', compact('titulo', 'inicio', 'fin', 'tipo', 'datos'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"reporte_{$tipo}.pdf\"",
        ]);
    }
}
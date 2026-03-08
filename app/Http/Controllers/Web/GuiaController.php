<?php
// app/Http/Controllers/Web/GuiaController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\GuiaRepository;
use App\Repositories\ReporteRepository;
use Illuminate\Http\Request;
use App\Models\GuiaRecorrido;

class GuiaController extends Controller
{
    private GuiaRepository $guiaRepo;
    private ReporteRepository $reporteRepo;

    public function __construct()
    {
        $this->guiaRepo    = new GuiaRepository();
        $this->reporteRepo = new ReporteRepository();
    }

    public function dashboard(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        $recorridosAsignados = $this->guiaRepo->getRecorridosAsignados($user->id_usuario);
        return view('guias.dashboard', compact('user', 'recorridosAsignados'));
    }

    public function horarios(Request $request)
    {
        $user        = $request->attributes->get('auth_user');
        $semanaOffset = (int)$request->input('semana', 0);
        $semanaOffset = max(0, min(1, $semanaOffset));

        $lunes = now()->startOfWeek();
        if ($semanaOffset === 1) $lunes->addDays(7);

        $inicioSemana = $lunes->copy()->addDay()->format('Y-m-d');
        $finSemana    = $lunes->copy()->addDays(6)->format('Y-m-d');

        $datosGuia           = $this->guiaRepo->getHorariosGuia($user->id_usuario);
        $recorridosPorSemana = $this->guiaRepo->getRecorridosPorSemana(
            $user->id_usuario, $inicioSemana, $finSemana
        );

        return view('guias.horarios', compact('user', 'datosGuia', 'recorridosPorSemana', 'semanaOffset'));
    }

    public function detalleRecorrido(Request $request)
    {
        $user         = $request->attributes->get('auth_user');
        $id_recorrido = (int)$request->input('id', 0);

        if ($id_recorrido <= 0) abort(400);

        $recorrido = $this->guiaRepo->getDetalleRecorrido($id_recorrido, $user->id_usuario);
        if (!$recorrido) abort(404);

        $areas = $this->guiaRepo->getAreasPorRecorrido($id_recorrido);

        return view('guias.detalle_recorrido', compact('user', 'recorrido', 'areas'));
    }

    public function showReportForm(Request $request)
{
    $user              = $request->attributes->get('auth_user');
    $id_guia_recorrido = (int)$request->input('id_gr', 0);

    if ($id_guia_recorrido <= 0) {
        return redirect('/guias/dashboard');
    }

    $asignacion = GuiaRecorrido::with('recorrido')
        ->where('id_guia_recorrido', $id_guia_recorrido)
        ->first();

    if (!$asignacion) abort(404);

    $detalleRecorrido = [
        'id_guia_recorrido'   => $asignacion->id_guia_recorrido,
        'recorrido_nombre'    => $asignacion->recorrido->nombre ?? 'Sin nombre',
        'fecha_asignacion'    => $asignacion->fecha_asignacion,
        'tickets_confirmados' => $asignacion->tickets_confirmados ?? 0,
        'tipo'                => $asignacion->recorrido->tipo ?? 'N/A',
    ];

    return view('guias.reporte_crear', compact('user', 'detalleRecorrido'));
}

    public function processReport(Request $request)
    {
        $user               = $request->attributes->get('auth_user');
        $id_guia_recorrido  = (int)$request->input('id_guia_recorrido', 0);
        $descripcion        = trim($request->input('descripcion', ''));
        $observaciones      = trim($request->input('observaciones', ''));

        if (!$id_guia_recorrido || !$descripcion) {
            return redirect('/guias/reportes-crear')->with('error', 'Completa todos los campos obligatorios.');
        }

        if ($this->reporteRepo->existeReporte($id_guia_recorrido)) {
            return redirect('/guias/reportes-crear')->with('error', 'Ya existe un reporte para esta asignación.');
        }

        $this->reporteRepo->save($id_guia_recorrido, $descripcion . "\n" . $observaciones);

        return redirect('/guias/reportes-historial')->with('success', 'Reporte guardado correctamente.');
    }

    public function showReportHistory(Request $request)
    {
        $user     = $request->attributes->get('auth_user');
        $reportes = $this->reporteRepo->getReportesPorGuia($user->id_usuario);

        return view('guias.reporte_historial', compact('user', 'reportes'));
    }
}
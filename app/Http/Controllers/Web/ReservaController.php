<?php
// app/Http/Controllers/Web/ReservaController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\ReservaRepository;
use App\Services\ReservaService;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    private ReservaService $reservaService;
    private ReservaRepository $reservaRepo;

    public function __construct()
    {
        $this->reservaService = new ReservaService();
        $this->reservaRepo    = new ReservaRepository();
    }

    public function showForm(Request $request)
    {
        $user       = $request->attributes->get('auth_user');
        $recorridos = $this->reservaService->obtenerRecorridosGuiados();

        return view('reservas.form', compact('user', 'recorridos'));
    }

    public function processForm(Request $request)
    {
        $user    = $request->attributes->get('auth_user');
        $cliente = $user->cliente;
        if (!$cliente) abort(403, 'No tienes perfil de cliente.');

        $resultado = $this->reservaService->procesarReserva(
            (int)$request->input('recorrido_id', 0),
            trim($request->input('institucion', '')),
            trim($request->input('tipo_institucion', '')),
            trim($request->input('contacto_nombre', '')),
            trim($request->input('contacto_telefono', '')),
            trim($request->input('contacto_email', '')),
            (int)$request->input('numero_personas', 0),
            $request->input('fecha', ''),
            $request->input('hora', ''),
            trim($request->input('observaciones', '')),
            $cliente->id_cliente
        );

        if (!$resultado) {
            $recorridos = $this->reservaService->obtenerRecorridosGuiados();
            return view('reservas.form', [
                'user'       => $user,
                'recorridos' => $recorridos,
                'error'      => 'Error al procesar la reserva. Revisa los datos.',
            ]);
        }

        session(['ultima_reserva' => $resultado]);

        return redirect('/reservas/pagoqr');
    }

    public function showPagoQR(Request $request)
    {
        $user    = $request->attributes->get('auth_user');
        $reserva = session('ultima_reserva');

        if (!$reserva) return redirect('/reservar');

        return view('reservas.pagoqr', compact('user', 'reserva'));
    }

    public function showHistorial(Request $request)
    {
        $user    = $request->attributes->get('auth_user');
        $cliente = $user->cliente;

        $reservas = $cliente
            ? $this->reservaRepo->findByCliente($cliente->id_cliente)
            : collect();

        return view('reservas.historial', compact('user', 'reservas'));
    }

    public function downloadPdf(Request $request)
    {
        $reserva = session('ultima_reserva');
        if (!$reserva) return redirect('/reservas/historial');

        // Cargar el modelo Reserva desde BD para el PDF
        $reservaModel = \App\Models\Reserva::with('recorrido')->find($reserva['reserva_id']);
        if (!$reservaModel) return redirect('/reservas/historial');

        $pdf = $this->reservaService->generarComprobanteReserva($reservaModel, $reserva);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="comprobante_reserva.pdf"',
        ]);
    }
}
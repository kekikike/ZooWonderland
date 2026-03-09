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

    private function mapearRecorridos(): array
    {
        return $this->reservaService->obtenerRecorridosGuiados()
            ->map(fn($r) => [
                'id'     => $r->id_recorrido,
                'nombre' => $r->nombre,
                'precio' => $r->precio,
            ])->values()->toArray();
    }

    public function showForm(Request $request)
    {
        $usuario           = $request->attributes->get('auth_user');
        $recorridosGuiados = $this->mapearRecorridos();
        $fechaMin          = now()->addDays(3)->format('Y-m-d');

        return view('reservas.crear', [
            'usuario'           => $usuario,
            'recorridosGuiados' => $recorridosGuiados,
            'fechaMin'          => $fechaMin,
            'form'              => [],
        ]);
    }

    public function processForm(Request $request)
    {
        $usuario = $request->attributes->get('auth_user');
        $cliente = $usuario->cliente;
        if (!$cliente) abort(403, 'No tienes perfil de cliente.');

        $form = $request->only([
            'recorrido_id', 'institucion', 'tipo_institucion',
            'contacto_nombre', 'contacto_telefono', 'contacto_email',
            'numero_personas', 'fecha', 'hora', 'observaciones',
        ]);

        $resultado = $this->reservaService->procesarReserva(
            (int) $request->input('recorrido_id', 0),
            trim((string) $request->input('institucion', '')),
            trim((string) $request->input('tipo_institucion', '')),
            trim((string) $request->input('contacto_nombre', '')),
            trim((string) $request->input('contacto_telefono', '')),
            trim((string) $request->input('contacto_email', '')),
            (int) $request->input('numero_personas', 0),
            (string) $request->input('fecha', ''),
            (string) $request->input('hora', ''),
            trim((string) $request->input('observaciones', '')),
            $cliente->id_cliente
        );

        if (!$resultado) {
            return view('reservas.crear', [
                'usuario'           => $usuario,
                'recorridosGuiados' => $this->mapearRecorridos(),
                'fechaMin'          => now()->addDays(3)->format('Y-m-d'),
                'form'              => $form,
            ])->with('error', 'Error al procesar la reserva. Revisa los datos.');
        }

        session(['ultima_reserva' => $resultado]);

        return redirect('/reservas/pagoqr');
    }

    public function showPagoQR(Request $request)
    {
        $usuario = $request->attributes->get('auth_user');
        $result  = session('ultima_reserva');

        if (!$result) return redirect('/reservar');

        // Cargar el modelo Reserva desde BD
        $reserva = \App\Models\Reserva::with('recorrido')->find($result['reserva_id'] ?? null);
        if (!$reserva) return redirect('/reservar');

        $datos = $result; // contiene codigo, monto_total, etc.

        return view('reservas.pago', compact('usuario', 'reserva', 'datos'));
    }

    public function showHistorial(Request $request)
    {
        $usuario = $request->attributes->get('auth_user');
        $cliente = $usuario->cliente;

        $reservas = $cliente
            ? $this->reservaRepo->findByCliente($cliente->id_cliente)
            : collect();

        $todasLasReservas = $reservas->map(function ($r) {
            return [
                'reserva' => $r,
                'extras'  => [
                    'monto_total' => $r->cupos * ($r->recorrido->precio ?? 0),
                ],
            ];
        });

        return view('reservas.historial', compact('usuario', 'todasLasReservas'));
    }

    public function downloadPdf(Request $request)
    {
        $result = session('ultima_reserva');
        if (!$result) return redirect('/reservas/historial');

        $reservaModel = \App\Models\Reserva::with('recorrido')->find($result['reserva_id'] ?? null);
        if (!$reservaModel) return redirect('/reservas/historial');

        $pdf = $this->reservaService->generarComprobanteReserva($reservaModel, $result);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="comprobante_reserva.pdf"',
        ]);
    }
}
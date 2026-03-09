<?php
// app/Http/Controllers/Web/CompraController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\CompraRepository;
use App\Repositories\RecorridoRepository;
use App\Services\AuthService;
use App\Services\CompraService;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    private CompraService $compraService;
    private CompraRepository $compraRepo;
    private RecorridoRepository $recorridoRepo;
    private AuthService $auth;

    public function __construct()
    {
        $this->compraService  = new CompraService();
        $this->compraRepo     = new CompraRepository();
        $this->recorridoRepo  = new RecorridoRepository();
        $this->auth           = new AuthService();
    }

    public function crear(Request $request)
    {
        $usuario    = $request->attributes->get('auth_user');
        $recorridos = $this->recorridoRepo->findAll();

        return view('compras.crear', [
            'usuario'     => $usuario,
            'recorridos'  => $recorridos,
            'form'        => ['recorrido_id' => '', 'cantidad' => 1, 'fecha' => '', 'hora' => ''],
            'errores'     => [],
            'mensaje'     => null,
            'disponibles' => null,
        ]);
    }

    public function procesar(Request $request)
    {
        $usuario     = $request->attributes->get('auth_user');
        $recorridos  = $this->recorridoRepo->findAll();
        $recorridoId = (int) $request->input('recorrido_id', 0);
        $cantidad    = (int) $request->input('cantidad', 1);
        $fecha       = $request->input('fecha', '');
        $hora        = $request->input('hora', '');

        $form = [
            'recorrido_id' => $recorridoId,
            'cantidad'     => $cantidad,
            'fecha'        => $fecha,
            'hora'         => $hora,
        ];

        $validacion = $this->compraService->validarCompra($recorridoId, $cantidad, $fecha, $hora);
        if (!$validacion['valido']) {
            return view('compras.crear', [
                'usuario'     => $usuario,
                'recorridos'  => $recorridos,
                'form'        => $form,
                'errores'     => $validacion['errores'],
                'mensaje'     => null,
                'disponibles' => null,
            ]);
        }

        $cliente = $usuario->cliente;
        if (!$cliente) {
            abort(403, 'No tienes perfil de cliente.');
        }

        $resultado = $this->compraService->procesarCompra(
            $cliente->id_cliente, $recorridoId, $cantidad, $fecha, $hora
        );

        if (!$resultado) {
            return view('compras.crear', [
                'usuario'     => $usuario,
                'recorridos'  => $recorridos,
                'form'        => $form,
                'errores'     => [],
                'mensaje'     => 'Error al procesar la compra. Intenta nuevamente.',
                'disponibles' => null,
            ]);
        }

        session(['ultima_compra' => $resultado]);

        return redirect('/compras/pagoqr');
    }

    public function showPagoQR(Request $request)
    {
        $usuario = $request->attributes->get('auth_user');
        $compra  = session('ultima_compra');

        if (!$compra) {
            return redirect('/compras/crear');
        }

        $datos = [
            'monto_total' => $compra['monto_total'] ?? $compra['monto'] ?? 0,
            'recorrido'   => $compra['recorrido']   ?? '',
            'fecha'       => $compra['fecha']        ?? '',
            'hora'        => $compra['hora']         ?? '',
        ];

        return view('compras.pago', compact('usuario', 'datos'));
    }

    public function historial(Request $request)
    {
        $usuario = $request->attributes->get('auth_user');
        $cliente = $usuario->cliente;

        $compras = $cliente
            ? $this->compraRepo->findByCliente($cliente->id_cliente)
            : collect();

        return view('compras.historial', compact('usuario', 'compras'));
    }

    public function downloadPdf(Request $request)
    {
        $compra = session('ultima_compra');

        if (!$compra) {
            return redirect('/compras/historial');
        }

        $pdf = $this->compraService->generarComprobante($compra);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="comprobante_compra.pdf"',
        ]);
    }
}
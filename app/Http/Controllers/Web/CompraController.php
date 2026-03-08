<?php
// app/Http/Controllers/Web/CompraController.php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\CompraRepository;
use App\Services\AuthService;
use App\Services\CompraService;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    private CompraService $compraService;
    private CompraRepository $compraRepo;
    private AuthService $auth;

    public function __construct()
    {
        $this->compraService = new CompraService();
        $this->compraRepo    = new CompraRepository();
        $this->auth          = new AuthService();
    }

    public function crear(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        return view('compras.crear', compact('user'));
    }

    public function procesar(Request $request)
    {
        $user        = $request->attributes->get('auth_user');
        $recorridoId = (int)$request->input('recorrido_id', 0);
        $cantidad    = (int)$request->input('cantidad', 1);
        $fecha       = $request->input('fecha', '');
        $hora        = $request->input('hora', '');

        // Validar primero
        $validacion = $this->compraService->validarCompra($recorridoId, $cantidad, $fecha, $hora);
        if (!$validacion['valido']) {
            return view('compras.crear', [
                'user'    => $user,
                'errores' => $validacion['errores'],
            ]);
        }

        // Obtener id_cliente desde el usuario
        $cliente = $user->cliente;
        if (!$cliente) abort(403, 'No tienes perfil de cliente.');

        $resultado = $this->compraService->procesarCompra(
            $cliente->id_cliente, $recorridoId, $cantidad, $fecha, $hora
        );

        if (!$resultado) {
            return view('compras.crear', ['user' => $user, 'error' => 'Error al procesar la compra.']);
        }

        // Guardar en sesión para la vista de pago QR
        session(['ultima_compra' => $resultado]);

        return redirect('/compras/pagoqr');
    }

    public function showPagoQR(Request $request)
    {
        $user   = $request->attributes->get('auth_user');
        $compra = session('ultima_compra');

        if (!$compra) return redirect('/compras/crear');

        return view('compras.pagoqr', compact('user', 'compra'));
    }

    public function historial(Request $request)
    {
        $user    = $request->attributes->get('auth_user');
        $cliente = $user->cliente;

        $compras = $cliente
            ? $this->compraRepo->findByCliente($cliente->id_cliente)
            : collect();

        return view('compras.historial', compact('user', 'compras'));
    }

    public function downloadPdf(Request $request)
    {
        $compra = session('ultima_compra');
        if (!$compra) return redirect('/compras/historial');

        $pdf = $this->compraService->generarComprobante($compra);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="comprobante_compra.pdf"',
        ]);
    }
}
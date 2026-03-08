<?php
// app/Http/Controllers/Api/TicketController.php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TicketRepository;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private TicketRepository $ticketRepo;

    public function __construct()
    {
        $this->ticketRepo = new TicketRepository();
    }

    // GET /api/tickets?compra_id=X
    public function index(Request $request)
    {
        $compraId = (int)$request->input('compra_id', 0);

        if (!$compraId) {
            return response()->json(['success' => false, 'message' => 'compra_id es obligatorio.'], 400);
        }

        $tickets = $this->ticketRepo->findByCompra($compraId);
        return response()->json(['success' => true, 'data' => $tickets]);
    }

    // GET /api/tickets/{id}
    public function show(Request $request, int $id)
    {
        $ticket = $this->ticketRepo->findById($id);

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => "Ticket #{$id} no encontrado."], 404);
        }

        return response()->json(['success' => true, 'data' => $ticket]);
    }

    // GET /api/tickets/qr/{codigo}
    public function showByQR(Request $request, string $codigo)
    {
        $ticket = $this->ticketRepo->findByCodigoQR($codigo);

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Código QR no encontrado.'], 404);
        }

        return response()->json(['success' => true, 'data' => $ticket]);
    }

    // DELETE /api/tickets/{id}
    public function destroy(Request $request, int $id)
    {
        $ticket = $this->ticketRepo->findById($id);
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => "Ticket #{$id} no encontrado."], 404);
        }

        $ok = $this->ticketRepo->delete($id);
        if (!$ok) {
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el ticket.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Ticket eliminado.']);
    }
}
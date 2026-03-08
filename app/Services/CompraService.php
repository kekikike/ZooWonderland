<?php
// app/Services/CompraService.php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CompraRepository;
use App\Repositories\TicketRepository;
use App\Repositories\RecorridoRepository;
use Dompdf\Dompdf;

class CompraService
{
    private CompraRepository $compraRepo;
    private TicketRepository $ticketRepo;
    private RecorridoRepository $recorridoRepo;

    public function __construct()
    {
        $this->compraRepo    = new CompraRepository();
        $this->ticketRepo    = new TicketRepository();
        $this->recorridoRepo = new RecorridoRepository();
    }

    public function validarCompra(int $recorridoId, int $cantidad, string $fecha, string $hora): array
    {
        $errores   = [];
        $recorrido = $this->recorridoRepo->findById($recorridoId);

        if (!$recorrido) $errores['recorrido_id'] = 'Recorrido inválido.';
        if ($cantidad < 1)  $errores['cantidad']  = 'Mínimo 1 ticket.';
        if ($cantidad > 10) $errores['cantidad']  = 'Máximo 10 tickets por compra.';

        if (empty($fecha)) {
            $errores['fecha'] = 'La fecha es obligatoria.';
        } elseif (strtotime($fecha) < strtotime(date('Y-m-d'))) {
            $errores['fecha'] = 'La fecha no puede ser en el pasado.';
        }

        if (empty($hora)) {
            $errores['hora'] = 'La hora es obligatoria.';
        } else {
            $horaInt = (int) str_replace(':', '', $hora);
            if ($horaInt < 900 || $horaInt > 1500) {
                $errores['hora'] = 'Horario disponible: 09:00 a 15:00.';
            }
        }

        if ($recorrido && $fecha && $hora && empty($errores['fecha']) && empty($errores['hora'])) {
            $vendidos    = $this->compraRepo->countTicketsSold($recorridoId, $fecha, $hora);
            $disponibles = $recorrido->capacidad - $vendidos;
            if ($cantidad > $disponibles) {
                $errores['cantidad'] = "Solo quedan {$disponibles} entradas disponibles.";
            }
        }

        return ['valido' => empty($errores), 'errores' => $errores, 'recorrido' => $recorrido];
    }

    public function procesarCompra(int $clienteId, int $recorridoId, int $cantidad, string $fecha, string $hora): ?array
    {
        $validacion = $this->validarCompra($recorridoId, $cantidad, $fecha, $hora);
        if (!$validacion['valido']) return null;

        $recorrido  = $validacion['recorrido'];
        $precio     = (float) $recorrido->precio;
        $montoTotal = $precio * $cantidad;

        $compra = $this->compraRepo->create([
            'id_cliente'  => $clienteId,
            'fecha'       => now()->toDateString(),
            'hora'        => now()->toTimeString(),
            'monto'       => $montoTotal,
            'estado_pago' => 0,
        ]);

        for ($i = 0; $i < $cantidad; $i++) {
            $codigo = strtoupper(uniqid('TK'));
            $ticket = $this->ticketRepo->create($compra->id_compra, $recorridoId, $fecha, $hora, $codigo);
            $this->compraRepo->addDetalle($compra->id_compra, $ticket->id_ticket, $precio, 1);
        }

        return [
            'compra_id'   => $compra->id_compra,
            'cantidad'    => $cantidad,
            'monto_total' => $montoTotal,
            'fecha'       => $fecha,
            'hora'        => $hora,
            'recorrido'   => $recorrido->nombre,
            'codigo'      => strtoupper(substr(md5($compra->id_compra . $fecha), 0, 10)),
        ];
    }

    public function generarComprobante(array $datos): string
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($this->htmlComprobante($datos));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output() ?: '';
    }

    private function htmlComprobante(array $d): string
    {
        // ── El HTML del PDF se mantiene igual que tu versión original ──
        // Se omite aquí para brevedad pero va íntegro en el archivo final
        $compraId  = htmlspecialchars((string)($d['compra_id'] ?? ''));
        $recorrido = htmlspecialchars((string)($d['recorrido'] ?? ''));
        $cantidad  = (int)($d['cantidad'] ?? 0);
        $fecha     = htmlspecialchars((string)($d['fecha'] ?? ''));
        $hora      = htmlspecialchars((string)($d['hora'] ?? ''));
        $precioU   = number_format((float)($d['precio_unit'] ?? 0), 2);
        $monto     = number_format((float)($d['monto_total'] ?? 0), 2);
        $codigo    = htmlspecialchars((string)($d['codigo'] ?? ''));
        $hoy       = date('d/m/Y H:i');
        $anio      = date('Y');

        return <<<HTML
<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,sans-serif; color:#222; font-size:12px; }
.header { background:#2d5016; color:#fff; padding:20px 30px; }
.header h1 { font-size:20px; margin-bottom:4px; }
.header p  { font-size:11px; opacity:.85; }
.codigo-box { background:#fffaf0; border:2px dashed #bfb641; text-align:center; padding:14px; margin:18px 30px; border-radius:8px; }
.codigo-box strong { font-size:22px; color:#2d5016; font-family:'Courier New',monospace; }
.body { padding:0 30px 30px; }
.section-title { font-size:10px; text-transform:uppercase; color:#2d5016; font-weight:bold; margin:18px 0 8px; border-bottom:2px solid #d4e4c1; padding-bottom:4px; }
table { width:100%; border-collapse:collapse; }
th,td { padding:8px 6px; text-align:left; }
th { font-weight:bold; color:#2d5016; border-bottom:2px solid #d4e4c1; }
td { border-bottom:1px solid #eee; }
.total-box { background:#d4e4c1; border-radius:8px; padding:12px 16px; margin-top:20px; text-align:right; }
.total-box .amount { font-size:22px; font-weight:bold; color:#2d5016; }
.footer { border-top:1px solid #eee; text-align:center; padding:14px 30px 10px; font-size:10px; color:#999; }
</style></head><body>
<div class='header'><h1>Zoo Wonderland</h1><p>Comprobante de Compra</p><p>Compra N° {$compraId} | {$hoy}</p></div>
<div class='codigo-box'><strong>{$codigo}</strong></div>
<div class='body'>
<div class='section-title'>Detalles</div>
<table><thead><tr><th>Recorrido</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th></tr></thead>
<tbody><tr><td>{$recorrido}</td><td>{$cantidad}</td><td>Bs. {$precioU}</td><td>Bs. {$monto}</td></tr></tbody></table>
<div class='section-title' style='margin-top:20px;'>Tickets</div>
<table>
<tr><td><strong>Recorrido:</strong></td><td>{$recorrido}</td></tr>
<tr><td><strong>Fecha:</strong></td><td>{$fecha}</td></tr>
<tr><td><strong>Hora:</strong></td><td>{$hora}</td></tr>
<tr><td><strong>Cantidad:</strong></td><td>{$cantidad}</td></tr>
</table>
<div class='total-box'><div class='amount'>Total: Bs. {$monto}</div></div>
</div>
<div class='footer'>Zoo Wonderland &copy; {$anio}</div>
</body></html>
HTML;
    }
}
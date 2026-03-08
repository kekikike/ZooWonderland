<?php
// app/Services/ReservaService.php
declare(strict_types=1);

namespace App\Services;

use App\Models\Reserva;
use App\Repositories\ReservaRepository;
use App\Repositories\RecorridoRepository;
use Dompdf\Dompdf;

class ReservaService
{
    private ReservaRepository $reservaRepo;
    private RecorridoRepository $recorridoRepo;

    public function __construct()
    {
        $this->reservaRepo   = new ReservaRepository();
        $this->recorridoRepo = new RecorridoRepository();
    }

    public function obtenerRecorridosGuiados(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->recorridoRepo->findByTipo('Guiado');
    }

    public function validarReserva(
        int $recorridoId, string $institucion, string $tipoInstitucion,
        string $contactoNombre, string $contactoTelefono, string $contactoEmail,
        int $numPersonas, string $fecha, string $hora, string $observaciones
    ): array {
        $errores   = [];
        $recorrido = $this->recorridoRepo->findById($recorridoId);

        if (!$recorrido) $errores['recorrido_id'] = 'Recorrido inválido.';

        $institucion = trim($institucion);
        if (empty($institucion))           $errores['institucion'] = 'La institución es obligatoria.';
        elseif (strlen($institucion) < 3)  $errores['institucion'] = 'Mínimo 3 caracteres.';
        elseif (strlen($institucion) > 150)$errores['institucion'] = 'Máximo 150 caracteres.';

        $tiposValidos = ['colegio','universidad','empresa','ong','gobierno','otro'];
        if (!in_array($tipoInstitucion, $tiposValidos, true)) {
            $errores['tipo_institucion'] = 'Tipo de institución inválido.';
        }

        if (empty(trim($contactoNombre)))  $errores['contacto_nombre']    = 'El nombre del contacto es obligatorio.';
        if (!preg_match('/^[67]\d{7}$/', trim($contactoTelefono))) {
            $errores['contacto_telefono'] = 'Celular boliviano inválido (ej: 71234567).';
        }
        if (!filter_var(trim($contactoEmail), FILTER_VALIDATE_EMAIL)) {
            $errores['contacto_email'] = 'Correo inválido.';
        }

        if ($numPersonas < 10)  $errores['numero_personas'] = 'Mínimo 10 personas.';
        if ($numPersonas > 200) $errores['numero_personas'] = 'Máximo 200 personas.';

        if ($recorrido && $numPersonas > $recorrido->capacidad) {
            $errores['numero_personas'] = "Capacidad máxima: {$recorrido->capacidad} personas.";
        }

        if ($recorrido && $fecha && $hora) {
            $ocupados    = $this->reservaRepo->countByRecorridoFechaHora($recorridoId, $fecha, $hora);
            $disponibles = $recorrido->capacidad - $ocupados;
            if ($numPersonas > $disponibles) {
                $errores['numero_personas'] = "Solo quedan {$disponibles} cupos para ese horario.";
            }
        }

        if (empty($fecha)) {
            $errores['fecha'] = 'La fecha es obligatoria.';
        } elseif (strtotime($fecha) < strtotime('+3 days')) {
            $errores['fecha'] = 'Reservas con al menos 3 días de anticipación.';
        }

        if (empty($hora)) {
            $errores['hora'] = 'La hora es obligatoria.';
        } else {
            $horaInt = (int) str_replace(':', '', $hora);
            if ($horaInt < 900 || $horaInt > 1500) $errores['hora'] = 'Horario: 09:00 a 15:00.';
        }

        return [
            'valido'       => empty($errores),
            'errores'      => $errores,
            'recorridoData'=> $recorrido,
        ];
    }

    public function procesarReserva(
        int $recorridoId, string $institucion, string $tipoInstitucion,
        string $contactoNombre, string $contactoTelefono, string $contactoEmail,
        int $numPersonas, string $fecha, string $hora, string $observaciones,
        int $clienteId
    ): ?array {
        $v = $this->validarReserva(
            $recorridoId, $institucion, $tipoInstitucion,
            $contactoNombre, $contactoTelefono, $contactoEmail,
            $numPersonas, $fecha, $hora, $observaciones
        );

        if (!$v['valido'] || !$clienteId) return null;

        $recorrido  = $v['recorridoData'];
        $montoTotal = $recorrido->precio * $numPersonas;

        $reserva = $this->reservaRepo->create([
            'fecha'        => $fecha,
            'hora'         => $hora,
            'cupos'        => $numPersonas,
            'institucion'  => $institucion,
            'comentario'   => $observaciones,
            'estado_pago'  => 0,
            'estado'       => 1,
            'id_cliente'   => $clienteId,
            'id_recorrido' => $recorridoId,
        ]);

        $codigo = strtoupper(substr(md5($reserva->id_reserva . $institucion . $fecha), 0, 10));

        return [
            'reserva_id'        => $reserva->id_reserva,
            'recorrido'         => $recorrido->nombre,
            'monto_total'       => $montoTotal,
            'tipo_institucion'  => $tipoInstitucion,
            'contacto_nombre'   => $contactoNombre,
            'contacto_telefono' => $contactoTelefono,
            'contacto_email'    => $contactoEmail,
            'observaciones'     => $observaciones,
            'codigo'            => $codigo,
            'qr_pago'           => 'img/qr.jpeg',
        ];
    }

    public function generarComprobanteReserva(Reserva $reserva, array $extras): string
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($this->htmlComprobante($reserva, $extras));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output() ?: '';
    }

    private function htmlComprobante(Reserva $reserva, array $d): string
    {
        $tiposLabel = [
            'colegio'=>'Colegio / Unidad Educativa','universidad'=>'Universidad / Instituto',
            'empresa'=>'Empresa','ong'=>'ONG / Fundación',
            'gobierno'=>'Entidad Gubernamental','otro'=>'Otro',
        ];
        $tipoLabel = $tiposLabel[$d['tipo_institucion']] ?? $d['tipo_institucion'];
        $fechaFmt  = date('d/m/Y', strtotime($reserva->fecha));
        $monto     = number_format((float)$d['monto_total'], 2);
        $hoy       = date('d/m/Y H:i');
        $anio      = date('Y');
        $id        = $reserva->id_reserva;
        $inst      = htmlspecialchars($reserva->institucion);
        $cupos     = $reserva->cupos;
        $hora      = $reserva->hora;
        $contacto  = htmlspecialchars($d['contacto_nombre'] ?? '');
        $codigo    = htmlspecialchars($d['codigo'] ?? '');

        // Nombre del recorrido via relación Eloquent
        $recNombre = htmlspecialchars($reserva->recorrido?->nombre ?? '');

        return <<<HTML
<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,sans-serif; color:#222; font-size:12px; }
.header { background:#a3712a; color:#fff; padding:20px 30px; }
.header h1 { font-size:20px; margin-bottom:4px; }
.codigo-box { background:#fffaf0; border:2px dashed #bfb641; text-align:center; padding:14px; margin:18px 30px; border-radius:8px; }
.codigo-box strong { font-size:22px; color:#a3712a; font-family:'Courier New',monospace; }
.body { padding:0 30px 30px; }
.section-title { font-size:10px; text-transform:uppercase; color:#977c66; font-weight:bold; margin:18px 0 8px; border-bottom:2px solid #ffe2a0; padding-bottom:4px; }
table { width:100%; border-collapse:collapse; }
td { padding:5px 6px; vertical-align:top; }
td:first-child { color:#666; width:42%; }
td:last-child { font-weight:bold; }
.total-box { background:#ffe2a0; border-radius:8px; padding:12px 16px; margin-top:20px; text-align:right; }
.total-box .amount { font-size:22px; font-weight:bold; color:#a3712a; }
.footer { border-top:1px solid #eee; text-align:center; padding:14px; font-size:10px; color:#999; }
</style></head><body>
<div class='header'><h1>Zoo Wonderland</h1><p>Comprobante de Reserva Grupal</p><p>Reserva N° {$id} | {$hoy}</p></div>
<div class='codigo-box'><strong>{$codigo}</strong></div>
<div class='body'>
<div class='section-title'>Detalles de la Reserva</div>
<table>
<tr><td>Institución:</td><td>{$inst} ({$tipoLabel})</td></tr>
<tr><td>N° Personas:</td><td>{$cupos}</td></tr>
<tr><td>Recorrido:</td><td>{$recNombre}</td></tr>
<tr><td>Fecha:</td><td>{$fechaFmt}</td></tr>
<tr><td>Hora:</td><td>{$hora}</td></tr>
<tr><td>Responsable:</td><td>{$contacto}</td></tr>
</table>
<div class='total-box'><div class='amount'>Total: Bs. {$monto}</div></div>
</div>
<div class='footer'>Zoo Wonderland &copy; {$anio}</div>
</body></html>
HTML;
    }
}
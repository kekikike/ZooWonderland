<?php
// app/Models/Ticket.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    protected $table      = 'tickets';
    protected $primaryKey = 'id_ticket';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'hora', 'fecha', 'codigo_qr', 'id_compra', 'id_recorrido', 'estado',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'id_compra', 'id_compra');
    }

    public function recorrido(): BelongsTo
    {
        return $this->belongsTo(Recorrido::class, 'id_recorrido', 'id_recorrido');
    }

    public function detalle(): HasOne
    {
        return $this->hasOne(DetalleCompra::class, 'id_ticket', 'id_ticket');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaActivo(): bool
    {
        return (int)$this->estado === 1;
    }

    public function getInfo(): string
    {
        return "Ticket #{$this->id_ticket} - {$this->fecha} {$this->hora}";
    }
}
<?php
// app/Models/Reserva.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    protected $table      = 'reservas';
    protected $primaryKey = 'id_reserva';

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'fecha', 'hora', 'cupos', 'institucion', 'comentario',
        'estado_pago', 'estado', 'id_cliente', 'id_recorrido',
    ];

    protected $casts = [
        'estado_pago' => 'boolean',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function recorrido(): BelongsTo
    {
        return $this->belongsTo(Recorrido::class, 'id_recorrido', 'id_recorrido');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaPagada(): bool
    {
        return (bool)$this->estado_pago;
    }

    public function estaActiva(): bool
    {
        return (int)$this->estado === 1;
    }

    public function mostrarResumen(): string
    {
        return "Reserva #{$this->id_reserva} - {$this->institucion} ({$this->fecha})";
    }
}
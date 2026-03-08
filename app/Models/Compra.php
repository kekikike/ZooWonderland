<?php
// app/Models/Compra.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Compra extends Model
{
    protected $table      = 'compras';
    protected $primaryKey = 'id_compra';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'fecha', 'hora', 'monto', 'estado_pago', 'id_cliente', 'estado',
    ];

    protected $casts = [
        'monto'      => 'float',
        'estado_pago' => 'boolean',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    // detalle_compra SI necesita modelo propio (tiene precio_unitario, cantidad)
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra', 'id_compra');
    }

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(
            Ticket::class,
            'detalle_compra',
            'id_compra',
            'id_ticket'
        )->withPivot('precio_unitario', 'cantidad');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaPagada(): bool
    {
        return (bool)$this->estado_pago;
    }

    public function mostrarResumen(): string
    {
        return "Compra #{$this->id_compra} - Total: Bs. {$this->monto}";
    }
}
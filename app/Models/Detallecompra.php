<?php
// app/Models/DetalleCompra.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCompra extends Model
{
    protected $table      = 'detalle_compra';
    protected $primaryKey = 'id_detalle';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_compra', 'id_ticket', 'precio_unitario', 'cantidad', 'estado',
    ];

    protected $casts = [
        'precio_unitario' => 'float',
    ];

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'id_compra', 'id_compra');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'id_ticket', 'id_ticket');
    }
}
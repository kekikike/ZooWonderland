<?php
// app/Models/Cliente.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table      = 'clientes';
    protected $primaryKey = 'id_cliente';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'nit', 'tipo_cuenta', 'id_usuario', 'estado',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'id_cliente', 'id_cliente');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_cliente', 'id_cliente');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaActivo(): bool
    {
        return (int)$this->estado === 1;
    }
}
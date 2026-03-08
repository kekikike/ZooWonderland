<?php
// app/Models/ApiToken.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiToken extends Model
{
    protected $table      = 'api_token';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_usuario', 'token', 'expire_at', 'last_used', 'ip_origen', 'activo',
    ];

    protected $hidden = ['token'];

    protected $casts = [
        'activo'    => 'boolean',
        'expire_at' => 'datetime',
        'last_used' => 'datetime',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaActivo(): bool
    {
        return $this->activo && $this->expire_at->isFuture();
    }

    public function marcarUso(): void
    {
        $this->last_used = now();
        $this->save();
    }

    public function revocar(): void
    {
        $this->activo = false;
        $this->save();
    }
}
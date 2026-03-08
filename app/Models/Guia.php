<?php
// app/Models/Guia.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guia extends Model
{
    protected $table      = 'guias';
    protected $primaryKey = 'id_guia';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'horarios', 'dias_trabajo', 'id_usuario', 'estado',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // guia_recorrido SÍ necesita modelo (tiene fecha_asignacion, hora_inicio)
    public function guiaRecorridos(): HasMany
    {
        return $this->hasMany(GuiaRecorrido::class, 'id_guia', 'id_guia');
    }

    public function recorridos(): BelongsToMany
    {
        return $this->belongsToMany(
            Recorrido::class,
            'guia_recorrido',
            'id_guia',
            'id_recorrido'
        )->withPivot('fecha_asignacion', 'hora_inicio', 'estado');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaActivo(): bool
    {
        return (int)$this->estado === 1;
    }
}
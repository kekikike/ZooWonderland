<?php
// app/Models/Area.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Area extends Model
{
    protected $table      = 'areas';
    protected $primaryKey = 'id_area';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'nombre', 'restringida', 'descripcion', 'estado',
    ];

    protected $casts = [
        'restringida' => 'boolean',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function animales(): HasMany
    {
        return $this->hasMany(Animal::class, 'id_area', 'id_area');
    }

    public function recorridos(): BelongsToMany
    {
        return $this->belongsToMany(
            Recorrido::class,
            'recorrido_area',
            'id_area',
            'id_recorrido'
        )->withPivot('estado')->wherePivot('estado', 1);
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaActiva(): bool
    {
        return (int)$this->estado === 1;
    }

    public function esRestringida(): bool
    {
        return (bool)$this->restringida;
    }
}
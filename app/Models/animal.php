<?php
// app/Models/Animal.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Animal extends Model
{
    protected $table      = 'animales';
    protected $primaryKey = 'id_animal';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'especie', 'nombre_comun', 'habitat',
        'descripcion', 'foto', 'estado', 'id_area',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaActivo(): bool
    {
        return $this->estado === 'Activo';
    }
}
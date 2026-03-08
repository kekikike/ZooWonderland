<?php
// app/Models/Recorrido.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recorrido extends Model
{
    protected $table      = 'recorridos';
    protected $primaryKey = 'id_recorrido';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'nombre', 'tipo', 'precio', 'duracion', 'capacidad', 'estado',
    ];

    protected $casts = [
        'precio' => 'float',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(
            Area::class,
            'recorrido_area',
            'id_recorrido',
            'id_area'
        )->withPivot('estado')->wherePivot('estado', 1);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'id_recorrido', 'id_recorrido');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_recorrido', 'id_recorrido');
    }

    public function guias(): BelongsToMany
    {
        return $this->belongsToMany(
            Guia::class,
            'guia_recorrido',
            'id_recorrido',
            'id_guia'
        )->withPivot('fecha_asignacion', 'hora_inicio', 'estado');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaActivo(): bool
    {
        return (int)$this->estado === 1;
    }

    public function esGuiado(): bool
    {
        return $this->tipo === 'Guiado';
    }
}
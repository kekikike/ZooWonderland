<?php
// app/Models/Evento.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evento extends Model
{
    protected $table      = 'eventos';
    protected $primaryKey = 'id_evento';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_actualizacion';

    protected $fillable = [
        'nombre_evento', 'descripcion', 'fecha_inicio', 'fecha_fin',
        'tiene_costo', 'precio', 'encargado_id', 'lugar',
        'limite_participantes', 'estado',
    ];

    protected $casts = [
        'tiene_costo' => 'boolean',
        'precio'      => 'float',
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function guia(): BelongsTo
    {
        return $this->belongsTo(Guia::class, 'encargado_id', 'id_guia');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(EventoActividad::class, 'evento_id', 'id_evento');
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function estaActivo(): bool
    {
        return (int)$this->estado === 1;
    }

    public function esVigente(): bool
    {
        return $this->fecha_inicio <= now() && $this->fecha_fin >= now();
    }

    public function esPasado(): bool
    {
        return $this->fecha_fin < now();
    }

    public function esPendiente(): bool
    {
        return $this->fecha_inicio > now();
    }

    public function getVigencia(): string
    {
        if ($this->esVigente())  return 'Vigente';
        if ($this->esPasado())   return 'Pasado';
        return 'Pendiente';
    }
}
<?php
// app/Models/EventoActividad.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoActividad extends Model
{
    protected $table      = 'evento_actividades';
    protected $primaryKey = 'id_actividad';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'evento_id', 'nombre_actividad', 'descripcion', 'estado',
    ];

    // ── Relaciones ───────────────────────────────────────────────
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'evento_id', 'id_evento');
    }
}
<?php
// app/Models/GuiaRecorrido.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GuiaRecorrido extends Model
{
    protected $table      = 'guia_recorrido';
    protected $primaryKey = 'id_guia_recorrido';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_guia', 'id_recorrido', 'fecha_asignacion', 'hora_inicio', 'estado',
    ];

    public function guia(): BelongsTo
    {
        return $this->belongsTo(Guia::class, 'id_guia', 'id_guia');
    }

    public function recorrido(): BelongsTo
    {
        return $this->belongsTo(Recorrido::class, 'id_recorrido', 'id_recorrido');
    }

    public function reporte(): HasOne
    {
        return $this->hasOne(Reporte::class, 'id_guia_recorrido', 'id_guia_recorrido');
    }
}
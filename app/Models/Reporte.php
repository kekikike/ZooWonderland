<?php
// app/Models/Reporte.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reporte extends Model
{
    protected $table      = 'reportes';
    protected $primaryKey = 'id_reporte';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_guia_recorrido', 'observaciones', 'fecha_reporte', 'estado',
    ];

    public function guiaRecorrido(): BelongsTo
    {
        return $this->belongsTo(GuiaRecorrido::class, 'id_guia_recorrido', 'id_guia_recorrido');
    }
}
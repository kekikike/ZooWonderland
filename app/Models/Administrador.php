<?php
// app/Models/Administrador.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Administrador extends Model
{
    protected $table      = 'administradores';
    protected $primaryKey = 'id_admin';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = ['id_usuario', 'estado'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
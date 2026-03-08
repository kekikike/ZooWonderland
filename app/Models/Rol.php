<?php
// app/Models/Rol.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    protected $table      = 'roles';
    protected $primaryKey = 'id_rol';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'nombre_rol',
        'descripcion',
        'estado',
    ];

    // Constantes de roles
    const ADMINISTRADOR = 'administrador';
    const GUIA          = 'guia';
    const CLIENTE       = 'cliente';

    // ── Relaciones ───────────────────────────────────────────────
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
    }
}
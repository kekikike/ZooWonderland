<?php
// app/Models/Usuario.php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Usuario extends Authenticatable
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';

    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;

    protected $fillable = [
        'nombre1', 'nombre2', 'apellido1', 'apellido2',
        'ci', 'correo', 'telefono', 'nombre_usuario',
        'contrasena', 'estado', 'id_rol',
    ];

    protected $hidden = ['contrasena'];

    // Eloquent usa 'password' por defecto para auth, lo mapeamos
    protected $authPasswordName = 'contrasena';

    // ── Relaciones ───────────────────────────────────────────────
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function administrador(): HasOne
    {
        return $this->hasOne(Administrador::class, 'id_usuario', 'id_usuario');
    }

    public function guia(): HasOne
    {
        return $this->hasOne(Guia::class, 'id_usuario', 'id_usuario');
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id_usuario', 'id_usuario');
    }

    // ── Helpers (igual que antes) ────────────────────────────────
    public function getNombreCompleto(): string
    {
        $partes = array_filter([
            $this->nombre1,
            $this->nombre2,
            $this->apellido1,
            $this->apellido2,
        ]);
        return implode(' ', $partes) ?: $this->nombre_usuario;
    }

    public function getNombreParaMostrar(): string
    {
        return $this->getNombreCompleto() ?: $this->nombre_usuario;
    }

    public function getNombreRol(): string
    {
        return $this->rol?->nombre_rol ?? 'cliente';
    }

    public function esCliente(): bool
    {
        return $this->getNombreRol() === Rol::CLIENTE;
    }

    public function esGuia(): bool
    {
        return $this->getNombreRol() === Rol::GUIA;
    }

    public function esAdministrador(): bool
    {
        return $this->getNombreRol() === Rol::ADMINISTRADOR;
    }

    public function estaActivo(): bool
    {
        return (int)$this->estado === 1;
    }
}
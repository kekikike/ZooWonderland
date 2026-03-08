<?php
// app/Repositories/RolRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Collection;

class RolRepository
{
    public function findAll(): Collection
    {
        return Rol::where('estado', 1)->orderBy('id_rol')->get();
    }

    public function findByNombre(string $nombre): ?Rol
    {
        return Rol::where('nombre_rol', $nombre)->first();
    }

    public function getIdRol(string $nombre): int
    {
        $rol = $this->findByNombre($nombre);
        if (!$rol) throw new \Exception("Rol '{$nombre}' no existe.");
        return $rol->id_rol;
    }
}
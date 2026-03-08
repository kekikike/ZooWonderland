<?php
// app/Repositories/UsuarioRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Recorrido;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UsuarioRepository
{
    // ── Autenticación ─────────────────────────────────────────────
    public function authenticate(string $login, string $password): ?Usuario
    {
        $usuario = Usuario::with('rol')
            ->where('nombre_usuario', $login)
            ->orWhere('correo', $login)
            ->first();

        if (!$usuario) return null;
        if (!Hash::check($password, $usuario->contrasena)) return null;

        return $usuario;
    }

    public function findById(int $id): ?Usuario
    {
        return Usuario::with('rol')->find($id);
    }

    // ── Verificaciones de rol ─────────────────────────────────────
    public function esCliente(int $idUsuario): bool
    {
        return Usuario::find($idUsuario)?->cliente()->exists() ?? false;
    }

    public function esGuia(int $idUsuario): bool
    {
        return Usuario::find($idUsuario)?->guia()->exists() ?? false;
    }

    // ── Crear usuario ─────────────────────────────────────────────
    public function create(array $data): Usuario
    {
        $idRol = Rol::where('nombre_rol', 'cliente')->value('id_rol') ?? 3;

        return Usuario::create([
            'nombre1'        => $data['nombre1'],
            'nombre2'        => $data['nombre2']        ?? null,
            'apellido1'      => $data['apellido1'],
            'apellido2'      => $data['apellido2']      ?? null,
            'ci'             => $data['ci']              ?? null,
            'correo'         => $data['correo'],
            'telefono'       => $data['telefono']        ?? null,
            'nombre_usuario' => $data['nombre_usuario'],
            'contrasena'     => Hash::make($data['password']),
            'id_rol'         => $idRol,
        ]);
    }

    // ── Listado con filtros ───────────────────────────────────────
    public function getUsuariosFiltrados(
        string $busqueda       = '',
        string $rol            = '',
        int    $idRecorrido    = 0,
        string $estado         = '',
        int    $excluirUsuario = 0
    ): Collection {
        return Usuario::with(['rol', 'guia', 'cliente'])
            ->when($excluirUsuario, fn($b) => $b->where('id_usuario', '!=', $excluirUsuario))
            ->when($busqueda, function ($b) use ($busqueda) {
                $like = '%' . $busqueda . '%';
                $b->where(function ($q) use ($like) {
                    $q->where('ci',           'like', $like)
                      ->orWhere('nombre1',    'like', $like)
                      ->orWhere('nombre2',    'like', $like)
                      ->orWhere('apellido1',  'like', $like)
                      ->orWhere('apellido2',  'like', $like)
                      ->orWhereRaw("CONCAT(nombre1, ' ', apellido1) LIKE ?", [$like]);
                });
            })
            ->when($rol, fn($b) => $b->whereHas('rol', fn($r) => $r->where('nombre_rol', $rol)))
            ->when($idRecorrido, fn($b) => $b->whereHas('guia.recorridos', fn($r) => $r->where('recorridos.id_recorrido', $idRecorrido)))
            ->when($estado !== '', fn($b) => $b->where('estado', (int) $estado))
            ->orderBy('apellido1')
            ->orderBy('nombre1')
            ->get();
    }

    public function getUsuarioPorId(int $idUsuario): ?Usuario
    {
        return Usuario::with(['rol', 'guia', 'cliente'])->find($idUsuario);
    }

    // ── Validaciones de unicidad ──────────────────────────────────
    public function correoEnUso(string $correo, int $excluirId): bool
    {
        return Usuario::where('correo', $correo)
            ->where('id_usuario', '!=', $excluirId)
            ->exists();
    }

    public function nombreUsuarioEnUso(string $nombreUsuario, int $excluirId): bool
    {
        return Usuario::where('nombre_usuario', $nombreUsuario)
            ->where('id_usuario', '!=', $excluirId)
            ->exists();
    }

    // ── Actualizar ────────────────────────────────────────────────
    public function actualizarUsuario(
        int    $idUsuario,
        string $nombre1,
        string $nombre2,
        string $apellido1,
        string $apellido2,
        int    $ci,
        string $telefono,
        string $rol,
        string $correo,
        string $nombreUsuario
    ): bool {
        if ($this->correoEnUso($correo, $idUsuario)) {
            throw new \Exception("El correo '{$correo}' ya está registrado en otra cuenta.");
        }
        if ($this->nombreUsuarioEnUso($nombreUsuario, $idUsuario)) {
            throw new \Exception("El nombre de usuario '{$nombreUsuario}' ya está en uso.");
        }

        $idRol = Rol::where('nombre_rol', $rol)->value('id_rol');
        if (!$idRol) throw new \Exception("Rol '{$rol}' no existe.");

        return (bool) Usuario::where('id_usuario', $idUsuario)->update([
            'nombre1'        => $nombre1,
            'nombre2'        => $nombre2    !== '' ? $nombre2    : null,
            'apellido1'      => $apellido1,
            'apellido2'      => $apellido2  !== '' ? $apellido2  : null,
            'ci'             => $ci,
            'telefono'       => $telefono   !== '' ? $telefono   : null,
            'id_rol'         => $idRol,
            'correo'         => $correo,
            'nombre_usuario' => $nombreUsuario,
        ]);
    }

    public function cambiarEstado(int $idUsuario, int $estado): bool
    {
        return (bool) Usuario::where('id_usuario', $idUsuario)->update(['estado' => $estado]);
    }

    // ── Helpers para vistas ───────────────────────────────────────
    public function getRecorridosParaFiltro(): Collection
    {
        return Recorrido::orderBy('nombre')->get(['id_recorrido', 'nombre']);
    }
}
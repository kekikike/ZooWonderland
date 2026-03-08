<?php
// app/Repositories/ApiTokenRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\ApiToken;

class ApiTokenRepository
{
    public function findActivo(string $token): ?ApiToken
    {
        return ApiToken::where('token', $token)
            ->where('activo', 1)
            ->where('expire_at', '>', now())
            ->first();
    }

    public function crear(int $idUsuario, string $token, string $expireAt, string $ip): ApiToken
    {
        return ApiToken::create([
            'id_usuario' => $idUsuario,
            'token'      => $token,
            'expire_at'  => $expireAt,
            'ip_origen'  => $ip,
            'activo'     => 1,
        ]);
    }

    public function revocar(string $token): bool
    {
        return (bool) ApiToken::where('token', $token)->update(['activo' => 0]);
    }

    public function revocarTodos(int $idUsuario): bool
    {
        return (bool) ApiToken::where('id_usuario', $idUsuario)->update(['activo' => 0]);
    }

    public function marcarUso(string $token): void
    {
        ApiToken::where('token', $token)->update(['last_used' => now()]);
    }
}
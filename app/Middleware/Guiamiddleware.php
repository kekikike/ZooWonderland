<?php
// app/middleware/GuiaMiddleware.php
declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\Response;

class GuiaMiddleware
{
    /**
     * Verifica que el usuario autenticado tenga rol 'guia'.
     * Admins también pueden pasar (para supervisión).
     *
     * @param  array $authUser  Datos del usuario autenticado
     * @return array            El mismo array (para encadenar)
     */
    public function handle(array $authUser): array
    {
        if (!in_array($authUser['nombre_rol'], ['guia', 'administrador'], true)) {
            Response::forbidden('Se requiere rol de guía.');
        }

        return $authUser;
    }
}
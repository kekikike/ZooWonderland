<?php
// app/middleware/AdminMiddleware.php
declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\Response;

class AdminMiddleware
{
    /**
     * Verifica que el usuario autenticado tenga rol 'administrador'.
     * Recibe el array que devuelve AuthMiddleware::handle().
     *
     * @param  array $authUser  Datos del usuario autenticado
     * @return array            El mismo array (para encadenar)
     */
    public function handle(array $authUser): array
    {
        if ($authUser['nombre_rol'] !== 'administrador') {
            Response::forbidden('Se requiere rol de administrador.');
        }

        return $authUser;
    }
}
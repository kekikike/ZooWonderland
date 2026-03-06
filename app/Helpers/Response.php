<?php
// app/helpers/Response.php
declare(strict_types=1);

namespace App\Helpers;

class Response
{
    /**
     * Envía una respuesta JSON y termina la ejecución.
     *
     * @param mixed $data    Datos a serializar
     * @param int   $status  Código HTTP
     */
    public static function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /** 200 OK con datos */
    public static function ok(mixed $data, string $message = 'OK'): void
    {
        self::json(['success' => true, 'message' => $message, 'data' => $data], 200);
    }

    /** 201 Created */
    public static function created(mixed $data, string $message = 'Creado correctamente'): void
    {
        self::json(['success' => true, 'message' => $message, 'data' => $data], 201);
    }

    /** 400 Bad Request */
    public static function badRequest(string $message = 'Solicitud inválida', array $errors = []): void
    {
        self::json(['success' => false, 'message' => $message, 'errors' => $errors], 400);
    }

    /** 401 Unauthorized */
    public static function unauthorized(string $message = 'No autenticado'): void
    {
        self::json(['success' => false, 'message' => $message], 401);
    }

    /** 403 Forbidden */
    public static function forbidden(string $message = 'Acceso denegado'): void
    {
        self::json(['success' => false, 'message' => $message], 403);
    }

    /** 404 Not Found */
    public static function notFound(string $message = 'Recurso no encontrado'): void
    {
        self::json(['success' => false, 'message' => $message], 404);
    }

    /** 409 Conflict (duplicados, unicidad) */
    public static function conflict(string $message = 'Conflicto con datos existentes'): void
    {
        self::json(['success' => false, 'message' => $message], 409);
    }

    /** 422 Unprocessable Entity (validación) */
    public static function validationError(array $errors, string $message = 'Error de validación'): void
    {
        self::json(['success' => false, 'message' => $message, 'errors' => $errors], 422);
    }

    /** 500 Internal Server Error */
    public static function serverError(string $message = 'Error interno del servidor'): void
    {
        self::json(['success' => false, 'message' => $message], 500);
    }
}
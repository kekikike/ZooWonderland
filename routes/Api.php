<?php
// routes/api.php
declare(strict_types=1);

use Core\Router;
use App\Controllers\Api\AuthController;
use App\Controllers\Api\AnimalController;
use App\Controllers\Api\UsuarioController;

$router = new Router();

// ── AUTH  —  /api/auth ───────────────────────────────────────────
$router->post('/api/auth/login',  [AuthController::class, 'login']);
$router->post('/api/auth/logout', [AuthController::class, 'logout']);
$router->get( '/api/auth/me',     [AuthController::class, 'me']);

// ── USUARIOS  —  /api/usuarios  (solo admin) ─────────────────────
$router->get(   '/usuarios',             [UsuarioController::class, 'index']);
$router->get(   '/usuarios/{id}',        [UsuarioController::class, 'show']);
$router->put(   '/usuarios/{id}',        [UsuarioController::class, 'update']);
$router->patch( '/usuarios/{id}/estado', [UsuarioController::class, 'toggleEstado']);

// ── ANIMALES  —  /api/animales ───────────────────────────────────
$router->get(   '/api/animales',      [AnimalController::class, 'index']);
$router->get(   '/api/animales/{id}', [AnimalController::class, 'show']);
$router->post(  '/api/animales',      [AnimalController::class, 'store']);
$router->put(   '/api/animales/{id}', [AnimalController::class, 'update']);
$router->delete('/api/animales/{id}', [AnimalController::class, 'destroy']);

return $router;
<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\RecorridoController;
use App\Http\Controllers\Api\EventoController;

// ── AUTH ─────────────────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth.zoo')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);
});

// ── PÚBLICAS (sin auth) ───────────────────────────────────────────
Route::get('/animales',          [AnimalController::class,   'index']);
Route::get('/animales/{id}',     [AnimalController::class,   'show']);
Route::get('/recorridos',        [RecorridoController::class,'index']);
Route::get('/recorridos/{id}',   [RecorridoController::class,'show']);
Route::get('/areas',             [AreaController::class,     'index']);
Route::get('/areas/{id}',        [AreaController::class,     'show']);
Route::get('/eventos',           [EventoController::class,   'index']);
Route::get('/eventos/{id}',      [EventoController::class,   'show']);

// ── SOLO ADMIN ────────────────────────────────────────────────────
Route::middleware(['auth.zoo', 'auth.admin'])->group(function () {

    // Usuarios
    Route::get('/usuarios',               [UsuarioController::class,   'index']);
    Route::post('/usuarios',              [UsuarioController::class,   'store']);
    Route::get('/usuarios/{id}',          [UsuarioController::class,   'show']);
    Route::put('/usuarios/{id}',          [UsuarioController::class,   'update']);
    Route::patch('/usuarios/{id}/estado', [UsuarioController::class,   'toggleEstado']);
    Route::delete('/usuarios/{id}',       [UsuarioController::class,   'destroy']);

    // Animales
    Route::post('/animales',              [AnimalController::class,    'store']);
    Route::put('/animales/{id}',          [AnimalController::class,    'update']);
    Route::delete('/animales/{id}',       [AnimalController::class,    'destroy']);

    // Áreas
    Route::post('/areas',                 [AreaController::class,      'store']);
    Route::put('/areas/{id}',             [AreaController::class,      'update']);
    Route::delete('/areas/{id}',          [AreaController::class,      'destroy']);

    // Recorridos
    Route::post('/recorridos',            [RecorridoController::class, 'store']);
    Route::put('/recorridos/{id}',        [RecorridoController::class, 'update']);
    Route::delete('/recorridos/{id}',     [RecorridoController::class, 'destroy']);

    // Eventos
    Route::post('/eventos',               [EventoController::class,    'store']);
    Route::put('/eventos/{id}',           [EventoController::class,    'update']);
    Route::delete('/eventos/{id}',        [EventoController::class,    'destroy']);
});
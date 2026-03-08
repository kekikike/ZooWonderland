<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\UsuarioController;

// ── AUTH ─────────────────────────────────────────────────────────
Route::post('/auth/login',  [AuthController::class, 'login']);
Route::middleware('auth.zoo')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);
});

// ── USUARIOS (solo admin) ─────────────────────────────────────────
Route::middleware(['auth.zoo', 'auth.admin'])->group(function () {
    Route::get('/usuarios',              [UsuarioController::class, 'index']);
    Route::post('/usuarios',             [UsuarioController::class, 'store']);
    Route::get('/usuarios/{id}',         [UsuarioController::class, 'show']);
    Route::put('/usuarios/{id}',         [UsuarioController::class, 'update']);
    Route::patch('/usuarios/{id}/estado',[UsuarioController::class, 'toggleEstado']);
    Route::delete('/usuarios/{id}',      [UsuarioController::class, 'destroy']);
});

// ── ANIMALES ──────────────────────────────────────────────────────
Route::get('/animales',      [AnimalController::class, 'index']);
Route::get('/animales/{id}', [AnimalController::class, 'show']);
Route::middleware(['auth.zoo', 'auth.admin'])->group(function () {
    Route::post('/animales',        [AnimalController::class, 'store']);
    Route::put('/animales/{id}',    [AnimalController::class, 'update']);
    Route::delete('/animales/{id}', [AnimalController::class, 'destroy']);
});
<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\GuiaController;
use App\Http\Controllers\Web\CompraController;
use App\Http\Controllers\Web\ReservaController;
use App\Http\Controllers\Web\AnimalController;

// ── PÚBLICAS ─────────────────────────────────────────────────────
Route::get('/',         [HomeController::class, 'index']);
Route::get('/login',    [AuthController::class, 'showLogin']);
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegister']);
Route::post('/registro',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
Route::get('/logout',   [AuthController::class, 'logout']); // por si hay links GET

// ── CLIENTE ───────────────────────────────────────────────────────
Route::middleware(['auth.zoo', 'auth.cliente'])->group(function () {
    Route::get('/compras/crear',    [CompraController::class, 'crear']);
    Route::post('/compras/crear',   [CompraController::class, 'procesar']);
    Route::get('/compras/pagoqr',   [CompraController::class, 'showPagoQR']);
    Route::get('/compras/pdf',      [CompraController::class, 'downloadPdf']);
    Route::get('/compras/historial',[CompraController::class, 'historial']);

    Route::get('/reservar',          [ReservaController::class, 'showForm']);
    Route::post('/reservar',         [ReservaController::class, 'processForm']);
    Route::get('/reservas/pagoqr',   [ReservaController::class, 'showPagoQR']);
    Route::get('/reservas/historial',[ReservaController::class, 'showHistorial']);
    Route::get('/reservas/pdf',      [ReservaController::class, 'downloadPdf']);
});

// ── GUÍA ──────────────────────────────────────────────────────────
Route::middleware(['auth.zoo', 'auth.guia'])->group(function () {
    Route::get('/guias/dashboard',          [GuiaController::class, 'dashboard']);
    Route::get('/guias/horarios',           [GuiaController::class, 'horarios']);
    Route::get('/guias/detalle-recorrido',  [GuiaController::class, 'detalleRecorrido']);
    Route::get('/guias/reportes-crear',     [GuiaController::class, 'showReportForm']);
    Route::post('/guias/reportes-guardar',  [GuiaController::class, 'processReport']);
    Route::get('/guias/reportes-historial', [GuiaController::class, 'showReportHistory']);
});

// ── ADMIN ─────────────────────────────────────────────────────────
Route::middleware(['auth.zoo', 'auth.admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // Recorridos
    Route::get('/recorridos',           [AdminController::class, 'recorridos']);
    Route::get('/recorridos/crear',     [AdminController::class, 'crearRecorrido']);
    Route::post('/recorridos/guardar',  [AdminController::class, 'guardarRecorrido']);
    Route::get('/recorridos/editar',    [AdminController::class, 'editarRecorrido']);
    Route::post('/recorridos/actualizar',[AdminController::class, 'actualizarRecorrido']);
    Route::post('/recorridos/eliminar', [AdminController::class, 'eliminarRecorrido']);

    // Animales
    Route::get('/animales',             [AdminController::class, 'animales']);
    Route::get('/animales/crear',       [AdminController::class, 'crearAnimal']);
    Route::post('/animales/guardar',    [AdminController::class, 'guardarAnimal']);
    Route::get('/animales/editar',      [AdminController::class, 'editarAnimal']);
    Route::post('/animales/actualizar', [AdminController::class, 'actualizarAnimal']);
    Route::post('/animales/eliminar',   [AdminController::class, 'eliminarAnimal']);

    // Usuarios
    Route::get('/usuarios',                  [AdminController::class, 'usuarios']);
    Route::get('/usuario-editar',            [AdminController::class, 'editarUsuarioForm']);
    Route::post('/usuario-editar-post',      [AdminController::class, 'editarUsuarioPost']);
    Route::post('/usuario-toggle',           [AdminController::class, 'toggleEstado']);
    Route::post('/usuario-crear',            [AdminController::class, 'crearUsuario']);

    // Asignaciones
    Route::get('/asignaciones',              [AdminController::class, 'asignaciones']);
    Route::get('/asignaciones/crear',        [AdminController::class, 'crearAsignacion']);
    Route::post('/asignaciones/guardar',     [AdminController::class, 'guardarAsignacion']);
    Route::post('/asignaciones/eliminar',    [AdminController::class, 'eliminarAsignacion']);

    // Eventos
    Route::get('/eventos',                   [AdminController::class, 'eventos']);
    Route::get('/eventos/crear',             [AdminController::class, 'eventoForm']);
    Route::get('/eventos/editar',            [AdminController::class, 'eventoForm']);
    Route::post('/eventos/guardar',          [AdminController::class, 'saveEvento']);
    Route::post('/eventos/eliminar',         [AdminController::class, 'deleteEvento']);
    Route::get('/eventos/detalle',           [AdminController::class, 'detalleEvento']);

    // Reportes
    Route::get('/reportes',                  [AdminController::class, 'reportes']);
    Route::get('/reportePDF',                [AdminController::class, 'reportePDF']);
});
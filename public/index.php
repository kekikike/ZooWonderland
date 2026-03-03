<?php
// public/index.php
declare(strict_types=1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

define('ROOT_PATH',   dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('CORE_PATH',   ROOT_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

require_once ROOT_PATH . '/vendor/autoload.php';

require_once CORE_PATH . '/Session.php';
require_once CORE_PATH . '/Database.php';

\Core\Session::iniciarSesionSegura();
\Core\Database::getInstance();

// Variables globales para las vistas
$isLoggedIn = false;
$user = null;

$r = $_GET['r'] ?? '/';
$r = trim($r, '/');
$r = $r === '' ? '/' : $r;

switch ($r) {
    case '/':
    case '':
        $controller = new \App\Controllers\HomeController();
        $controller->index();
        break;

    case 'login':
        $authCtrl = new \App\Controllers\AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authCtrl->login();
        } else {
            $authCtrl->showLogin();
        }
        break;

    case 'registro':
        $authCtrl = new \App\Controllers\AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authCtrl->register();
        } else {
            $authCtrl->showRegister();
        }
        break;

    case 'logout':
        $authCtrl = new \App\Controllers\AuthController();
        $authCtrl->logout();
        break;

    case 'compras/crear':
        $compraCtrl = new \App\Controllers\CompraController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $compraCtrl->procesar();
        } else {
            $compraCtrl->crear();
        }
        break;

    case 'compras/pagoqr':
        $compraCtrl = new \App\Controllers\CompraController();
        $compraCtrl->showPagoQR();
        break;

    case 'compras/pdf':
        $compraCtrl = new \App\Controllers\CompraController();
        $compraCtrl->downloadPdf();
        break;

    case 'historial':
        $userCtrl = new \App\Controllers\UsuarioController();
        $userCtrl->historial();
        break;

    case 'compras/historial':
        header('Location: index.php?r=historial');
        exit;
        break;

    // ── RESERVAS ────────────────────────────────────────────────
    case 'reservar':
        $reservaCtrl = new \App\Controllers\ReservaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservaCtrl->processForm();
        } else {
            $reservaCtrl->showForm();
        }
        break;

    case 'reservas/pagoqr':
        $reservaCtrl = new \App\Controllers\ReservaController();
        $reservaCtrl->showPagoQR();
        break;

    case 'reservas/historial':
        header('Location: index.php?r=historial');
        exit;
        break;

    case 'reservas/pdf':
        $reservaCtrl = new \App\Controllers\ReservaController();
        $reservaCtrl->downloadPdf();
        break;

    // ── GUÍA: lista de recorridos asignados ──────────────────────
    case 'guias/dashboard':
        $guiaCtrl = new \App\Controllers\GuiaController();
        $guiaCtrl->dashboard();
        break;

    case 'guias/reportes-crear':
        $guiaCtrl = new \App\Controllers\GuiaController();
        $guiaCtrl->showReportForm();
        break;

    case 'guias/reportes-guardar':
        $guiaCtrl = new \App\Controllers\GuiaController();
        $guiaCtrl->processReport();
        break;

    case 'guias/reportes-historial':
        $guiaCtrl = new \App\Controllers\GuiaController();
        $guiaCtrl->showReportHistory();
        break;

    // ── GUÍA: horarios semanales ──────────────────────────────────
    case 'guias/horarios':
        $isLoggedIn = \App\Services\AuthService::check();
        $user       = \App\Services\AuthService::user();

        if (!$isLoggedIn || !$user || !$user->esGuia()) {
            header('Location: index.php?r=login');
            exit;
        }

        // Semana seleccionada: 0 = esta semana, 1 = siguiente
        $semanaOffset = (int)($_GET['semana'] ?? 0);
        $semanaOffset = max(0, min(1, $semanaOffset));

        // Calcular lunes de la semana seleccionada
        $hoyTemp   = new \DateTime();
        $lunesTemp = clone $hoyTemp;
        $lunesTemp->modify('Monday this week');
        if ($semanaOffset === 1) {
            $lunesTemp->modify('+7 days');
        }

        // Mar → Dom de esa semana
        $inicioSemana = (clone $lunesTemp)->modify('+1 day')->format('Y-m-d');
        $finSemana    = (clone $lunesTemp)->modify('+6 days')->format('Y-m-d');

        $guiaRepo            = new \App\Repositories\GuiaRepository();
        $datosGuia           = $guiaRepo->getHorariosGuia($user->id_usuario);
        $recorridosPorSemana = $guiaRepo->getRecorridosPorSemana(
            $user->id_usuario,
            $inicioSemana,
            $finSemana
        );

        require_once APP_PATH . '/Views/guias/horarios.php';
        break;

    // ── GUÍA: detalle de un recorrido + áreas ────────────────────
    case 'guias/detalle-recorrido':
        $isLoggedIn = \App\Services\AuthService::check();
        $user       = \App\Services\AuthService::user();

        if (!$isLoggedIn || !$user || !$user->esGuia()) {
            header('Location: index.php?r=login');
            exit;
        }

        $id_recorrido = (int)($_GET['id'] ?? 0);
        if ($id_recorrido === 0) {
            header('Location: index.php?r=guias/dashboard');
            exit;
        }

        $guiaRepo  = new \App\Repositories\GuiaRepository();
        $recorrido = $guiaRepo->getDetalleRecorrido($id_recorrido, $user->id_usuario);

        if (!$recorrido) {
            header('Location: index.php?r=guias/dashboard');
            exit;
        }

        $areas = $guiaRepo->getAreasPorRecorrido($id_recorrido);

        require_once APP_PATH . '/Views/guias/detalle_recorrido.php';
        break;

    // ── ADMIN: panel de administración de recorridos ──────────────
    case 'admin/dashboard':
        $adminCtrl = new \App\Controllers\AdminController();
        $adminCtrl->dashboard();
        break;

    case 'admin/recorridos':
        $adminCtrl = new \App\Controllers\AdminController();
        $adminCtrl->recorridos();
        break;

    // ── ADMIN: animales (CRUD) ──────────────────────────────────
    case 'admin/animales':
        $animCtrl = new \App\Controllers\AnimalController();
        $animCtrl->index();
        break;

    case 'admin/animales/crear':
        $animCtrl = new \App\Controllers\AnimalController();
        $animCtrl->crear();
        break;

    case 'admin/animales/guardar':
        $animCtrl = new \App\Controllers\AnimalController();
        $animCtrl->guardar();
        break;

    case 'admin/animales/editar':
        $animCtrl = new \App\Controllers\AnimalController();
        $animCtrl->editar();
        break;

    case 'admin/animales/actualizar':
        $animCtrl = new \App\Controllers\AnimalController();
        $animCtrl->actualizar();
        break;

    case 'admin/animales/eliminar':
        $animCtrl = new \App\Controllers\AnimalController();
        $animCtrl->eliminar();
        break;

    case 'admin/areas':
        // TODO: Implementar gestión de áreas
        header('Location: index.php?r=admin/dashboard');
        exit;
        break;

    case 'admin/reservas':
        // TODO: Implementar vista de reservas
        header('Location: index.php?r=admin/dashboard');
        exit;
        break;

    case 'admin/usuarios':
        // TODO: Implementar gestión de usuarios
        header('Location: index.php?r=admin/dashboard');
        exit;
        break;

    case 'admin/reportes':
        // TODO: Implementar reportes
        header('Location: index.php?r=admin/dashboard');
        exit;
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Ruta no encontrada: $r</h1>";
        exit;
}
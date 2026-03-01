<?php
// public/index.php
declare(strict_types=1);
use App\Services\AuthService;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    case 'guias/dashboard':
    if (!AuthService::check() || !($user = AuthService::user()) || !$user->esGuia()) {
        header('Location: index.php?r=login');
        exit;
    }

    $guiaRepo = new \App\Repositories\GuiaRepository();
    $recorridosAsignados = $guiaRepo->getRecorridosAsignados($user->id_usuario);

    require_once APP_PATH . '/Views/guias/dashboard.php';
    break;
case 'guias/horarios':
    // Cargar siempre el estado de sesión
    $isLoggedIn = \App\Services\AuthService::check();
    $user = \App\Services\AuthService::user();

    if (!$isLoggedIn || !$user || !$user->esGuia()) {
        header('Location: index.php?r=login');
        exit;
    }

    $guiaRepo = new \App\Repositories\GuiaRepository();
    $datosGuia = $guiaRepo->getHorariosGuia($user->id_usuario);

    require_once APP_PATH . '/Views/guias/horarios.php';
    break;
    default:
        http_response_code(404);
        echo "<h1>404 - Ruta no encontrada: $r</h1>";
        exit;
}
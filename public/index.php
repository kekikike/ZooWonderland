<?php
// public/index.php
declare(strict_types=1);

// Constantes
define('ROOT_PATH',   dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('CORE_PATH',   ROOT_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Cargar Composer
require_once ROOT_PATH . '/vendor/autoload.php';

// Sesión segura (solo una vez)
require_once CORE_PATH . '/Session.php';
require_once CORE_PATH . '/Database.php';
\Core\Session::iniciarSesionSegura();

// Conexión BD
\Core\Database::getInstance();

// ... (código anterior: constantes, autoload, sesión segura, Database)

// Obtener la ruta solicitada
$r = $_GET['r'] ?? '/';
$r = trim($r, '/');
$r = $r === '' ? '/' : $r;

// Rutas
switch ($r) {
    case '/':
    case '':
        $controller = new \App\Controllers\HomeController();
        $controller->index();
        break;

    case 'login':
        $authCtrl = new \App\Controllers\AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authCtrl->login();           // procesa el formulario
        } else {
            $authCtrl->showLogin();       // muestra el formulario
        }
        break;

    case 'logout':
        $authCtrl = new \App\Controllers\AuthController();
        $authCtrl->logout();
        break;
    case 'registro':
    $authCtrl = new \App\Controllers\AuthController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authCtrl->register();
    } else {
        $authCtrl->showRegister();
    }
    break;

    default:
        http_response_code(404);
        echo "<h1>404 - Ruta no encontrada: $r</h1>";
        echo "<p>Usa index.php?r=login para el login, por ejemplo.</p>";
        exit;
}
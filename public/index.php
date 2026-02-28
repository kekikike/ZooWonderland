<?php
// public/index.php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('CORE_PATH',   ROOT_PATH . '/core');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

require_once ROOT_PATH . '/vendor/autoload.php';

session_start();

// Cargar rutas
$routes = require ROOT_PATH . '/routes/web.php';

// Obtener URI y método
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Encontrar ruta
$handler = null;
foreach ($routes as $route => $action) {
    if ($route === $uri || $route === $uri . '/') {
        $handler = $action;
        break;
    }
}

if (!$handler) {
    http_response_code(404);
    echo "404 - Página no encontrada";
    exit;
}

// Resolver controlador@metodo
[$controllerClass, $method] = explode('@', $handler);
$controller = new $controllerClass();

if ($method === 'index' && $uri === '/') {
    // Vista principal (puedes renderizar directamente o usar un View renderer simple)
    $isLoggedIn = (new \App\Services\AuthService())->check();
    $user = $isLoggedIn ? (new \App\Services\AuthService())->user() : null;

    // Aquí cargas tu vista index antigua, pero adaptada
    // Por simplicidad, asumimos que tienes una vista en Views/home.php o similar
    require_once APP_PATH . '/Views/home.php';  // crea esta vista con el HTML + banner + recorridos
} else {
    $controller->$method();
}
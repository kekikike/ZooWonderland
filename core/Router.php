<?php
// core/Router.php
declare(strict_types=1);

namespace Core;

class Router
{
    private array $routes = [];

    // ── Registro de rutas ────────────────────────────────────────

    public function get(string $path, callable|array $handler): void
    {
        $this->routes[] = ['GET', $path, $handler];
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes[] = ['POST', $path, $handler];
    }

    public function put(string $path, callable|array $handler): void
    {
        $this->routes[] = ['PUT', $path, $handler];
    }

    public function patch(string $path, callable|array $handler): void
    {
        $this->routes[] = ['PATCH', $path, $handler];
    }

    public function delete(string $path, callable|array $handler): void
    {
        $this->routes[] = ['DELETE', $path, $handler];
    }

    // ── Despacho ─────────────────────────────────────────────────

    public function dispatch(string $method, string $uri): void
    {
        // Quitar query string y normalizar
        $uri = strtok($uri, '?');
        $uri = '/' . trim($uri, '/');

        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            if ($routeMethod !== strtoupper($method)) continue;

            $pattern = $this->buildPattern($routePath);

            if (preg_match($pattern, $uri, $matches)) {
                // Parámetros de ruta
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $this->call($handler, $params);
                return;
            }
        }

        // 404
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => "Ruta no encontrada: {$method} {$uri}",
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Convierte /api/animales/{id} en regex con grupos nombrados */
    private function buildPattern(string $path): string
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /** Instancia el controlador y llama el método, pasando params */
    private function call(callable|array $handler, array $params): void
    {
        if (is_callable($handler)) {
            $handler($params);
            return;
        }

        [$class, $method] = $handler;
        $controller = new $class();
        $controller->$method($params);
    }
}
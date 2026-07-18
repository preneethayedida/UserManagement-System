<?php
/**
 * MVC URL Router
 */

class Router {
    private array $routes = [];

    // Register a route
    public function add(string $method, string $route, string $action, array $middlewares = []): void {
        // Convert route wildcard like {id} into a named regex capture group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $route);
        // Anchor pattern
        $pattern = '#^' . trim($pattern, '/') . '$#';
        
        $this->routes[] = [
            'method'      => strtoupper($method),
            'pattern'     => $pattern,
            'action'      => $action,
            'middlewares' => $middlewares
        ];
    }

    // Register GET route
    public function get(string $route, string $action, array $middlewares = []): void {
        $this->add('GET', $route, $action, $middlewares);
    }

    // Register POST route
    public function post(string $route, string $action, array $middlewares = []): void {
        $this->add('POST', $route, $action, $middlewares);
    }

    // Dispatch the current request
    public function dispatch(string $url, string $method): void {
        // Clean URL path and method
        $url = trim(parse_url($url, PHP_URL_PATH), '/');
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $url, $matches)) {
                // Extract named matches only
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Run associated middlewares
                foreach ($route['middlewares'] as $middlewareClass) {
                    if (class_exists($middlewareClass)) {
                        $middleware = new $middlewareClass();
                        $middleware->handle($params); // Triggers exception/redirect if checks fail
                    } else {
                        throw new Exception("Middleware class [$middlewareClass] not found.");
                    }
                }

                // Dispatch to controller
                $parts = explode('@', $route['action']);
                if (count($parts) === 2) {
                    $controllerName = $parts[0];
                    $actionName = $parts[1];

                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $actionName)) {
                            $controller->$actionName($params);
                            return;
                        }
                    }
                }
                throw new Exception("Action [{$route['action']}] could not be resolved.");
            }
        }

        // Route Not Found (404)
        http_response_code(404);
        
        // Log 404 access attempt
        error_log("404 Not Found: Method=$method | URL=" . ($url ?: '/'));
        
        // Render 404 page
        $errorFile = ROOT_PATH . 'views' . DS . 'errors' . DS . '404.php';
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo "<h1>404 Not Found</h1><p>The page you are looking for does not exist.</p>";
        }
        exit();
    }
}

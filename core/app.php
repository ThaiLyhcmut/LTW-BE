<?php
class Router
{
    private $routes = [];
    private $controllerInstances = [];

    public function add($method, $uri, $controller, $action)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch()
    {
        error_log("Router dispatch called");
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                $controllerName = $route['controller'];
                if (!class_exists($controllerName)) {
                    error_log("Controller class $controllerName not found");
                    http_response_code(500);
                    echo json_encode(['error' => "Controller class $controllerName not found"]);
                    return;
                }

                if (!isset($this->controllerInstances[$controllerName])) {
                    $this->controllerInstances[$controllerName] = new $controllerName();
                    error_log("Created new instance of $controllerName for action {$route['action']}");
                } else {
                    error_log("Reusing instance of $controllerName for action {$route['action']}");
                }

                $controller = $this->controllerInstances[$controllerName];
                $action = $route['action'];

                if (method_exists($controller, $action)) {
                    $params = array_slice($matches, 1);
                    return $controller->$action(...$params);
                } else {
                    error_log("Method $action not found in $controllerName");
                    http_response_code(404);
                    echo json_encode(['error' => "Method $action not found in $controllerName"]);
                    return;
                }
            }
        }

        error_log("No route found for $requestMethod $requestUri");
        http_response_code(404);
        echo json_encode(['error' => "Route not found"]);
    }
}
?>
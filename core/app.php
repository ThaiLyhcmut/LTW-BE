<?php

class Router
{
  private $routes = [];

  // Đăng ký route
  public function add($method, $uri, $controller, $action)
  {
    $this->routes[] = [
      'method' => strtoupper($method),
      'uri' => $uri,
      'controller' => $controller,
      'action' => $action
    ];
  }

  // Xử lý request
  public function dispatch()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($this->routes as $route) {
      // Convert route pattern to regex pattern
      $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
      $pattern = str_replace('/', '\/', $pattern);
      $pattern = '/^' . $pattern . '$/';

      if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
        $controller = new $route['controller']();
        $action = $route['action'];

        if (method_exists($controller, $action)) {
          // Extract parameters from URL
          $params = array_slice($matches, 1);
          return $controller->$action(...$params);
        } else {
          http_response_code(404);
          echo "Method not found!";
          return;
        }
      }
    }

    http_response_code(404);
    echo "Route not found!";
  }
}

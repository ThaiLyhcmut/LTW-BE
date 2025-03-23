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
      if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {
        $controller = new $route['controller'](); // Tạo instance của Controller
        $action = $route['action'];

        if (method_exists($controller, $action)) {
          return $controller->$action(); // Gọi action trong controller
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

<?php

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get($uri, $action)
    {
        $this->routes['GET'][trim($uri, '/')] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][trim($uri, '/')] = $action;
    }

    public function direct($uri, $requestMethod)
    {
        $uri = trim($uri, '/');
    
        if (array_key_exists($uri, $this->routes[$requestMethod])) {
            $action = explode('@', $this->routes[$requestMethod][$uri]);
            return $this->callAction($action[0], $action[1]);
        }
    
        http_response_code(404);
        echo "404 - Ruta nu a fost găsită.";
        exit;
    }

    protected function callAction($controller, $method)
    {
        // Verifică dacă controller-ul conține subdirectoare
        if (strpos($controller, '/') !== false) {
            $controllerPath = APP_ROOT . '/app/controllers/' . $controller . '.php';
            $controllerName = basename($controller);
        } else {
            $controllerPath = APP_ROOT . '/app/controllers/' . $controller . '.php';
            $controllerName = $controller;
        }

        if (!file_exists($controllerPath)) {
            echo "Controllerul $controller nu a fost găsit.";
            exit;
        }

        require_once $controllerPath;
        $controllerInstance = new $controllerName;

        if (!method_exists($controllerInstance, $method)) {
            echo "Metoda $method nu există în controllerul $controllerName.";
            exit;
        }

        return $controllerInstance->$method();
    }
}

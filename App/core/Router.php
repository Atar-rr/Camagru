<?php

namespace App\core;

class Router
{
    const VIEW_ERROR = '/error/404.phtml';
    const PATH_CONTROLLER = 'App\controllers\\';

    private $routes;
    private $params;
    private $view;

    public function __construct()
    {
        $this->routes = require 'App/config/routes.php';
        $this->view = new View();
    }

    protected function getUri()
    {
        return trim($_SERVER['REQUEST_URI'], '/');
    }

    private function getPath($uri)
    {
        return parse_url($uri, PHP_URL_PATH);
    }

    private function getQuery($uri)
    {
        return parse_url($uri, PHP_URL_QUERY);
    }


    private function match()
    {
        $uri = $this->getUri();
        $path = $this->getPath($uri) ?? '';

        foreach ($this->routes as $route => $params) {
            if (preg_match("~^$route$~", $path)) {
                //тут можно добавить определение глагола http
                $this->params = $params;

                return true;
            }
        }

        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $path = self::PATH_CONTROLLER . ucfirst($this->params['controller']) . 'Controller';

            if (class_exists($path)) {
                $query = $this->getQuery($this->getUri());
                $controller = new $path($this->view, $this->params, $query);
                $action = $this->params['action'] . 'Action';
                if (method_exists($controller, $action)) {
                    $controller->$action();
                } else {
                    $this->view->error(self::VIEW_ERROR, 404);
                }
            } else {
                $this->view->error(self::VIEW_ERROR, 404);
            }
        } else {
            $this->view->error(self::VIEW_ERROR, 404);
        }
    }
}

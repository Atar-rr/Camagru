<?php

namespace App\core;

class Router
{
    private $routes;
    private $params;

    public function __construct()
    {
        $this->routes = require 'App/config/routes.php';
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
                $this->params = $params; // обдумать как убрать от сюда эту строку, чтобы функция была предикатом
                return true;
            }
        }
        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $path = 'App\controllers\\' . ucfirst($this->params['controller']) . 'Controller';

            if (class_exists($path)) {
                $controller = new $path();
                $action = $this->params['action'] . 'Action';

                if (method_exists($controller, $action)) {// 404 ?
                    $query = $this->getQuery($this->getUri());
                    if (isset($query)) {
                        $controller->$action($query);
                    } else {
                        $controller->$action();
                    }
                } else {
                    debug('Error 404');
                    //debug(1);
                }
            }
        } else {
            debug('Error 404');
        }
    }
}

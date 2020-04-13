<?php

namespace App\core;

use App\controllers\UserController;

class Router
{
	private $routes;
	private $params;

	public function __construct()
	{
		$this->routes = require 'app/config/routes.php';
	}

	private function match()
	{
		$uri = trim($_SERVER['REQUEST_URI'], '/');

		foreach ($this->routes as $route => $params) {
			if (preg_match("~^$route$~", $uri)) {
				$this->params = $params; // обдумать как убрать от сюда эту строку, чтобы функция была предикатом
				return true;
			}
		}
		return false;
	}

	public function run()
	{
		if ($this->match()) {
			$pathController = ROOT . '/app/controllers/'
				. ucfirst($this->params['controller'])
				. 'Controller.php';
			if (file_exists($pathController)) { // 404 ?
				include_once $pathController;
				$action = $this->params['action'] . 'Action';
				$controller = new UserController();
				if(method_exists($controller, $action)) // 404 ?
					$controller->$action();
			}
		}
		else
			debug('Error 404');
	}
}
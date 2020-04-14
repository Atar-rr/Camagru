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
			$pathController = ROOT . '/app/controllers/'
				. ucfirst($this->params['controller'])
				. 'Controller.php';
			if (file_exists($pathController)) { // 404 ?
				include_once $pathController;
				$action = $this->params['action'] . 'Action';
				$controller = new UserController();
				if(method_exists($controller, $action)) {// 404 ?
					$query = $this->getQuery($this->getUri());
					if (isset($query)) {
						$controller->$action($query);
					} else {
						$controller->$action();
					}
				}
				else
					debug('Error 404');
			}
		}
		else
			debug('Error 404');
	}
}
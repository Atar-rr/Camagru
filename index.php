<?php

include_once 'app/dev/dev.php'; //удалить дебаг

use App\core\Router;

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT', dirname(__FILE__));

spl_autoload_register(function ($class) {
	$path = lcfirst(str_replace('\\', '/', $class) . '.php');
	if (file_exists($path))
		require $path;
});

$router = new Router();

session_start();

$router->run();
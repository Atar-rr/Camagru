<?php

namespace App\controllers;

use App\model\User;


class UserController
{
	public function loginAction()
	{
		if (isset($_POST['submit']))
		{
			debug($_POST);

			//редирект через смену хедера, возможно так же можно установить код 302
		}

		$pathView = ROOT . '/app/view/user/login.phtml';
		require_once $pathView;
	}

	public function registerAction()
	{
		if (isset($_POST['submit'])) {
			$result = User::register();
		}
		$pathView = ROOT . '/app/view/user/register.phtml';
		require_once $pathView;
	}

	public function activationAction($param = '')
	{
		$pathView = ROOT . '/app/view/user/activation.phtml';
		$result = NULL;

		if (!empty($param)) {
			parse_str($param, $output);
			$result = User::activation($output['token']);
		}
		require_once $pathView;
			//header("HTTP/1.1 404 Not Found");
	}
}
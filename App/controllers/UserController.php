<?php

namespace App\controllers;

use App\model\User;
use http\Header;

class UserController
{
	public function loginAction()
	{
		if (isset($_POST['submit'])) {
			$result = User::login();
			if (!count($result['error'])) {
				header('Location: /user/cabinet');
			}
		}

		$pathView = ROOT . '/App/view/user/login.phtml';
		require_once $pathView;
	}

	public function registerAction()
	{
		$flag = 0; // как убрать?

		if (isset($_POST['submit'])) {
			$result = User::register();
			if (!count($result['error'])) {
				$flag = 1;
			}
		}
		$pathView = ROOT . '/App/view/user/register.phtml';
		require_once $pathView;
	}

	public function activationAction($param = '')
	{
		$pathView = ROOT . '/App/view/user/activation.phtml';
		$result = NULL;

		if (!empty($param)) {
			parse_str($param, $output);
			$result = User::activation($output['token']);
		}
		require_once $pathView;
	}

	public function cabinetAction()
	{
		if(isset($_SESSION['id']))
			debug('cabinet');
	}

	public function logoutAction()
	{
		$_SESSION = [];
		session_destroy();
		header('Location: /');
	}
}
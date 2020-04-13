<?php

namespace App\controllers;

use App\model\User;


class UserController
{
	public function loginAction()
	{
//		debug($_SERVER['PHP_SELF']);
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
		if (isset($_POST['submit']))
		{
			User::register();
		}
		$pathView = ROOT . '/app/view/user/register.phtml';
		require_once $pathView;
	}
}
<?php

namespace App\model;

require_once ROOT . '/app/library/Validator.php';
require_once ROOT . '/app/core/Db.php';

use App\library\Validator;
use App\core\Db;

class User
{
	public static function login()
	{
	}

	public static function register()
	{
		$user = $_POST['user'];
		$validator = new Validator($user);
		$error = $validator->validate();

		$param = ['user' => $user, 'error' => $error];
		if (!count($error)) {
			$db = Db::getConnection();
			if ($db) {
				$user['password'] = hash('whirlpool', $user['password']);
				$user['token'] = md5($user['email']);

				$sql = "INSERT INTO users (login, password, email, token) VALUES (:login, :password, :email, :token)";
				$sth = $db->prepare($sql);

				$sth->bindParam(':login', $user['login']);
				$sth->bindParam(':password', $user['password']);
				$sth->bindParam(':email', $user['email']);
				$sth->bindParam(':token', $user['token']);

				$sth->execute();
				self::sendMail($user);
				return $param;
			}
		}
		return $param;
	}

	public static function activation($token)
	{
		$db = Db::getConnection();
		if ($db)
		{
			$sql = "SELECT id FROM users WHERE token=:token";
			$sth = $db->prepare($sql);
			$sth->bindParam(':token', $token);
			$sth->execute();

			$result = $sth->fetch();
			if ($result)
			{
				$activate = true;

				$sql = "UPDATE users SET status_register=:activate WHERE token=:token";
				$sth = $db->prepare($sql);

				$sth->bindParam(':token', $token);
				$sth->bindParam(':activate', $activate);

				if($sth->execute()) {
					return true;
				}
			}
		}
		return false;
	}

	private static function sendMail($user)
	{
		$token = $user['token'];

		$to = $user['email'];
		$subject = 'Подтверждение регистрации на сайте Camagru';
		$message = "Спасибо за регистрацию на сайте Camagru. Для подтверждения вашего аккаунта перейдите по ссылке <a href='http://localhost:8081/user/activation?token=$token'>Подтвердить</a>";

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "Content-Transfer-Encoding: utf-8\r\n";
		$headers .= "Reply-To: no-reply@gmail.com\r\n";

		debug(mail($to, $subject, $message, $headers));
	}
}
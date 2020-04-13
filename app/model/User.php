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
		// проверить полученные данные
		$user = $_POST['user'];
		$validator = new Validator($user);
		$error = $validator->validate();
		if (!count($error)) {
			//подключение к базе
			$db = Db::getConnection();
			if ($db) {
				$user['password'] = hash('whirlpool', $user['password']);
				$user['token'] = md5($user['email']);
 				// подготовить запрос
				$sql = "INSERT INTO users (login, password, email) VALUES (:login, :password, :email)";
				$sth = $db->prepare($sql);
				$sth->bindParam(':login', $user['login']);
				$sth->bindParam(':password', $user['password']);
				$sth->bindParam(':email', $user['email']);
				//вставить в базу
				$sth->execute();
				self::sendMail($user);
				return ; // ??
				// отправить письмо с регистрацией?
			}
		}
		debug($error);
		//вернуть массив ошибок, с кодом 422
	}

	private function sendMail($user)
	{
		$to = $user['email'];
		$subject = 'Подтверждение регистрации на сайте Camagru';
		$message = "<pre>Спасибо за регистрацию на сайте Camagru. Для подтверждения вашего аккаунта перейдите по ссылке</pre> <a>localhost:8081/user/activation/$user[token]</a>";
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		mail($to, $subject, $message, $headers);
	}
}
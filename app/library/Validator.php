<?php

namespace App\library;

use App\core\Db;

require_once ROOT . '/app/core/Db.php';

class Validator
{
	public $user = [];

	public function __construct($data)
	{
		$this->user['login'] = $data['login']; // ?? '';
		$this->user['email'] = $data['email']; //?? '';
		$this->user['password'] = $data['password']; //?? '';
		$this->user['confirm'] = $data['confirm']; //?? '';
	}

	public function validate()
	{
		$errors = [];
		$errors ['password'] = $this->validatePassword();
		$errors ['login'] = $this->validateLogin();
		$errors ['email'] = $this->validateEmail();
		return $errors;
	}

	private function validateLogin()
	{
		$errors = '';

		if (empty($this->user['login'])) {
			$errors = "Поле не может быть пустым";
		} else if (strlen($this->user['login']) < 3 || strlen($this->user['login']) > 20) {
			$errors = "Логин должен быть от 3 до 20 символов";
		} else if (!preg_match('/^([\wа-яА-Я])+(?!.*\W)$/', $this->user['login'])) {
			$errors = "Вы ввели недопустимые символы. Логин может содержать буквы, цифры и символы нижнего подчеркивания";
		} else {
			if ($db = Db::getConnection()) {
				$sql = "SELECT login FROM users WHERE login=:login";
				$sth = $db->prepare($sql);
				$sth->bindParam(':login', $this->user['login']);
				$sth->execute();
				if ($sth->fetch(\PDO::FETCH_ASSOC))
					$errors = "Пользователь с таким именем уже существует";
			}
		}

		return $errors;
	}

	private function validatePassword()
	{
		$errors = '';

		if (empty($this->user['password'])) {
			$errors = "Поле не может быть пустым";
		} else if (empty($this->user['confirm'])) {
			$errors= "Поле не может быть пустым";
		} else if (strcmp($this->user['password'], $this->user['confirm']) !== 0) {
			$errors = "Пароли не совпадают";
		} else if(!preg_match('/^(?=.{8,255}$)((?=.*\d))(?!.*\W)(?=.*[a-z])(?=.*[A-Z]).+$/', $this->user['password'])) {
			$errors = "Вы ввели недопустимые символы. Пароль должен содержать цифры и буквы латинского алфавита в верхнем и нижнем регистре.";//"Пароль должен содержать не менее восьми знаков, включать заглавную и строчную букву и цифру. ''";
		}

		return $errors;
	}

	private function validateEmail()
	{
		$errors = '';

		if (empty($this->user['email'])) {
			$errors = "Поле не может быть пустым";
		} else if (!filter_var($this->user['email'], FILTER_VALIDATE_EMAIL)) {
			$errors = "Некорректный e-mail адрес";
		} else {
			$sql = "SELECT email FROM users WHERE email = :email";
			$db = Db::getConnection();
			$sth = $db->prepare($sql);
			$sth->bindParam(':email', $this->user['email']);
			$sth->execute();
			if($sth->fetch(\PDO::FETCH_ASSOC))
				$errors = "Пользователь с таким e-mail уже существует";
		}

		return $errors;
	}
}
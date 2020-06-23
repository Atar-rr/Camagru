<?php

namespace App\library;

use App\core\Db;

//require_once ROOT . '/App/core/Db.php';

class Validator
{
    private $user = [];
    private $errors = [];

    public function __construct($data)
    {
        $this->user['login'] = $data['login'] ?? '';
        $this->user['email'] = $data['email'] ?? '';
        $this->user['password'] = $data['password'] ?? '';
        $this->user['confirm'] = $data['confirm'] ?? '';
    }

    public function validate()
    {
        $this->validatePassword();
        $this->validateLogin();
        $this->validateEmail();
        return $this->errors;
    }

    private function validateLogin()
    {
        if (empty($this->user['login'])) {
            $this->errors['login'] = "Поле не может быть пустым";
        } elseif (strlen($this->user['login']) < 3 || strlen($this->user['login']) > 20) {
            $this->errors['login'] = "Логин должен быть от 3 до 20 символов";
        } elseif (!preg_match('/^([\wа-яА-Я])+(?!.*\W)$/', $this->user['login'])) {
            $this->errors['login'] = "Вы ввели недопустимые символы. Логин может содержать буквы,
             цифры и символы нижнего подчеркивания";
        } else {
            if ($db = Db::getConnection()) {
                $sql = "SELECT login FROM users WHERE login=:login";
                $sth = $db->prepare($sql);
                $sth->bindParam(':login', $this->user['login']);
                $sth->execute();
                if ($sth->fetch(\PDO::FETCH_ASSOC)) {
                    $this->errors['login'] = "Пользователь с таким именем уже существует";
                }
            }
        }
    }

    private function validatePassword()
    {
        if (empty($this->user['password'])) {
            $this->errors['password'] = "Поле не может быть пустым";
        } elseif (empty($this->user['confirm'])) {
            $this->errors['confirm'] = "Поле не может быть пустым";
        } elseif (strcmp($this->user['password'], $this->user['confirm']) !== 0) {
            $this->errors['confirm'] = "Пароли не совпадают";
        } elseif (!preg_match('/^(?=.{8,255}$)((?=.*\d))(?!.*\W)(?=.*[a-z])(?=.*[A-Z]).+$/', $this->user['password'])) {
            $this->errors['password'] = "Вы ввели недопустимые символы. 
            Пароль должен содержать цифры и буквы латинского алфавита в верхнем и нижнем регистре.";
            //"Пароль должен содержать не менее восьми знаков, включать заглавную и строчную букву и цифру. ''";
        }
    }

    private function validateEmail()
    {

        if (empty($this->user['email'])) {
            $this->errors['email'] = "Поле не может быть пустым";
        } elseif (!filter_var($this->user['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Некорректный e-mail адрес";
        } else {
            $sql = "SELECT email FROM users WHERE email = :email";
            $db = Db::getConnection();
            $sth = $db->prepare($sql);
            $sth->bindParam(':email', $this->user['email']);
            $sth->execute();
            if ($sth->fetch(\PDO::FETCH_ASSOC)) {
                $this->errors['email'] = "Пользователь с таким e-mail уже существует";
            }
        }
    }
}
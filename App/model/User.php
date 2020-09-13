<?php

namespace App\model;

use App\core\Model;
use App\library\Mail;
use App\library\RegValidator;
use App\library\SendMail;

/**
 * Class User
 *
 * @package App\model
 */
class User extends Model
{
    const TABLE = 'user';

    #TODO убрать валидацию в отдельный класс LoginValidate
    public function login()
    {
        #TODO что если логин не установлен
        $user = $_POST['user'];
        $error = [];

        $sth = $this->execute("SELECT password, active_status, id  FROM users WHERE login=:login", ['login' => $user['login']]);
        $result = $sth->fetch(\PDO::FETCH_ASSOC);

        if ($result) { // вынести валидацию?
            if ($result['password'] === hash('whirlpool', $user['password'])) {
                if ($result['active_status']) {
                    $_SESSION['id'] = $result['id'];
                } else {
                    $error['active_status'] = "Аккаунт не активирован.";
                }
            } else {
                $error['password'] = "Неверный пароль";
            }
        } else {
            $error['login'] = "Пользователя с таким именем не существует";
        }

        return ['user' => $user, 'error' => $error];
    }

    public function register()
    {
        $user = $_POST['user'];
        $validator = new RegValidator($user);
        $error = $validator->validate();

        $params = ['user' => $user, 'error' => $error];
        if (!count($error)) {
            $user['password'] = hash('whirlpool', $user['password']);
            $user['token'] = md5($user['email']);

            $this->execute
            (
                "INSERT INTO users (login, password, email, token) VALUES (:login, :password, :email, :token)",
                ['login' => $user['login'], 'password' => $user['password'], 'email' => $user['email'], 'token' => $user['token']]
            );

            self::sendMail($user);
        }

        return $params;
    }

    public function changePassword()
    {

    }

    public function restorePassword()
    {

    }

    public function activation($token)
    {

        $sth = $this->execute
        (
            "SELECT id FROM users WHERE token=:token",
            ['token' => $token]
        );

        $result = $sth->fetch();
        if ($result) {
            $activeStatus = true;
            $sth = $this->execute
            (
                "UPDATE users SET active_status=:active_status WHERE token=:token",
                ['token' => $token, 'active_status' => $activeStatus]
            );

            if ($sth->execute()) {
                return true;
            }
        }

        return false;
    }

    #TODO перенести в отдельный класс в библиотеку
    private function sendActivateMail($user)
    {
        $token = $user['token'];
        $subject = 'Подтверждение регистрации на сайте Camagru';
        $message = "Спасибо за регистрацию на сайте Camagru. Для подтверждения вашего аккаунта перейдите по ссылке <a href='http://localhost:8081/user/activation?token=$token'>Подтвердить</a>";

        Mail::sendMail($user['email'], $subject, $message);

    }

}
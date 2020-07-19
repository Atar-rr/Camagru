<?php

namespace App\model;

use App\core\Model;
use App\library\Validator;

//require_once ROOT . '/App/library/Validator.php';
//require_once ROOT . '/App/core/Db.php';

class User extends Model
{
    #TODO убрать валидацию в отдельный класс LoginValidate
    public function login()
    {
        $user = $_POST['user'];
        $error = [];

        $sql = "SELECT password, status_register, id  FROM users WHERE login=:login";
        $sth = $this->db->prepare($sql);
        $sth->bindParam(':login', $user['login']); // что если login не установлен
        $sth->execute();

        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        if ($result) { // вынести валидацию?
            if ($result['password'] === hash('whirlpool', $user['password'])) {
                if ($result['status_register']) {
                    $_SESSION['id'] = $result['id'];
                } else {
                    $error['status_register'] = "Аккаунт не активирован.";
                }
            } else {
                $error['password'] = "Неверный пароль";
            }
        } else {
            $error['login'] = "Пользователя с таким именем не существует";
        }

        $param = ['user' => $user, 'error' => $error];
        return $param;
    }

    public function register()
    {
        $user = $_POST['user'];
        $validator = new Validator($user);
        $error = $validator->validate();

        $param = ['user' => $user, 'error' => $error];
        if (!count($error)) {
                $user['password'] = hash('whirlpool', $user['password']);
                $user['token'] = md5($user['email']);

                $sql = "INSERT INTO users (login, password, email, token) VALUES (:login, :password, :email, :token)";
                $sth = $this->db->prepare($sql);

                $sth->bindParam(':login', $user['login']);
                $sth->bindParam(':password', $user['password']);
                $sth->bindParam(':email', $user['email']);
                $sth->bindParam(':token', $user['token']);

                $sth->execute();
                self::sendMail($user);
        }
        return $param;
    }

    public static function changePassword()
    {

    }

    public static function forgetPassword()
    {

    }

    public function activation($token)
    {

        $sql = "SELECT id FROM users WHERE token=:token";
        $sth = $this->db->prepare($sql);
        $sth->bindParam(':token', $token);
        $sth->execute();

        $result = $sth->fetch();
        if ($result) {
            $activate = true;

            $sql = "UPDATE users SET status_register=:activate WHERE token=:token";
            $sth = $this->db->prepare($sql);

            $sth->bindParam(':token', $token);
            $sth->bindParam(':activate', $activate);

            if ($sth->execute()) {
                return true;
            }
        }
        return false;
    }

    #TODO перенести в отдельный класс в библиотеку
    private function sendMail($user)
    {
        $token = $user['token'];

        $to = $user['email'];
        $subject = 'Подтверждение регистрации на сайте Camagru';
        $message = "Спасибо за регистрацию на сайте Camagru. Для подтверждения вашего аккаунта перейдите по ссылке <a href='http://localhost:8081/user/activation?token=$token'>Подтвердить</a>";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "Content-Transfer-Encoding: utf-8\r\n";
        $headers .= "Reply-To: no-reply@gmail.com\r\n";

        mail($to, $subject, $message, $headers);
    }
}
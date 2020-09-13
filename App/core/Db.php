<?php

namespace App\core;

abstract class Db
{
    public static function getConnection()
    {
        $paramsPath = ROOT . '/App/config/db.php';
        $params = include $paramsPath;

        try {
            $dsn = "mysql:host=$params[host];dbname=$params[dbname]";
            $db = new \PDO($dsn, $params['user'], $params['password']);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

            return $db;
        } catch (\PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            file_put_contents(ROOT . '/log/db_error', $e->getMessage() . "\n", FILE_APPEND);

            die();
        }
    }
}

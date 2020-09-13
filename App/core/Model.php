<?php

namespace App\core;

use \PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Db::getConnection();
    }

    protected function execute(string $sql, array $params = [])
    {
        $sth = $this->db->prepare($sql);

        $sth->execute($params);

        return $sth;
    }

    public function makeSafeForView(?array $data): array
    {
        $result = [];

        foreach ($data as $value) {
            $tmp = [];
            foreach ($value as $key => $val) {
                $tmp[$key] = htmlspecialchars($val);
            }
            $result [] = $tmp;
        }
        return $result;
    }

    protected function me()
    {
        return $_SESSION['id'];
    }

}
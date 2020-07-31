<?php

namespace App\core;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Db::getConnection();
    }
}
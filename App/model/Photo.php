<?php

namespace App\model;

use App\core\Model;

class Photo extends Model
{

    private $name;
    private $type;
    private $file;

    public function __construct()
    {
        parent::__construct();
        //возможно валидация тут
        $this->name = $_FILES['name'] ?? null;
        $this->type = $_FILES['type'] ?? null;
        if (isset($_FILES['tmp_name'])) {
            $this->file = file_get_contents($_FILES['tmp_name']);
        }
    }

    public function savePhoto()
    {
        //валидация
    }
}
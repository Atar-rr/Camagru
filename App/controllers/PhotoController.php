<?php

namespace App\controllers;

use App\core\Controller;
use App\model\Photo;

class PhotoController extends Controller
{
    public function __construct($view, array $routes = [], ?string $query = '')
    {
        parent::__construct($view, $routes, $query);
        $this->model = new Photo();
    }

    public function newAction()
    {
        $this->view->renderer('photo/new.phtml');
    }

    public function uploadAction()
    {
        #TODO проверка, что пользователь авторизован
        $this->model->uploadPhoto();
    }
}
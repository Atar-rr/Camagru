<?php

namespace App\controllers;

use App\core\Controller;
use App\model\Photo;

class GalleryController extends Controller
{
    public function __construct($view, array $routes = [], ?string $query = '')
    {
        parent::__construct($view, $routes, $query);
        $this->model = new Photo();
    }

    public function showAction()
    {
        #TODO может быть пустой массив
        $result = $this->model->getGallery();

        $this->view->renderer('index.phtml', $result);
    }
}
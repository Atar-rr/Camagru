<?php

namespace App\controllers;

class MainController
{
    public function galleryAction()
    {
        $pathView = ROOT . '/App/view/index.phtml';
        require $pathView;
        //debug($_SESSION['id']);
    }

}
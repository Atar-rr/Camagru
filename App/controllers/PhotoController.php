<?php

namespace App\controllers;

class PhotoController
{
    public function newAction()
    {
        $pathView = ROOT . '/App/view/photo/new.phtml';
        require $pathView;
    }

    public function saveAction()
    {
        //debug($_POST);
        echo 'ok';
        //$pathView = ROOT . '/App/view/photo/new.phtml';
        //require $pathView;
    }
}
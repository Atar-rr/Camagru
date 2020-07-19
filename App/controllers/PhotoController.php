<?php

namespace App\controllers;


class PhotoController
{
    public function newAction()
    {
        $pathView = ROOT . '/App/view/photo/new.phtml';
        require $pathView;
    }

    #TODO добавить валидацию загружаемых файлов
    public function saveAction()
    {
        //debug($_POST);
        debug($_FILES);
        echo 'ok';
        //$pathView = ROOT . '/App/view/photo/new.phtml';
        //require $pathView;
    }
}
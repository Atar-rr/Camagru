<?php

namespace App\core;

/**
 * Class Controller
 * @package App\core
 *
 * @method \App\controllers\GalleryController showAction()
 *
 * @method \App\controllers\PhotoController uploadAction()
 * @method \App\controllers\PhotoController newAction()
 *
 * @method \App\controllers\UserController loginAction()
 * @method \App\controllers\UserController registerAction()
 * @method \App\controllers\UserController activationAction()
 * @method \App\controllers\UserController settingAction()
 * @method \App\controllers\UserController logoutAction()
 *
 */
class Controller
{
    protected $routes = [];
    protected $view;
    protected $model;
    protected $query;

    /**
     * Controller constructor.
     * @param array $routes
     * @param string|null $query
     * @param $view
     */
    public function __construct($view, array $routes = [], ?string $query = '')
    {
        $this->routes = $routes;
        $this->view = $view;
        $this->query = $query;
    }

    /**
     * @param string $url
     */
    protected function redirect(string $url)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        die();
    }

    /**
     * @return string
     */
    private function getController(): string
    {
        return $this->routes['controller'];
    }

    /**
     * @return string
     */
    private function getModel(): string
    {
        return $this->routes['model'];
    }
}
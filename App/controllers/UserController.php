<?php

namespace App\controllers;

use App\core\Controller;
use App\model\User;

#TODO разделить запросы по глаголам http, чтобы решить вопрос одинаковых нзваний маршрутов
class UserController extends Controller
{
    protected $result = [];

    public function __construct($view, array $routes = [], ?string $query = '')
    {
        parent::__construct($view, $routes, $query);
        $this->model = new User();
    }

    public function loginAction()
    {
        if (isset($_POST['submit'])) {
            $this->result = $this->model->login();
            if (!count($this->result['error'])) {
                $this->redirect('/main/gallery');
            }
        }

        $this->view->renderer('user/login.phtml', $this->result);
    }

    public function registerAction()
    {
        $this->result['flag'] = 0; // как убрать?

        if (isset($_POST['submit'])) {
            $this->result = $this->model->register();
            if (!count($this->result['error'])) {
                #TODO редирект на страницу спасибо за регистрацию
                $this->result['flag'] = 1;
            }
        }

        $this->view->renderer('user/register.phtml', $this->result);
    }

    public function activationAction()
    {
        if (!empty($param)) {
            parse_str($this->query, $output);
            $this->result = $this->model->activation($output['token']);
        }

        $this->view->renderer('user/activation.phtml', $this->result);
    }

    public function settingAction()
    {
        if (isset($_SESSION['id'])) {
            debug('setting');
        }
    }

    public function restoreAction()
    {
        if (isset($_POST['submit'])) {
            $this->result = $this->model->restorePassword();
            if (!count($this->result['error'])) {
                $this->redirect('/user/login.phtml'); #TODO заглушка, чтобы шел на свою почту. а лучше может js?
            }
        }

        $this->view->renderer('user/restore.phtml', $this->result);
    }

    public function updateUser()
    {
        
    }

    public function logoutAction()
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/');
    }
}
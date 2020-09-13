<?php

namespace App\core;

/**
 * Class View
 * @package App\core
 */
class View
{
    const FOLDER_VIEW = __DIR__ . "/../view/";

    /**
     * @param string $view
     * @param array $params
     */
    public function renderer(string $view, array $params = [])
    {
        $path = self::FOLDER_VIEW . $view;
        require_once $path;
        die();
    }

    /**
     * @param string $view
     * @param int $code
     */
    public function error(string $view, int $code)
    {
        http_response_code($code);
        $path = self::FOLDER_VIEW . $view;
        require_once $path;
        die();
    }
}
<?php

namespace App\Controllers;


class Controller
{
    /**
     * @var $request
     */
    protected $request;

    /**
     * This method renders a view page
     * Here we use a convention - we remove the Controller ( eg from PostController ) part and look for view file in prefix (post)
     *
     * @param $view - is the name of the view file without .php extension
     * @param $model - is the model (eg Post )
     * @return bool
     */
    public function render($view, $model)
    {
        $path = explode('\\', get_class($this));
        $className = array_pop($path);
        $prefix = strtolower( str_replace('Controller', '', $className) );
        $dirPath = realpath(dirname(dirname(__FILE__)));
        require_once "$dirPath/views/{$prefix}/{$view}.php";

        return true;
    }

}

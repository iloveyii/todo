<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2018-10-22
 * Time: 12:05
 */

namespace App\Models;


class Request implements IRequest
{
    public $route = null;
    public $getVars = [];
    public $postVars = [];
    public $params = [];

    public function __construct()
    {
       $this->bootStrap();
    }

    /**
     * Get all the vars from $_SERVER into local class vars
     */
    private function bootStrap()
    {
        foreach ($_SERVER as $key=>$value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
        $this->redirectUrl = isset($this->redirectUrl) ? $this->redirectUrl : null;
    }

    /**
     * Converts the vars in $_SERVER to camelCase
     * The vars in $_SERVER has the form CAPS_CAPS
     * We first convert to lower, then explode by _ , then change every work into initial caps except first word
     * @param $value
     * @return mixed|string
     */
    private function toCamelCase($value)
    {
        $lower = strtolower($value);
        $parts = explode('_', $lower);
        $variableName = array_shift($parts);
        $camelParts = array_map(function ($v) {
            return ucfirst($v);
        }, $parts);
        $variableName = $variableName . implode('', $camelParts);
        return $variableName;
    }

    /**
     * Returns the GET/PUT/POST parameters in the request depending on its type in the requestMethod
     *
     * @return array
     */
    public function body()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $this->getVars = empty($this->getVars) ?  $_GET : $this->getVars; // for testing override real vars by setted vars
                return $this->getGetVars();
            case 'POST':
                $this->postVars = empty($this->postVars) ? $_POST : $this->postVars; // for testing override real vars by setted vars
                return $this->getPostVars();
            case 'PUT':
                return $this->getPutVars();
        }

        return [];
    }

    /**
     * For testing set post vars and this will override the real $_POST vars
     * @param array $post
     */
    public function setPostVars(array $post)
    {
        $this->postVars = $post;
    }

    public function isPost()
    {
        return ($this->requestMethod === 'POST');
    }

    /**
     * For testing set get vars and this will override the real $_GET vars
     * @param array $get
     */
    public function setGetVars(array $get)
    {
        $this->getVars = $get;
    }

    /**
     * For testing set the request method to desired one
     * @param $method
     */
    public function setRequestMethod($method)
    {
        $this->requestMethod = $method;
    }

    private function getGetVars()
    {
        $get = [];
        foreach ($this->getVars as $key => $value) {
            $get[$key] =  filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $get;
    }

    private function getPostVars() : array
    {
        $post = [];
        foreach ($this->postVars as $key => $value) {
            $post[$key] = $value; // filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS); // remove it for strip_tags to  work
        }

        return $post;
    }

    private function getPutVars()
    {
        $json = file_get_contents("php://input");
        $put = json_decode($json, true);
        $put['description'] = $put['author'];
        return $put;
    }

}

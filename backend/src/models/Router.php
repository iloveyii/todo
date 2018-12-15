<?php
namespace App\Models;

class Router
{
    /**
     * @var IRequest
     */
    private $request;
    /**
     * @var string
     */
    public $defaultRoute = null;
    /**
     * @var callable
     */
    public $defaultMethod = null;
    /**
     * @var bool
     */
    private $pathNotFound = true;
    /**
     * @var array
     */
    private $allowedMethods = [ 'GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Router constructor.
     * @param IRequest $request
     * @param $defaultRoute
     */
    public function __construct(IRequest $request, $defaultRoute)
    {
        $this->request = $request;
        $this->defaultRoute = $defaultRoute;

        if( ! in_array($request->requestMethod, $this->allowedMethods)) {
            header("{$this->request->serverProtocol} 405 Method Not Allowed, allowed methods are ", implode(', ', $this->allowedMethods));
            exit(1);
        }
    }

    /**
     * Handles the get HTTP request
     *
     * @param $route
     * @param $method
     * @throws \Exception
     */
    public function get($route, $method)
    {
        $this->request->route = $route;
        $redirectUrl = isset($this->request->redirectUrl) ? $this->request->redirectUrl : null;
        // Make index optional
        if(substr($redirectUrl, -1) === '/') {
            $redirectUrl = $redirectUrl . 'index';
        }

        // Set Default Route's Method
        $this->defaultMethod = $this->defaultRoute === $route ? $method : $this->defaultMethod;

        if($route === $redirectUrl && $this->request->requestMethod === 'GET') {
            $this->pathNotFound = false;
            call_user_func_array($method, [$this->request]);
            Log::write('Found route ' . $route, INFO);
            exit(0);
        }
    }

    /**
     * Handles the post HTTP request
     *
     * @param $route
     * @param $method
     * @throws \Exception
     */
    public function post($route, $method)
    {
        $this->request->route = $route;
        $redirectUrl = $this->request->redirectUrl;
        // Make index optional
        if(substr($this->request->redirectUrl, -1) === '/') {
            $redirectUrl = $redirectUrl . 'index';
        }

        if($route === $redirectUrl && $this->request->requestMethod === 'POST') {
            $this->pathNotFound = false;
            call_user_func_array($method, [$this->request]);
            Log::write('Found route ' . $route, INFO);
            exit(0);
        }
    }

    /**
     * Handles the put HTTP request
     *
     * @param $route
     * @param $method
     */
    public function put($route, $method)
    {
        $this->request->route = $route;
        $this->getParams($route);

        if($this->request->requestMethod === 'PUT') {
            $this->pathNotFound = false;
            call_user_func_array($method, [$this->request]);
            exit(0);
        }
    }

    /**
     * Handles the delete HTTP request
     *
     * @param $route
     * @param $method
     */
    public function delete($route, $method)
    {
        $this->request->route = $route;
        $this->getParams($route);

        if($this->request->requestMethod === 'DELETE') {
            $this->pathNotFound = false;
            call_user_func_array($method, [$this->request]);
            exit(0);
        }
    }

    private function renderDefaultRoute()
    {
        if( ! is_null($this->defaultRoute) && is_callable($this->defaultMethod)) {
            return call_user_func_array($this->defaultMethod, [$this->request]);
        }
        return false;
    }

    /**
     * Note: works for one param only
     * @param $route
     */
    private function getParams($route)
    {
        // Split out id part
        $redirectUrl = $this->request->redirectUrl;
        $routeArray = explode(':', $route);
        $varsArray = [];

        if(count($routeArray) > 1) {
            $route = array_shift($routeArray);
            foreach ($routeArray as $varName) {
                $varsArray[$varName] = str_replace($route, '', $redirectUrl);
            }
            $this->request->params = $varsArray;
        }
    }

    /**
     * If no route found then renders default route finally
     */
    public function __destruct()
    {
        if($this->pathNotFound) {
            Log::write('Did not find route ' . $this->request->redirectUrl, WARN);

            $result = $this->renderDefaultRoute();
            if($result === false) {
                header("{$this->request->serverProtocol} 404 Not Found");
            }
        }
        $this->request = null;
        exit(0);
    }

}

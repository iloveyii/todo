<?php
    require_once '../vendor/autoload.php';
    require_once '../config/app.php';

    use App\Models\Request;
    use App\Models\Router;

    /**
     * First create router object with params Request object and default route
     * Next declare the http methods
     */
    $router = new Router(new Request, '/task/index');

    /**
     * Serve user pages
     */
    $router->get('/user/login', function ($request) {
        $controller = new \App\Controllers\UserController($request);
        $controller->login();
    });
    $router->post('/user/login', function ($request) {
        $controller = new \App\Controllers\UserController($request);
        $controller->login();
    });
    $router->get('/user/logout', function ($request) {
        $controller = new \App\Controllers\UserController($request);
        $controller->logout();
    });
    $router->get('/user/signup', function ($request) {
        $controller = new \App\Controllers\UserController($request);
        $controller->create();
    });
    $router->post('/user/signup', function ($request) {
        $controller = new \App\Controllers\UserController($request);
        $controller->create();
    });

    /**
     * Serve task pages
     */
    $router->get('/task/index', function ($request) {
        $controller = new \App\Controllers\TaskController($request);
        $controller->index();
    });

    $router->get('/task/create', function ($request) {
        $controller = new \App\Controllers\TaskController($request);
        $controller->create();
    });

    $router->get('/task/update/:id', function ($request) {
        $controller = new \App\Controllers\TaskController($request);
        $controller->update();
    });

    $router->post('/task/update', function ($request) {
        $controller = new \App\Controllers\TaskController($request);
        $controller->update();
    });

    $router->post('/task/create', function ($request) {
        $controller = new \App\Controllers\TaskController($request);
        $controller->create();
    });

    $router->post('/task/delete', function ($request) {
        // header("Content-Type: application/json");
        $controller = new \App\Controllers\TaskController($request);
        $result = $controller->delete();
        echo json_encode($result);
    });

    /**
     * For RESTFul API
     */
    $router->get('/api/v1/task', function ($request) {
        header("Content-Type: application/json");
        $controller = new \App\Controllers\TaskController($request);
        $result = $controller->all();
        echo json_encode($result);;
    });



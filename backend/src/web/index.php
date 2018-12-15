<?php
require_once '../vendor/autoload.php';
require_once '../config/app.php';

use App\Models\Router;
use App\Models\Request;

/**
 * First create router object with params Request object and default route
 * Next declare the http methods
 */
$router = new Router(new Request, '/events/index');

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
 * Serve events pages
 */
$router->get('/events/index', function ($request) {
    $controller = new \App\Controllers\EventController($request);
    $controller->index();
});

/**
 * Serve vote pages / requests
 */
$router->post('/vote/create', function ($request) {
    $controller = new \App\Controllers\VoteController($request);
    $controller->create();
});

/**
 * For RESTFul API
 */
$router->get('/api/v1/events', function ($request) {
    header("Content-Type: application/json");
    $controller = new \App\Controllers\EventController($request);
    $result = $controller->all();
    echo json_encode($result);;
});

$router->get('/api/v1/random', function ($request) {
    header("Content-Type: application/json");
    $controller = new \App\Controllers\EventController($request);
    $result = $controller->byRandomCategory();
    echo json_encode($result);;
});

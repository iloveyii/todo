Alip Framework
=======
This a light PHP framework based on MVC pattern. The model has the business logic like validation. The view has the presentation (html) logic. The controller handles the different types of HTTP request. But before controller we need a router to route the request to designated controller based on the request URI.
We have implemented a very light Router class that handles GET, POST, UPDATE and DELETE methods.

This framework does not use any built in libraries or packages. All code is custom written. It only uses composer for autoloading.

This framework is useful for a light PHP web application or RESTFull API application. This has been used on site [Simple Blog](http://alip.softhem.se).


## Setup and first run

  * First clone the repository.
  * Then run composer command `composer dump-autoload`.
  * Create a database (manually for now) and adjust the database credentials in the `config/app.php` file as per your environment.
  * Run the init command to create the database table as `php init.php`.

For more information about using Composer please see its [documentation](http://getcomposer.org/doc/).

## How to use the framework

This framework is very easy to be used. You can create an object of the router by passing a request object to it as shown below.

```
// index.php
require_once 'vendor/autoload.php';
require_once 'config/app.php';

use App\Models\Router;
use App\Models\Request;

/**
 * First create router object with params Request object and default route
 */
$router = new Router(new Request, '/posts/index');

/**
 * Next declare the http methods
 */
$router->get('/posts/index', function ($request) {
    $controller = new \App\Controllers\PostController($request);
    $controller->index();
});
```

DEMO is here [DEMO](http://alip.softhem.se).

## Overall Structure

Bellow the directory structure used:

```

   |-config
   |---app.php
   |-controllers
   |-models
   |---Database.php
   |---Post.php
   |---Request.php
   |---Router.php
   |-views
   |-web
   |---index.php
   |---assets
   
 ```


## Requirements
   * You need to enable mode rewrite and use the file `.htaccess` in the web directory.
   * Make web directory writeable for web server user (www-data in apache), to enable logging.
   * Disable displaying errors in config/app.php
   * PHP 7.2
   * Apache 2
   * MySql 14.14
   
<i>Web development has never been so fun.</i>  
[Hazrat Ali](http://blog.softhem.se/) 

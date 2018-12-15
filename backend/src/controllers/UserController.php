<?php
namespace App\Controllers;


use App\Models\Event;
use App\Models\User;

class UserController extends Controller
{

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Returns the form page for new user sign up
     * It the HTTP request is a post then it save it to DB and tries to login user
     * If user could not be logged in then redirects login page
     * If any errors in the model then shows the same form with errors
     * @return bool
     * @throws \Exception
     */
    public function create() : bool
    {
        $model = new User();
        $attributes = $this->request->body();

        if($this->request->isPost() && $model->setAttributes($attributes)->validate() && $model->create() ) {
            if($model->login()) {
                header("Location: /events/index");
                exit;
            } else {
                header("Location: /user/login");
                exit;
            }
        }

        return $this->render('create', $model);
    }

    /**
     * Does use login
     * @return bool
     */
    public function login() : bool
    {
        $model = new User();

        if($this->request->isPost()) {
            $params = $this->request->body();
            if($model->setAttributes($params)->validate() && $model->login()) {
                header("Location: /events/index");
                return true;
            }

        }
        return $this->render('login', $model);
    }

    /**
     * Does user logout
     * @return bool
     */
    public function logout() : bool
    {
        $model = new User();
        $model->logout();
        header("Location: /user/login");
        return true;
    }

}

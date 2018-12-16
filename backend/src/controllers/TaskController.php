<?php
namespace App\Controllers;


use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Returns the index page
     */
    public function index()
    {
        if( ! User::isLoggedIn()) {
            header("Location: /user/login");
        }
        $model = new Task();
        $model->user_id = User::getLoggedInUserId();

        $tasks = $model->read();

        if(count($tasks) > 0 ) {
            $this->render('index', $tasks);
        } else {
            $this->render('nodata', []);
        }
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
        if( ! User::isLoggedIn()) {
            header("Location: /user/login");
        }

        $model = new Task();
        $attributes = $this->request->body();
        $user_id = User::getLoggedInUserId();
        $attributes['user_id'] = $user_id;

        if($this->request->isPost() && $model->setAttributes($attributes)->validate() && $model->create() ) {

            header("Location: /task/index");
            exit;
        }

        return $this->render('create', $model);
    }

    public function update() : bool
    {
        if( ! User::isLoggedIn()) {
            header("Location: /user/login");
        }

        $model = new Task();
        $attributes = $this->request->body();
        $user_id = User::getLoggedInUserId();
        $attributes['user_id'] = $user_id;

        if($this->request->isPost() && $model->setAttributes($attributes)->validate() && $model->update() ) {

            header("Location: /task/index");
            exit;
        }

        if(isset($_GET['id'])) {
            $model->user_id = User::getLoggedInUserId();
            $row = $model->read($_GET['id']);
            $model->setAttributes($row);
        }
        return $this->render('update', $model);
    }

    /**
     * Returns the list of all events
     */
    public function all()
    {
        $model = new Event();
        $events = $model->readAll();
        return ['events'=>$events];
    }

    /**
     * Deletes a post
     * The id of the post exists in the params attribute of request object
     */
    public function delete()
    {
        $user = new Task();
        $attributes = $this->request->body();

        if( $user->setAttributes($attributes)->delete() ) {
            // return ['status' => 'success']; // for ajax
            header("Location: /task/index");
            exit;
        }

        return ['status' => 'cannot delete'];
    }

}

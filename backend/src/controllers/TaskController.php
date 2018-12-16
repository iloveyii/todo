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
        $tasks = $model->read();

        if(count($tasks) > 0 ) {
            $this->render('index', $tasks);
        } else {
            $this->render('nodata', []);
        }
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
     * Returns rows by random group name
     * @return array
     */
    public function byRandomCategory() : array
    {
        $model = new Event();
        return $model->readAllByRandomCategoryName();
    }
}

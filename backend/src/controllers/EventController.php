<?php
namespace App\Controllers;


use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    const DATA_DIR = 'data';
    const JSON_FILE = 'test-assignment.json';

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
        $model = new Event();
        $events = $model->readAllByRandomCategoryName($_SESSION['user_id']);
        if(count($events) > 0 ) {
            $this->render('index', $events);
        } else {
            $this->render('thanks', []);
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

<?php
namespace App\Controllers;


use App\Models\Event;
use App\Models\User;
use Couchbase\UserSettings;

class VoteController extends Controller
{

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Creates votes for all the posted events from page events/index
     * @throws \Exception
     */
    public function create()
    {
        $winningIds = [
            'home'=>1,
            'draw'=>2,
            'away'=>3
        ];

        if($this->request->isPost()) {
            if( ! User::isLoggedIn()) {
                header("Location: /user/login");
                exit;
            }

            /**
             * Define a vote model
             */
            $vote = new \App\Models\Vote();
            $polls = $this->request->body();
            $user_id = User::getLoggedInUserId();

            foreach ($polls as $var => $value) {
                /**
                 * explode radio_id and get id of event
                 */
                $arr = explode('_', $var);
                $event_id = $arr[1];
                /**
                 * $value (home, draw, away), so get respective id from $winningIds
                 */
                $winner_id = $winningIds[$value];
                /**
                 * Make attributes array for saving to model
                 */
                $attributes = [
                    'event_id' => $event_id,
                    'user_id' => $user_id,
                    'winner_id' => $winner_id
                ];

                $vote->setAttributes($attributes)->create();
            }
        }

        header("Location: /events/index");
        exit(0);
    }

}

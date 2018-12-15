<?php

namespace App\Models;


class Vote extends Model
{
    /**
     * @var null|int
     */
    public $id;
    public $event_id;
    public $user_id;
    public $winner_id;

    /**
     * @var string
     */
    public $tableName = 'vote';


    /**
     * Post constructor.
     */
    public function __construct()
    {
    }

    /**
     * Pass request object to this method to set the object attributes
     * @param array $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->id = isset($attributes['id'])  ? $attributes['id'] : null;
        $this->event_id = $attributes['event_id'];
        $this->user_id = $attributes['user_id'];
        $this->winner_id = $attributes['winner_id'];
        $this->isNewRecord = $this->id === null ? true : false;
        return $this;
    }

    /**
     * These are the validation rules for the attributes
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => ['integer'],
            'title' => ['string', 'minLength'=>5, 'maxLength'=>140, 'alpha'],
            'description' => ['string', 'minLength'=>15, 'maxLength'=>800, 'stripTags'],
        ];
    }

    // Abstract methods

    public function createTable(): bool
    {
        $createTable = "CREATE TABLE $this->tableName(
        id INT( 11 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        event_id INT( 11 ) UNSIGNED,
        user_id INT( 11 ) UNSIGNED,
        winner_id INT( 11 ) UNSIGNED,
        createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_vote_polled (event_id, user_id)
        );";
        $result = Database::connect()->exec($createTable);
        Log::write("Created table $this->tableName", INFO);
        return $result;
    }

    // CRUD

    /**
     * Creates a db post record
     * @return bool
     * @throws \Exception
     */
    public function create() : bool
    {
        $query = sprintf("INSERT INTO %s (event_id, user_id, winner_id) 
                                 VALUES (:event_id, :user_id, :winner_id)
                                 ON DUPLICATE KEY UPDATE winner_id=:winner_id
                                 ", $this->tableName);
        $params = [':event_id'=>$this->event_id, ':user_id'=>$this->user_id, ':winner_id'=>$this->winner_id];
        return Database::connect()->insert($query, $params);
    }

    /**
     * Reads all posts from db into associative array
     * @param null | integer $id
     * @return array
     * @throws \Exception
     */
    public function read( $id = null) : array
    {
        $query = sprintf("SELECT * FROM %s", $this->tableName);
        $params = [];

        if($id !== null) {
            $query = sprintf("SELECT * FROM %s WHERE id=:id", $this->tableName);
            $params = [':id'=>$id];
        }
        $rows = Database::connect()->selectAll($query, $params);

        return $rows;
    }

    public function readColumnAll($columnName) : array
    {
        $query = sprintf("SELECT %s FROM %s", $columnName, $this->tableName);
        $rows = Database::connect()->selectAll($query, []);
        return $rows;
    }

    /**
     * Updates the given record in DB using id in Request object
     * @return bool
     * @throws \Exception
     */
    public function update() : bool
    {
        $query = sprintf("UPDATE %s SET title=:title, description=:description WHERE id=:id", $this->tableName);
        $params = [':id'=>$this->id, ':title'=>$this->title, ':description'=>$this->description];
        $result = Database::connect()->update($query, $params);
        return $result;
    }

}

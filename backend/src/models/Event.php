<?php

namespace App\Models;


class Event extends Model
{
    /**
     * Name of the directory which contains json file
     */
    const DATA_DIR = 'data';
    /**
     * Name of the json file for events
     */
    const JSON_FILE = 'test-assignment.json';
    /**
     * Name of the database column on which to group the events and show to user in poll
     */
    const CATEGORY_COLUMN_NAME = 'sport';
    /**
     * If a poll is submitted for an event should we repeat it with the pre selected values ?
     */
    const REPEAT_CATEGORY_POLL = true;

    /**
     * @var null|int
     */
    public $id;
    /**
     * @var string
     */
    public $objectId;

    /**
     * @var string
     */
    public $awayName;
    /**
     * @var string
     */
    public $homeName;
    public $name;
    public $groupName;
    public $sport;
    public $country;
    public $state;
    /**
     * @var dateTime
     */
    public $createdAt;

    /**
     * @var string
     */
    public $tableName = 'event';

    /**
     * Post constructor.
     */
    public function __construct()
    {
    }

    /**
     * Pass request object to this method to set the object attributes
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->id = isset($attributes['id'])  ? $attributes['id'] : null;
        $this->objectId = $attributes['objectId'];
        $this->homeName = $attributes['homeName'];
        $this->awayName = $attributes['awayName'];
        $this->name = $attributes['name'];
        $this->groupName = $attributes['groupName'];
        $this->sport = $attributes['sport'];
        $this->country = $attributes['country'];
        $this->state = $attributes['state'];
        $this->createdAt = $attributes['createdAt'];
        $this->isNewRecord = true;
    }

    public function loadJsonFileToTable()
    {
        $events = $this->readJsonFile();
        foreach ($events as $event) {
            $model = new static();
            // Set groupName
            $event['groupName'] = $event['group'];
            // Format date time
            $event['createdAt'] = date('Y-m-d H:i:s', strtotime($event['createdAt']));
            $model->setAttributes($event);
            $model->create();
        }
        $num = count($events);
        Log::write("Inserted $num rows in table $this->tableName", INFO);

        return $num;
    }

    private function readJsonFile() : array
    {
        $dirPath = realpath( dirname(dirname(__FILE__)) . '/' . self::DATA_DIR );
        $filePath = sprintf("%s/%s", $dirPath, self::JSON_FILE);
        if( file_exists($filePath) ) {
            $fileContents = file_get_contents($filePath);
            return json_decode($fileContents, true);
        }

        Log::write("File {$filePath} does not exist", WARN);
        return [];
    }


    /**
     * These are the validation rules for the attributes
     * @return array
     */
    public function rules() : array
    {
        return [
        ];
    }

    // Abstract methods implemented
    public function createTable(): bool
    {
        $sql = "CREATE TABLE $this->tableName(
        id INT( 11 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        objectId CHAR( 10 ),
        homeName VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        awayName VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        name VARCHAR( 180 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        groupName VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        sport VARCHAR( 40 ) NOT NULL,
        country VARCHAR( 40 ) NOT NULL,
        state VARCHAR( 40 ) NOT NULL,
        createdAt DATETIME NOT NULL);";

        $result = Database::connect()->exec($sql);
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
        $query = sprintf("INSERT INTO %s (objectId, homeName, awayName, name, groupName, sport, country, state, createdAt) 
                                 VALUES (:objectId, :homeName, :awayName, :name, :groupName, :sport, :country, :state, :createdAt)", $this->tableName);
        $params = [':objectId'=>$this->objectId, ':homeName'=>$this->homeName, ':awayName'=>$this->awayName, ':name'=>$this->name,
                    ':groupName'=>$this->groupName, ':sport'=>$this->sport, ':country'=>$this->country, ':state'=>$this->state, ':createdAt'=>$this->createdAt];
        return Database::connect()->insert($query, $params);
    }

    public function read($id = null): array
    {
        // TODO: Implement read() method.
    }

    /**
     * Reads all posts from db into associative array
     * @return array
     * @throws \Exception
     */
    public function readAll() : array
    {
        $query = sprintf("SELECT * FROM %s", $this->tableName);
        $rows = Database::connect()->selectAll($query, []);

        return $rows;
    }

    public function readColumnAll($columnName) : array
    {
        $query = sprintf("SELECT %s FROM %s", $columnName, $this->tableName);
        $rows = Database::connect()->selectAll($query, []);
        return $rows;
    }

    /**
     * Really random and faster
     * @return null
     * @throws \Exception
     */
    public function getRandomCategoryName($userId)
    {
        $rand = "SELECT sport FROM category WHERE sport NOT IN ( SELECT sport from user_voted_sport WHERE user_voted_sport.user_id = :id ) ORDER BY RAND() LIMIT 1;";
        $rows = Database::connect()->selectOne($rand, [':id'=>$userId]);

        return is_array($rows) && count($rows) > 0 ? $rows['sport'] : null;
    }

    public function readAllByRandomCategoryName($userId=1)
    {
        $randomCategoryName = $this->getRandomCategoryName($userId);
        $query = sprintf("SELECT *, %s AS categoryName  FROM %s 
                                 LEFT JOIN 
                                 ( SELECT event_id, user_id, winner_id FROM vote WHERE user_id = %d ) t1
                                 ON event.id = t1.event_id
                                 WHERE %s=:categoryName %s ORDER BY name",
            self::CATEGORY_COLUMN_NAME, $this->tableName, $userId, self::CATEGORY_COLUMN_NAME,
            self::REPEAT_CATEGORY_POLL ? '' : ' AND user_id is null ');

        $params = [':categoryName' => $randomCategoryName];
        $rows = Database::connect()->selectAll($query, $params);
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

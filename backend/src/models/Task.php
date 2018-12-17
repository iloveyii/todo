<?php

    namespace App\Models;


    class Task extends Model
    {
        /**
         * Constants
         */
        const TODO = 1;
        const IN_PROGRESS = 2;
        const DONE = 3;

        /**
         * @var null|int
         */
        public $id;
        public $user_id;
        public $title;
        public $status;

        /**
         * For sorting
         */
        public $sort = 'created_at';

        /**
         * @var string
         */
        public $tableName = 'task';


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
            $this->user_id = isset($attributes['user_id'])  ? $attributes['user_id'] : null;
            $this->title = isset($attributes['title'])? $attributes['title']:null;
            $this->status = isset($attributes['status'])?$attributes['status']:null;
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
                'title' => ['string', 'minLength'=>2, 'maxLength'=>140, 'stripTags'],
                'status' => ['integer', 'minValue'=>1, 'maxValue'=>3],
            ];
        }

        // Abstract methods

        public function createTable(): bool
        {
            $createTable = "CREATE TABLE $this->tableName(
                id INT( 11 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT( 11 ) UNSIGNED,
                title VARCHAR( 140 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                status INT( 11 ) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
            $query = sprintf("INSERT INTO %s (user_id, title, status) 
                                 VALUES (:user_id, :title, :status)
                                 ON DUPLICATE KEY UPDATE title=:title, status=:status
                                 ", $this->tableName);
            $params = [':user_id'=>$this->user_id, ':title'=>$this->title, ':status'=>$this->status];
            $id = Database::connect()->insert($query, $params);
            $this->id = $id;
            Log::write("Inserted record into table $this->tableName with id $id", INFO);
            return $id;
        }

        /**
         * Reads all posts from db into associative array
         * @param null | integer $id
         * @return array
         * @throws \Exception
         */
        public function read( $id = null) : array
        {
            $sortOrder = $this->sort === 'created_at' ? 'ASC' : 'DESC';
            $query = sprintf("SELECT * FROM %s WHERE user_id=:user_id ORDER BY created_at %s", $this->tableName, $sortOrder);
            $params = [':user_id'=>$this->user_id];

            if($id !== null) {
                $query = sprintf("SELECT * FROM %s WHERE id=:id AND user_id=:user_id ORDER BY created_at %s", $this->tableName, $sortOrder);
                $params = [':id'=>$id , ':user_id'=>$this->user_id];
                return Database::connect()->selectOne($query, $params);
            }
            $rows = Database::connect()->selectAll($query, $params);

            return $rows;
        }

        /**
         * Select all rows with one column
         * @param $columnName
         * @return array
         * @throws \Exception
         */
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
            $query = sprintf("UPDATE %s SET title=:title, status=:status WHERE id=:id", $this->tableName);
            $params = [':id'=>$this->id, ':title'=>$this->title, ':status'=>$this->status];
            $result = Database::connect()->update($query, $params);
            return $result;
        }

        public function statuses()
        {
            return [
                self::TODO => 'To do',
                self::IN_PROGRESS => 'In progress',
                self::DONE => 'Done'
            ];
        }

        public static function getStatusLabel($status)
        {
            $statuses= [
                self::TODO => 'To do',
                self::IN_PROGRESS => 'In progress',
                self::DONE => 'Done'
            ];

            return isset($statuses[$status]) ? $statuses[$status] : 'NA';
        }

    }

<?php

    namespace App\Models;


    class Task extends Model
    {
        /**
         * @var null|int
         */
        public $id;
        public $title;
        public $status;

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
                'title' => ['string', 'minLength'=>5, 'maxLength'=>140, 'alpha', 'stripTags'],
                'status' => ['string', 'minLength'=>1, 'maxLength'=>10, 'alpha'],
            ];
        }

        // Abstract methods

        public function createTable(): bool
        {
            $createTable = "CREATE TABLE $this->tableName(
                id INT( 11 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR( 140 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                status VARCHAR( 10 ) NOT NULL,
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
            $query = sprintf("INSERT INTO %s (title, status) 
                                 VALUES (:title, :status)
                                 ON DUPLICATE KEY UPDATE title=:title, status=:status
                                 ", $this->tableName);
            $params = [':title'=>$this->title, ':status'=>$this->status];
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

    }
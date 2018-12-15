<?php

namespace App\Models;


class User extends Model
{
    /**
     * @var null|int
     */
    public $id;
    public $username;
    public $password;
    public $verifyPassword;

    /**
     * @var string
     */
    public $tableName = 'user';


    /**
     * Post constructor.
     */
    public function __construct()
    {
    }

    /**
     * Set the attributes from the passed associate array
     * @param $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->id = isset($attributes['id'])  ? $attributes['id'] : null;
        $this->username = $attributes['username'];
        $this->password = $attributes['password'];
        $this->verifyPassword = isset($attributes['verifyPassword']) ? $attributes['verifyPassword'] : null;
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
            'username' => ['string', 'minLength'=>3, 'maxLength'=>40, 'alpha'],
            'password' => ['string', 'minLength'=>3, 'maxLength'=>60],
            'verifyPassword' => ['string', 'minLength'=>3, 'maxLength'=>60, 'shouldMatchProperty'=>'password'],
        ];
    }

    // Abstract methods
    public function createTable(): bool
    {
        $createTable = "CREATE TABLE $this->tableName(
        id INT( 11 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username CHAR( 40 ), 
        password CHAR( 60 )
        );";
        $result = Database::connect()->exec($createTable);
        Log::write("Created table $this->tableName", INFO);
        return $result;
    }


    public function login()
    {
        $query = sprintf("SELECT * FROM %s  
                                 WHERE username = :username", $this->tableName);
        $params = [':username'=>$this->username];
        $user = Database::connect()->selectOne($query, $params);
        if(isset($user) && password_verify($this->password, $user['password'])) {
            Log::write("User $this->username logged in successfully", INFO);
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
        Log::write("User {$this->username} failed to login", INFO);
        $this->errors['login'][0] = 'Username or password incorrect';
        return false;
    }

    public function logout()
    {
        session_destroy();
        unset($_SESSION);
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function getLoggedInUsername()
    {
        $model = new User();
        $user = $model->read($_SESSION['user_id']);
        return isset($user) && count($user) > 0 ? $user['username'] : 'NA';
    }

    public static function getLoggedInUserId()
    {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    // CRUD

    /**
     * Creates a db post record
     * @return bool
     * @throws \Exception
     */
    public function create() : bool
    {
        $query = sprintf("INSERT INTO %s (username, password) 
                                 VALUES (:username, :password)", $this->tableName);
        $params = [':username'=>$this->username, ':password'=>password_hash($this->password, PASSWORD_BCRYPT)];
        $result = Database::connect()->insert($query, $params);
        Log::write("Inserted user {$this->username} into table $this->tableName", INFO);

        return $result;
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
            $row = Database::connect()->selectOne($query, $params);
            return $row === false ? [] : $row;
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

<?php
namespace App\Models;


class Winner extends Model
{
    public $tableName = 'winner';
    /**
     * @var int null
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;

    public function __construct()
    {
    }

    // Abstract methods implemented
    public function rules(): array
    {
        return [];
    }
    public function setAttributes($attributes)
    {
        $this->id = isset($attribues['id']) ?? null;
        $this->name = $attributes['name'];
        return $this;
    }

    public function createTable(): bool
    {
        $createTable = "CREATE TABLE $this->tableName(
        id INT( 11 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name CHAR( 10 ) );";
        $result = Database::connect()->exec($createTable);
        Log::write("Created table $this->tableName", INFO);
        return $result;

    }

    // CRUD
    public function create(): bool
    {
        $query = sprintf("INSERT INTO %s (name) VALUES (:name)", $this->tableName );
        $result = Database::connect()->insert($query, [':name'=>$this->name]);
        Log::write("Inserted {$this->name} as a new row into table {$this->tableName}", INFO);
        return $result;
    }


    /**
     * Reads all winners from db into associative array
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

    public function update(): bool
    {
        // TODO: Implement update() method.
    }



}

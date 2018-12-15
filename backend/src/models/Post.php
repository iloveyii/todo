<?php

namespace App\Models;


class Post extends Model
{
    /**
     * @var null|int
     */
    public $id;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    private $tableName = 'post';
    /**
     * @var mixed
     */
    private $isNewRecord = null;

    /**
     * Post constructor.
     * @param null $id
     * @param null $title
     * @param null $description
     */
    public function __construct($id = null, $title = null, $description =null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Pass request object to this method to set the object attributes
     * @param IRequest $request
     */
    public function setAttributes(IRequest $request)
    {
        $attributes = $request->body();
        $this->id = isset($request->params['id'])  ? $request->params['id'] : null;
        $this->title = $attributes['title'];
        $this->description = $attributes['description'];
        $this->isNewRecord = true;
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

    // CRUD

    /**
     * Creates a db post record
     * @return bool
     */
    public function create() : bool
    {
        $query = sprintf("INSERT INTO %s (title, description) VALUES (:title, :description)", $this->tableName);
        $params = [':title'=>$this->title, ':description'=>$this->description];
        return Database::connect()->insert($query, $params);
    }

    /**
     * Reads all posts from db into associative array
     * @return array
     */
    public function readAll() : array
    {
        $query = sprintf("SELECT id, title, description AS author, title AS filename, title AS artist FROM %s", $this->tableName);
        $rows = Database::connect()->selectAll($query, []);

        return $rows;
    }

    /**
     * Updates the given record in DB using id in Request object
     * @return bool
     */
    public function update() : bool
    {
        $query = sprintf("UPDATE %s SET title=:title, description=:description WHERE id=:id", $this->tableName);
        $params = [':id'=>$this->id, ':title'=>$this->title, ':description'=>$this->description];
        $result = Database::connect()->update($query, $params);
        return $result;

    }

    /**
     * Deletes the post with id in Request object
     * @return bool
     */
    public function delete() : bool
    {
        $query = sprintf("DELETE FROM %s WHERE id=:id", $this->tableName);
        $params = [':id'=>$this->id];
        $result = Database::connect()->delete($query, $params);
        return $result;
    }

}

<?php

    use App\Models\Task;
    use App\Models\User;
    use PHPUnit\Framework\TestCase;


    class TaskTest extends TestCase
    {
        public $task;
        public static $user;

        public static function setUpBeforeClass()
        {
            $user = new User();
            $user->setAttributes(['username'=>'taskUser1', 'password'=>'taskPass1']);
            $user->create();
            self::$user = $user;
        }

        public function setUp()
        {

            $this->task = new Task();
        }

        public function testTaskTableReturnsArray()
        {
            $rows = $this->task->read();
            $this->assertInstanceOf('App\Models\Task', $this->task);
            $this->assertTrue(is_array($rows));
        }

        public function testCanInsertDataIntoTask()
        {
            $data = [
                'user_id'=>self::$user->id,
                'title' => 'Develop Todo list',
                'status' => 'IN_PROGRESS'
            ];
            $this->task->setAttributes($data);
            $id = $this->task->create();
            $this->assertTrue($id > 0);
        }

        public function testCanDeleteDataFromTask()
        {
            $data = [
                'user_id'=>self::$user->id,
                'title' => 'Develop Todo list 2',
                'status' => 'IN_PROGRESS'
            ];
            $this->task->setAttributes($data);
            $this->task->create();
            $result = $this->task->delete();
            $this->assertTrue($result > 0);
        }

        public function testCanUpdateDataIntoTask()
        {
            $data = [
                'user_id'=>self::$user->id,
                'title' => 'Develop Todo list 3',
                'status' => 'IN_PROGRESS'
            ];
            $this->task->setAttributes($data);
            $this->task->create();
            // Fetch record by id
            $row = $this->task->read($this->task->id);
            $this->assertEquals('Develop Todo list 3', $row['title']);

            // Update
            $data = [
                'id' => $this->task->id,
                'user_id'=>self::$user->id,
                'title' => 'Develop Todo list 4',
                'status' => 'DONE'
            ];
            $this->task->setAttributes($data);
            $this->task->update();
            $row = $this->task->read($this->task->id);
            $this->assertEquals('Develop Todo list 4', $row['title']);
        }

        public function testTaskHasAttributeUserId()
        {
            $this->assertClassHasAttribute('user_id', 'App\Models\Task');
            $data = [
                'user_id'=>self::$user->id,
                'title' => 'Develop Todo list 5',
                'status' => 'IN_PROGRESS'
            ];
            $this->task->setAttributes($data);
            $this->task->create();
            $this->assertTrue($this->task->user_id === self::$user->id);
        }


    }

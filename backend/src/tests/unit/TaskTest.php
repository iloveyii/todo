<?php
    use PHPUnit\Framework\TestCase;
    use App\Models\Task;


    class TaskTest extends TestCase
    {
        public $task;

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
                'title' => 'Develop Todo list',
                'status' => 'IN_PROGRESS'
            ];
            $this->task->setAttributes($data);
            $id = $this->task->create();
            $this->assertTrue($id > 0);
        }

    }

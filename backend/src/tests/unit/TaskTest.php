<?php

    use App\Models\Task;
    use PHPUnit\Framework\TestCase;


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

        public function testCanDeleteDataFromTask()
        {
            $data = [
                'title' => 'Develop Todo list 2',
                'status' => 'IN_PROGRESS'
            ];
            $this->task->setAttributes($data);
            $this->task->create();
            $result = $this->task->delete();
            $this->assertTrue($result > 0);
        }


    }

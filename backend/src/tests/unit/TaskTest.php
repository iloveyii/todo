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
            $model = new Task();
            $rows = $model->read();
            $this->assertInstanceOf('App\Models\Task', $model);
            $this->assertTrue(is_array($rows));
        }

    }

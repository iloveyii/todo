<?php
    use PHPUnit\Framework\TestCase;
    use App\Models\Event;
    use App\Models\Database;


    class TaskTest extends TestCase
    {
        public $task;

        public function setUp()
        {
           $this->task = new Task();
        }

    }

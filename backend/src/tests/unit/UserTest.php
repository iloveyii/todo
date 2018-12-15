<?php

    use App\Models\User;
    use PHPUnit\Framework\TestCase;


    class UserTest extends TestCase
    {
        public $task;

        public function setUp()
        {
            $this->user = new User();
        }
    }

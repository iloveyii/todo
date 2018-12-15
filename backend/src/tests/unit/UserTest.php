<?php

    use App\Models\User;
    use PHPUnit\Framework\TestCase;


    class UserTest extends TestCase
    {
        public $user;

        public function setUp()
        {
            $this->user = new User();
        }

        public function testUserTableReturnsArray()
        {
            $rows = $this->user->read();
            $this->assertInstanceOf('App\Models\User', $this->user);
            $this->assertTrue(is_array($rows));
        }

        /*
        public function testCanInsertDataIntoUser()
        {
            $data = [
                'title' => 'Develop Todo list',
                'status' => 'IN_PROGRESS'
            ];
            $this->user->setAttributes($data);
            $id = $this->user->create();
            $this->assertTrue($id > 0);
        }

        public function testCanDeleteDataFromUser()
        {
            $data = [
                'title' => 'Develop Todo list 2',
                'status' => 'IN_PROGRESS'
            ];
            $this->user->setAttributes($data);
            $this->user->create();
            $result = $this->user->delete();
            $this->assertTrue($result > 0);
        }

        public function testCanUpdateDataIntoUser()
        {
            $data = [
                'title' => 'Develop Todo list 3',
                'status' => 'IN_PROGRESS'
            ];
            $this->user->setAttributes($data);
            $this->user->create();
            // Fetch record by id
            $row = $this->user->read($this->user->id);
            $this->assertEquals('Develop Todo list 3', $row['title']);

            // Update
            $data = [
                'id' => $this->user->id,
                'title' => 'Develop Todo list 4',
                'status' => 'DONE'
            ];
            $this->user->setAttributes($data);
            $this->user->update();
            $row = $this->user->read($this->user->id);
            $this->assertEquals('Develop Todo list 4', $row['title']);
        }
        */

    }

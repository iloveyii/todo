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

        public function testCanInsertDataIntoUser()
        {
            $data = [
                'username' => 'testUser',
                'password' => 'unitTest'
            ];
            $this->user->setAttributes($data);
            $id = $this->user->create();
            $this->assertTrue($id > 0);
        }

        public function testCanDeleteDataFromUser()
        {
            $data = [
                'username' => 'testUser2',
                'password' => 'unitTest2'
            ];
            $this->user->setAttributes($data);
            $this->user->create();
            $result = $this->user->delete();
            $this->assertTrue($result > 0);
        }

        public function testCanUpdateDataIntoUser()
        {
            $data = [
                'username' => 'testUser3',
                'password' => 'unitTest3'
            ];
            $this->user->setAttributes($data);
            $this->user->create();
            // Fetch record by id
            $row = $this->user->read($this->user->id);
            $this->assertEquals('testUser3', $row['username']);

            // Update
            $data = [
                'id' => $this->user->id,
                'username' => 'testUser4',
                'password' => 'unitTest4'
            ];
            $this->user->setAttributes($data);
            $this->user->update();
            $id = $this->user->id;
            $row = $this->user->read($id);
            $this->assertEquals('testUser4', $row['username']);
        }

    }

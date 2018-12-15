<?php
use PHPUnit\Framework\TestCase;
use App\Models\Event;
use App\Models\Database;


class EventTest extends TestCase
{
    public function testEventCanReturnRandomSport()
    {
        $sportsArray2D = Database::connect()->selectAll("SELECT sport FROM category", []);
        $sports = array_map('current', $sportsArray2D);
        $model = new Event();
        $randomSport = $model->getRandomCategoryName(null);
        $this->assertTrue(in_array($randomSport, $sports));
    }

    public function testEventCanReadJsonFileIntoTable()
    {
        $model = new Event();
        $model->dropTable();
        $model->createTable();
        $num = $model->loadJsonFileToTable();
        $this->assertEquals(18, $num);
    }
}

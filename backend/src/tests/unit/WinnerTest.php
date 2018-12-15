<?php
use PHPUnit\Framework\TestCase;
use App\Models\Winner;


final class WinnerTest extends TestCase
{

    public function testWinnerTableHasThreeRows()
    {
        $model = new Winner();
        $rows = $model->read();
        $this->assertEquals(3, count($rows));
    }

    public function testWinnerTableReturnsArray()
    {
        $model = new Winner();
        $row = $model->read();
        $this->assertTrue(is_array($row));
    }

    public function testWinnerTableHasKeyName()
    {
        $model = new Winner();
        $row = $model->read(1);
        $this->assertArrayHasKey('name', $row);
    }

    public function testWinnerTableHasFirstValueHome()
    {
        $model = new Winner();
        $row = $model->read(1);
        $this->assertEquals('HOME', $row['name']);
    }

    public function testWinnerTableHasSecondValueDraw()
    {
        $model = new Winner();
        $row = $model->read(2);
        $this->assertEquals('DRAW', $row['name']);
    }

    public function testWinnerTableHasThirdValueAway()
    {
        $model = new Winner();
        $row = $model->read(3);
        $this->assertEquals('AWAY', $row['name']);
    }
}

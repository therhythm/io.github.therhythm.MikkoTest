<?php

class DateUtilityTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        include_once '../DateUtility.php';
    }

    /**
     * @covers DateUtility::getWeekday
     */
    public function testGetWeekday() {
        $dateUtitility = new DateUtility;
        $this->assertEquals(1, $dateUtitility->getWeekday(2013, 13, 9, 30));
        $this->assertEquals(0, $dateUtitility->getWeekday(2013, 13, 9, 15));
        $this->assertEquals(3, $dateUtitility->getWeekday(2013, 13, 10, 30));
        $this->assertEquals(5, $dateUtitility->getWeekday(2013, 13, 11, 15));
    }
    
    /**
     * @covers DateUtility::getWeekday
     * @expectedException Exception
     */
    public function testInvalidGetWeekday() {
        $dateUtility = new DateUtility;
        $dateUtility->getWeekday(1789, 89, 7, 14);
    }
}

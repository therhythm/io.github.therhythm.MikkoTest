<?php

class MonthTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        include_once '../Month.php';
    }

    /**
     * @covers Month::calculateSalaryDay
     */
    public function testCalculateSalaryDay()
    {
        $month = new Month(9, 2013, 15, 3, 5);
        $this->assertEquals(30, $month->calculateSalaryDay());
    }
    
    /**
     * @covers Month::calculateSalaryDay
     */
    public function testCalculateAlternativeSalaryDay()
    {
        $month = new Month(11, 2013, 15, 3, 5);
        $this->assertEquals(29, $month->calculateSalaryDay());
    }

    /**
     * @covers Month::calculateBonusDay
     */
    public function testCalculateBonusDay()
    {
        $month = new Month(11, 2013, 15, 3, 5);
        $this->assertEquals(15, $month->calculateBonusDay());
    }
    
    /**
     * @covers Month::calculateBonusDay
     */
    public function testCalculateAlternativeBonusDaySunday()
    {
        $month = new Month(9, 2013, 15, 3, 5);
        $this->assertEquals(18, $month->calculateBonusDay());
    }
    
    /**
     * @covers Month::calculateBonusDay
     */
    public function testCalculateAlternativeBonusDaySaturday()
    {
        $month = new Month(2, 2014, 15, 3, 5);
        $this->assertEquals(19, $month->calculateBonusDay());
    }
    
    /**
     * @covers Month::setNumber
     * @expectedException Exception
     */
    public function testSetImpossibleMonth()
    {
        $month = new Month();
        $month->setNumber(13);
    }
    
    /**
     * @covers Month::setNumberOfDays
     * @expectedException Exception
     */
    public function testSetImpossibleNumberOfDays()
    {
        $month = new Month();
        $month->setNumberOfDays(35);
    }

    /**
     * @covers Month::setStartsOn
     * @expectedException Exception
     */
    public function testSetImpossibleStartdate()
    {
        $month = new Month();
        $month->setStartOn(8);
    }
}

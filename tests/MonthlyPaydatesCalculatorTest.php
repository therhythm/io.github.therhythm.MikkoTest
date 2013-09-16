<?php

class MonthlyPaydatesCalculatorTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        include_once '../MonthlyPaydatesCalculator.php';
    }

    /**
     * @covers Month::calculateSalaryDay
     */
    public function testCalculateSalaryDay()
    {
        $month = new MonthlyPaydatesCalculator(9, 2013, 15, 3, 5);
        $this->assertEquals(30, $month->calculateSalaryDay());
    }
    
    /**
     * @covers Month::calculateSalaryDay
     */
    public function testCalculateAlternativeSalaryDay()
    {
        $month = new MonthlyPaydatesCalculator(11, 2013, 15, 3, 5);
        $this->assertEquals(29, $month->calculateSalaryDay());
    }

    /**
     * @covers Month::calculateBonusDay
     */
    public function testCalculateBonusDay()
    {
        $month = new MonthlyPaydatesCalculator(11, 2013, 15, 3, 5);
        $this->assertEquals(15, $month->calculateBonusDay());
    }
    
    /**
     * @covers Month::calculateBonusDay
     */
    public function testCalculateAlternativeBonusDaySunday()
    {
        $month = new MonthlyPaydatesCalculator(9, 2013, 15, 3, 5);
        $this->assertEquals(18, $month->calculateBonusDay());
    }
    
    /**
     * @covers Month::calculateBonusDay
     */
    public function testCalculateAlternativeBonusDaySaturday()
    {
        $month = new MonthlyPaydatesCalculator(2, 2014, 15, 3, 5);
        $this->assertEquals(19, $month->calculateBonusDay());
    }
    
    /**
     * @covers Month::setNumber
     * @expectedException Exception
     */
    public function testSetImpossibleMonth()
    {
        $month = new MonthlyPaydatesCalculator();
        $month->setNumber(13);
    }
    
    /**
     * @covers Month::setNumberOfDays
     * @expectedException Exception
     */
    public function testSetImpossibleNumberOfDays()
    {
        $month = new MonthlyPaydatesCalculator();
        $month->setNumberOfDays(35);
    }

    /**
     * @covers Month::setStartsOn
     * @expectedException Exception
     */
    public function testSetImpossibleStartdate()
    {
        $month = new MonthlyPaydatesCalculator();
        $month->setStartOn(8);
    }
}

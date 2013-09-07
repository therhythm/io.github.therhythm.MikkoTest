<?php

class MonthTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        include_once '../Month.php';
    }

    /**
     * @covers Month::calculateSalaryDay
     */
    public function testCalculateSalaryDay() {
        $month = new Month("September", 30, 6);
        $this->assertEquals(30, $month->calculateSalaryDay());
    }
    
    /**
     * @covers Month::calculateSalaryDay
     */
    public function testCalculateAlternativeSalaryDay() {
        $month = new Month("November", 30, 4);
        $this->assertEquals(29, $month->calculateSalaryDay());
    }

    /**
     * @covers Month::calculateBonusDay
     */
    public function testCalculateBonusDay() {
        $month = new Month("Oktober", 31, 1);
        $this->assertEquals(15, $month->calculateBonusDay());
    }
    
    /**
     * @covers Month::calculateBonusDay
     */
    public function testCalculateAlternativeBonusDay() {
        $month = new Month("September", 30, 6);
        $this->assertEquals(18, $month->calculateBonusDay());
    }

}

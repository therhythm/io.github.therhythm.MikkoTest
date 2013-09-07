<?php

/**
 * Description of Month
 * Weekdays are represented using a number as such: Monday = 0, Tuesday = 1, etc.
 *
 * @author Kasper Vervaecke
 */
class Month {
    //TODO: what if suddenly paydays can be weekends and other days cannot?
    //Bonuses are paid out on the 15th, unless the 15th is a Saturday or Sunday,
    //in which case, pay out the next Wednesday, the 18th.
    const BONUS_DAY = 15;
    const ALT_BONUS_DAY = 18;
    //If the last day of the month is a Saturday or Sunday, 
    //use the date of the previous Friday instead
    const ALT_SALARY_DAY = 4;

    private $name = "January";
    private $numberOfDays = 31;
    private $startsOn = 1; //TODO: can this be calculated so multiple years are supported?
    
    //TODO: are these sensible defaults?
    public function __construct($name = "January", $numberOfDays = 31, $startsOn = 0) {
        $this->setName($name);
        $this->setNumberOfDays($numberOfDays);
        $this->setStartOn($startsOn);
    }

    //TODO: validation
    public function setName($name = "January") {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setNumberOfDays($numberOfDays = 31) {
        $this->numberOfDays = $numberOfDays;
    }

    public function getNumberOfDays() {
        return $this->numberOfDays;
    }

    public function setStartOn($startsOn = 0) {
        $this->startsOn = $startsOn;
    }

    public function getStartsOn() {
        return $this->startsOn;
    }

    public function calculateSalaryDay() {
        // (numberOfDays + indexOfFirstDay + 6) mod 7 
        // gives you the numeric representation of the last day of the month
        $lastDay = ($this->numberOfDays + $this->startsOn + 6) % 7;
        if ($lastDay > 4) {
            return $this->numberOfDays - ($lastDay - self::ALT_SALARY_DAY);
        } else {
            return $this->numberOfDays;
        }
    }

    public function calculateBonusDay() {
        //If we would assume that bonus day will always be on the 15th, 
        //we could simply check if the first day of the month is a week day, 
        //since the 1st and the 15th are always on the same weekday. However,
        //the bonus day might change in the future.
        // (usualBonusDay + indexOfFirstDay + 6) mod 7 
        // gives you the numeric representation of the 15th day of the month
        $bonusDay = (self::BONUS_DAY + $this->startsOn + 6) % 7;
        if ($bonusDay > 4) {
            return self::ALT_BONUS_DAY;
        } else {
            return self::BONUS_DAY;
        }
    }

}

?>
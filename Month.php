<?php

/**
 * Description of Month
 * Weekdays are represented using a number as such: Sunday = 0, Monday = 1, etc.
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
    const ALT_SALARY_DAY = 5;

    private $number;
    private $numberOfDays;
    private $startsOn;
    
    public function __construct($number = 1, $year = 2013) {
        include_once 'DateUtility.php';
        
        $this->setNumber($number);
        
        $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $number, $year);
        $this->setNumberOfDays($numberOfDays);
        
        $dateUtility = new DateUtility();
        $startsOn = $dateUtility->getWeekday(
                $year, substr($year, 2, 2), $number, 1);
        $this->setStartOn($startsOn);
    }

    public function setNumber($number = 1) {
        if (1 <= $number && $number <= 12) {
            $this->number = $number;
        } else {
            throw new Exception(
                    'Month numbers should be between 1 and 12');
        }
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumberOfDays($numberOfDays = 31) {
        if (28 <= $numberOfDays && $numberOfDays <= 31) {
            $this->numberOfDays = $numberOfDays;
        } else {
            throw new Exception(
                    'Months should have between 28 and 31 days');
        }
    }

    public function getNumberOfDays() {
        return $this->numberOfDays;
    }

    public function setStartOn($startsOn = 1) {
        if (0 <= $startsOn && $startsOn <= 6) {
            $this->startsOn = $startsOn;
        } else {
            throw new Exception(
                    'Start date should be between 0 and 6');
        }
    }

    public function getStartsOn() {
        return $this->startsOn;
    }

    public function calculateSalaryDay() {
        // (numberOfDays + indexOfFirstDay + 6) mod 7 
        // gives you the numeric representation of the last day of the month
        $lastDay = ($this->numberOfDays + $this->startsOn + 6) % 7;
        if ($lastDay == 0) {
            return $this->numberOfDays - 2;
        } else if ($lastDay == 6) {
            return $this->numberOfDays - 1;
        }else {
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
        $bonusDay = (Month::BONUS_DAY + $this->startsOn + 6) % 7;
        if ($bonusDay == 0 || $bonusDay == 6) {
            return Month::ALT_BONUS_DAY;
        } else {
            return Month::BONUS_DAY;
        }
    }

}

?>
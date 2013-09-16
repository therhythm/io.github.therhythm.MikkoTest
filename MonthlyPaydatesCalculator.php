<?php

/**
 * Month is a class representing calendar months which can also calculate the 
 * paydays for that given month. Weekdays are represented using a number, e.g. 
 * Sunday = 0, Monday = 1, etc.
 * 
 * @author Kasper Vervaecke <kaspervervaecke@gmail.com>
 */
class MonthlyPaydatesCalculator 
{
    private $_bonusDay;
    private $_altBonusDay;
    private $_altSalaryDay;

    private $_number;
    private $_numberOfDays;
    private $_startsOn;
    
    /**
     * Initialize this Month with a given numerical representation and a given 
     * year, calculate the number of days in this month and calculate which
     * weekday the first day of this month is.
     * 
     * @param int $number   The numerical representation of this month, e.g. 9
     * @param int $year     The full-length numerical representation of this 
     *                      year, e.g. 2013
     * 
     */
    public function __construct($number, $year, $bonusDay, $altBonusDay, $altSalaryDay)
    {
        include_once 'DateUtility.php';
        
        $this->setNumber($number);
        $this->setNumberOfDays(cal_days_in_month(CAL_GREGORIAN, $number, $year));
        $dateUtility = new DateUtility();
        $this->setStartOn($dateUtility->getWeekday(
            substr($year, 0, 2) * 100, substr($year, 2, 2), $number, 1
        ));
        $this->_bonusDay = $bonusDay;
        $this->_altBonusDay = $altBonusDay;
        $this->_altSalaryDay = $altSalaryDay;        
    }

    /**
     * Set the numerical representation of this month.
     * 
     * @param int $number   The numerical representation of this month, e.g. 9
     * 
     * @throws Exception    Month expects $number to be between 1 and 12
     * 
     */
    public function setNumber($number)
    {
        if (1 <= $number && $number <= 12) {
            $this->_number = $number;
        } else {
            throw new Exception(
                'Month numbers should be between 1 and 12'
            );
        }
    }

    /**
     * Get the numerical representation of this month.
     * 
     * @return int  The numerical representation of this month, e.g. 9
     * 
     */
    public function getNumber()
    {
        return $this->_number;
    }

    /**
     * Set the number of days in this month.
     * 
     * @param int $numberOfDays The number of days in this month
     * 
     * @throws Exception        Month expects $numberOfDays to be between
     *                          28 and 31
     * 
     */
    public function setNumberOfDays($numberOfDays)
    {
        if (28 <= $numberOfDays && $numberOfDays <= 31) {
            $this->_numberOfDays = $numberOfDays;
        } else {
            throw new Exception(
                'Months should have between 28 and 31 days'
            );
        }
    }

    /**
     * Set which weekday this month starts on.
     * 
     * @param int $startsOn The weekday this month starts on
     * 
     * @throws Exception    Month expects $startsOn to be between 0 and 6
     * 
     */
    public function setStartOn($startsOn)
    {
        if (0 <= $startsOn && $startsOn <= 6) {
            $this->_startsOn = $startsOn;
        } else {
            throw new Exception(
                'Start date should be between 0 and 6'
            );
        }
    }

    /**
     * Calculate the salary day for this month. If the last day of the month
     * is a Saturday or a Sunday, the previous Friday is used.
     * 
     * @return int  The salary day for this month
     * 
     */
    public function calculateSalaryDay()
    {
        /**
         * (numberOfDays + indexOfFirstDay + 6) mod 7
         * gives you the numeric representation of the last day of the month 
         */
        $lastDay = ($this->_numberOfDays + $this->_startsOn + 6) % 7;
        if ($lastDay == 0) {
            return $this->_numberOfDays - (7 - $this->_altSalaryDay);
        } else if ($lastDay == 6) {
            return $this->_numberOfDays - (6 - $this->_altSalaryDay);
        } else {
            return $this->_numberOfDays;
        }
    }

    /**
     * Calculate the bonus day for this month. If the bonus day is a Saturday or
     * Sunday, the next Wednesday is used.
     * 
     * @return int  The bonus day for this month.
     * 
     */
    public function calculateBonusDay()
    {
        /**
         * If we would assume that bonus day will always be on the 15th,
         * we could simply check if the first day of the month is a week day, 
         * since the 1st and the 15th are always on the same weekday. However,
         * the bonus day might change in the future.
         * (usualBonusDay + indexOfFirstDay + 6) mod 7 
         * gives you the numeric representation of the 15th day of the month 
         */
        $bonusDay = ($this->_bonusDay + $this->_startsOn + 6) % 7;
        if ($bonusDay == 0) {
            return $this->_bonusDay + $this->_altBonusDay;
        } else if ($bonusDay == 6) {
            return $this->_bonusDay + $this->_altBonusDay + 1;
        } else {
            return $this->_bonusDay;
        }
    }

}

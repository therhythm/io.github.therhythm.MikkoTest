<?php

/**
 * Month is a class representing calendar months which can also calculate the 
 * paydays for that given month. Weekdays are represented using a number, e.g. 
 * Sunday = 0, Monday = 1, etc.
 * 
 * @author Kasper Vervaecke <kaspervervaecke@gmail.com>
 */
class Month 
{
    //TODO: what if suddenly paydays can be weekends and other days cannot?
    /**
     * Bonuses are paid out on the 15th, unless the 15th is a Saturday or Sunday,
     * in which case, pay out the next Wednesday.
     */
    const BONUS_DAY = 15;
    const ALT_BONUS_DAY = 3;
    /**
     * If the last day of the month is a Saturday or Sunday, 
     * use the date of the previous Friday instead
     */
    const ALT_SALARY_DAY = 5;

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
    public function __construct($number, $year)
    {
        include_once 'DateUtility.php';
        
        $this->setNumber($number);
               
        $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $number, $year);
        $this->setNumberOfDays($numberOfDays);
        
        $dateUtility = new DateUtility();
        $startsOn = $dateUtility->getWeekday(
            substr($year, 0, 2) * 100, substr($year, 2, 2), $number, 1
        );
        $this->setStartOn($startsOn);
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
     * Get the number of days in this month.
     * 
     * @return int  The number of days in this month
     * 
     */
    public function getNumberOfDays()
    {
        return $this->_numberOfDays;
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
     * Get the weekday this month starts on.
     * 
     * @return int  The weekday this month starts on
     * 
     */
    public function getStartsOn()
    {
        return $this->_startsOn;
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
            return $this->_numberOfDays - 2;
        } else if ($lastDay == 6) {
            return $this->_numberOfDays - 1;
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
        $bonusDay = (Month::BONUS_DAY + $this->_startsOn + 6) % 7;
        if ($bonusDay == 0) {
            return Month::BONUS_DAY + Month::ALT_BONUS_DAY;
        } else if ($bonusDay == 6) {
            return Month::BONUS_DAY + Month::ALT_BONUS_DAY + 1;
        } else {
            return Month::BONUS_DAY;
        }
    }

}

?>
<?php

/**
 * DateUtility is a utility class to calculate which weekday a certain given 
 * date is. 
 * Based on: http://5dspace-time.org/Calendar/Algorithm.html
 * 
 * @author Kasper Vervaecke <kaspervervaecke@gmail.com>
 */
class DateUtility 
{

    /**
     * Calculate what weekday a certain give date is.
     * 
     * @param int $century  The full-length numerical representation of the century,
     *                      DateUtility expects the century to be between 1900 and 9999,
     *                      e.g 2000
     * @param int $year     The last two digits of the numerical representation
     *                      of the year, e.g. 13
     * @param int $month    The numerical representation of the month, e.g. 12
     * @param int $day      The numerical representation of the day, e.g. 31
     * 
     * @return int          The numerical representation of the weekday of the 
     *                      given date, where Sunday = 0, Monday = 1, etc.
     * 
     */
    public function getWeekday($century, $year, $month, $day)
    {
        if ($this->_validateValues($century, $year, $month, $day)) {
            return $weekday = ($this->_calculateCenturyOffset($century) 
                    + $this->_calculateYearOffset($year) 
                    + $this->_calculateMonthOffset($month) 
                    + $this->_calculateDayOffset($day)) % 7;
        }
    }
    
    private function _validateValues($century, $year, $month, $day)
    {
        if (1900 > $century || $century > 9999) {
            throw new Exception('Century should be between 1900 and 9999');
        } else if (0 > $year || $year > 99) {
            throw new Exception('Year should be between 00 and 99');
        } else if (1 > $month || $month > 12 ) {
            throw new Exception('Month should be between 1 and 12');
        } else if (1 > $day || $day > 31) {
            throw new Exception('Day should be between 1 and 31');
        } else {
            return true;
        }
    }

    private function _calculateCenturyOffset($century)
    {
        return $this->_mod((39 - $century), 4) * 2;
    }

    private function _calculateYearOffset($year)
    {
        return (($year + floor($year / 4)) % 7);
    }

    private function _calculateMonthOffset($month)
    {
        $offset = 0;
        for ($i = 1; $i < $month; $i++) {
            $offset = $offset + 
                    (cal_days_in_month(CAL_GREGORIAN, $i, 1) % 7);
        }
        return $offset % 7;
    }

    private function _calculateDayOffset($day)
    {
        return $day % 7;
    }
    
    /**
     * Because % doesn't play nice with negative numbers, this little function
     * is required
     */
    private function _mod($a, $b) {
        return ($a % $b) + ($a < 0 ? $b : 0);
    }

}

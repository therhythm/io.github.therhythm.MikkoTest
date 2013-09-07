<?php

/**
 * DateUtility is a utility class to calculate which weekday a certain given 
 * date is. 
 * Based on: http://5dspace-time.org/Calendar/Algorithm.html
 * @author Kasper Vervaecke
 */
class DateUtility {

    /**
     * Calculate what weekday a certain give date is.
     * @param int $century  The full-length numerical representation of the year,
     *                      DateUtility expects the year to be between 1970 and 9999,
     *                      e.g 2013
     * @param int $year     The last two digits of the numerical representation
     *                      of the year, e.g. 13
     * @param int $month    The numerical representation of the month, e.g. 12
     * @param int $day      The numerical representation of the day, e.g. 31
     * @return int          The numerical representation of the weekday of the 
     *                      given date, where Sunday = 0, Monday = 1, etc.
     */
    public function getWeekday($century, $year, $month, $day) {
        if($this->validateValues($century, $year, $month, $day)) {
            return $weekday = (($this->calculateCenturyOffset($century) 
                    + $this->calculateYearOffset($year) 
                    + $this->calculateMonthOffset($month, $century) 
                    + $this->calculateDayOffset($day) - 1) % 7);
        }
    }
    
    private function validateValues($century, $year, $month, $day) {
        if (1970 > $century || $century > 9999) {
            throw new Exception("Century should be between 1970 and 9999");
        } else if (0 > $year || $year > 99) {
            throw new Exception("Year should be between 00 and 99");
        } else if (1 > $month || $month > 12 ) {
            throw new Exception("Month should be between 1 and 12");
        } else if (1 > $day || $day > 31) {
            throw new Exception("Day should be between 1 and 31");
        } else {
            return true;
        }
    }

    private function calculateCenturyOffset($century) {
        //This will cause problems for years between 400 and 500,
        //which is unlikely to be an issue for this particular application
        //but this should be considered before reuse.
        switch ($century % 400) {
            case 0:
                return 6;
                break;
            case 100:
                return 4;
                break;
            case 200:
                return 2;
                break;
            case 300:
                return 0;
                break;
        }
    }

    private function calculateYearOffset($year) {
        return (($year + floor($year / 4)) % 7);
    }

    private function calculateMonthOffset($month, $century) {
        $offset = 0;
        for ($i = 1; $i < $month; $i++) {
            $offset = $offset + 
                    (cal_days_in_month(CAL_GREGORIAN, $i, $century) % 7);
        }
        return $offset % 7;
    }

    private function calculateDayOffset($day) {
        return $day % 7;
    }

}

?>

<?php

/**
 * Description of DateUtility
 * Based on: http://5dspace-time.org/Calendar/Algorithm.html
 * @author Kasper Vervaecke
 */
class DateUtility {

    public function getWeekday($century, $year, $month, $day) {
        return $weekday = (($this->calculateCenturyOffset($century) 
                + $this->calculateYearOffset($year) 
                + $this->calculateMonthOffset($month, $century) 
                + $this->calculateDayOffset($day) - 1) % 7);
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

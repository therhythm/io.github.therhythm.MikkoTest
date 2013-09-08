<?php

/**
 * SalaryController is the main controller of this application. It can calculate
 * the months in a given timeframe, calculate paydays for given months and write
 * these paydays to a CSV file.
 * 
 * @author Kasper Vervaecke <kaspervervaecke@gmail.com>
 */
class SalaryController 
{
    
    /*
     * Initialize SalaryController with a given timezone.
     */
    public function __construct($timeZone)
    {
        include_once 'Month.php';
        include_once 'FileWriter.php';
        date_default_timezone_set($timeZone);
    }
    
    /**
     * Create a new array of Months within a given timeframe.
     * 
     * @param int $firstMonth   The first month of the timeframe, e.g. 9
     * @param int $lastMonth    The last month of the timeframe, e.g. 12
     * @param int $year         The year of the timeframe, e.g. 2013
     * 
     * @return array    The array of Months
     * 
     */
    public function calculateMonths($firstMonth, $lastMonth, $year)
    {
        if(1 <= $firstMonth && $firstMonth <= 12
                && 1 <= $lastMonth && $lastMonth <= 12
                && $firstMonth < $lastMonth
                && 0 < $year
          ) {
            for ($i = $firstMonth; $i <= $lastMonth; $i++) {
                $months[] = new Month((int) $i, $year);
            }
            return $months;
        } else {
            throw new Exception('Invalid dates entered. Timeframe and year can 
                not be negative, months are expected to be between 1 and 12'
            );
        }
    }
    
    /**
     * Create a 2-dimenional array with a column for the month, a column for 
     * the salary day and a column for the bonus day.
     * 
     * @param array $months An array of Months
     * 
     * @return array A 2-dimensional array with the paydays for each month
     * 
     */
    public function calculatePayDays($months)
    {
        foreach ($months as $month) {
            $paydays[] = array(
                'month' => $month->getNumber(),
                'salary_day' => $month->calculateSalaryDay(),
                'bonus_day' => $month->calculateBonusDay(),
            );
        }
        return $paydays;
    }
    
    /**
     * Write a 2-dimensional array of paydays to a CSV file.
     * 
     * @param array $paydays    The 2-dimensional array to be written
     * 
     */
    public function writeToFile($paydays)
    {
        $csvFileWriter = new CSVFileWriter();
        $csvFileWriter->writeToFile($paydays, 'paydays-' . time() . '.csv');
    }
}

?>

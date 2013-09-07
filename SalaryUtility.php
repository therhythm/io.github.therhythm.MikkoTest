<?php

/**
 * Description of SalaryUtility
 *
 * @author Kasper Vervaecke
 */
class SalaryUtility {
    const TIMEZONE = 'Europe/Brussels';
    
    public function __construct() {
        include_once 'Month.php';
        include_once 'FileWriter.php';
        date_default_timezone_set(SalaryUtility::TIMEZONE);
               
        $months = $this->calculateRemainingMonths();
        $paydays = $this->calculatePayDays($months);
        $this->writeToFile($paydays);
    }
    
    //We want to calculate the paydays for the remainder of the year,
    //starting with the current month. If in the future some different 
    //timeframe needs to be used, this method should be changed.
    private function calculateRemainingMonths() {
        for ($i = date('n'); $i <= 12; $i++) {
            $months[] = new Month($i, date('Y'));
        }
        return $months;
    }
    
    //Create a 2-dimenional array with a column for the month, a column for 
    //the salary day and a column for the bonus day.
    private function calculatePayDays($months) {
        foreach ($months as $month) {
            $paydays[] = array(
                'month' => $month->getNumber(),
                'salary_day' => $month->calculateSalaryDay(),
                'bonus_day' => $month->calculateBonusDay(),
            );
        }
        return $paydays;
    }
    
    private function writeToFile($paydays) {
        $csvFileWriter = new CSVFileWriter();
        $csvFileWriter->writeToFile($paydays, 'paydays-' . time() . '.csv');
    }
}

$salaryUtility = new SalaryUtility();

?>

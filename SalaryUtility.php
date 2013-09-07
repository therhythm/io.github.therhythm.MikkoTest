<?php

/**
 * Description of SalaryUtility
 *
 * @author Kasper Vervaecke
 */
class SalaryUtility {
    const TIMEZONE = 'Europe/Brussels';
    
    public function __construct() {
        date_default_timezone_set(SalaryUtility::TIMEZONE);
               
        $months = $this->calculateRemainingMonths();
        $paydays = $this->calculatePayDays($months);
        $this->writeToFile($paydays);
    }
    
    //We want to calculate the paydays for the remainder of the year,
    //starting with the current month.
    private function calculateRemainingMonths() {
        for ($i = date('n'); $i <= 12; $i++) {
            $months[] = new Month($i, date('Y'));
        }
        return $months;
    }
    
    private function calculatePayDays($months) {
        foreach ($months as $month) {
            $paydays[] = array(
                "month" => $month->getNumber(),
                "salary_day" => $month->calculateSalaryDay(),
                "bonus_day" => $month->calculateBonusDay(),
            );
        }
        return $paydays;
    }
    
    private function writeToFile($paydays) {
        $outputFile = 'paydays-' . time() . '.csv';
        $handle = fopen($outputFile, "w");
        foreach ($paydays as $entry) {
            fputcsv($handle, $entry);
        }
        fclose($handle);
    }
}

include_once('Month.php');
$salaryUtility = new SalaryUtility();

?>

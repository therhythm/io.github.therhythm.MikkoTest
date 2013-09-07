<?php

/**
 * Description of SalaryUtility
 *
 * @author Kasper Vervaecke
 */
class SalaryUtility {
    
    public function __construct() {
        //TODO: currently hard-coded, use parameters or another elegant solution?
        $months[] = new Month("September", 30, 6);
        $months[] = new Month("Oktober", 31, 1);
        $months[] = new Month("November", 30, 4);
        $months[] = new Month("December", 31, 6);
        $paydays = $this->calculatePayDays($months);
        $this->writeToFile($paydays);
    }
    
    //TODO: is this really necessary???
    private function calculatePayDays($months) {
        $paydays = array();
        foreach ($months as $month) {
            $paydays[] = 
                    $month->getName() . ", " 
                    . $month->calculateSalaryDay() . ", "
                    . $month->calculateBonusDay() . "\n";
        }
        return $paydays;
    }
    
    private function writeToFile($paydays) {
        $outputFile = 'paydays.csv';
        $handle = fopen($outputFile, "w");
        foreach ($paydays as $entry) {
            fwrite($handle, $entry); //TODO: use fputcsv instead of fwrite
        }
        fclose($handle);
    }
}

include_once('Month.php');
$salaryUtility = new SalaryUtility();

?>

<?php

/**
 * SalaryCalculator is the main class of this application. When initialized, it will
 * calculate the remaining months of the current year, calculate the paydays for
 * these months and write the results to a CSV file.
 * 
 * @author Kasper Vervaecke <kaspervervaecke@gmail.com>
 */
class SalaryCalculator 
{
    /**
     * This is unlikely to make a big difference, but it could be changed 
     * depending on where the application is used.
     */
    const TIMEZONE = 'Europe/Brussels';
    
    /*
     * Initialize SalaryCalculator, calculate the remaining months, calculate the
     * paydays and write the results to a CSV file.
     */
    public function __construct()
    {
        include_once 'Month.php';
        include_once 'FileWriter.php';
        date_default_timezone_set(SalaryCalculator::TIMEZONE);
               
        try {
            $months = $this->_calculateRemainingMonths();
            $paydays = $this->_calculatePayDays($months);
            $this->_writeToFile($paydays);
        } catch (Exception $exc) {
            fwrite(STDERR, $exc->getMessage() . "\n");
            exit(1);
        }
    }
    
    /**
     * We want to calculate the paydays for the remainder of the year,
     * starting with the current month. If in the future some different 
     * timeframe needs to be used, this method should be changed.
     */
    private function _calculateRemainingMonths()
    {
        for ($i = date('n'); $i <= 12; $i++) {
            $months[] = new Month((int) $i, (int) date('Y'));
        }
        return $months;
    }
    
    /**
     * Create a 2-dimenional array with a column for the month, a column for 
     * the salary day and a column for the bonus day.
     */
    private function _calculatePayDays($months)
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
    
    private function _writeToFile($paydays)
    {
        $csvFileWriter = new CSVFileWriter();
        $csvFileWriter->_writeToFile($paydays, 'paydays-' . time() . '.csv');
    }
}

$salaryUtility = new SalaryCalculator();

?>

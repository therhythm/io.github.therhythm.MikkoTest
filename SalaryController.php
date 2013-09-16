<?php

/**
 * SalaryController is the main controller of this application. It can load settings, calculate
 * the months in a given timeframe, calculate paydays for given months and write
 * these paydays to a CSV file.
 * 
 * @author Kasper Vervaecke <kaspervervaecke@gmail.com>
 */
class SalaryController 
{
    private $_bonusDay;
    private $_altBonusDay;
    private $_altSalaryDay;
    
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
     * Set the bonus day
     * 
     * @param int $bonusDay The bonus day
     * 
     * @throws Exception    The bonus day is expected to be between 1 and 28
     * 
     */
    public function setBonusDay($bonusDay) {
        if (1 <= $bonusDay && $bonusDay <= 28) {
            $this->_bonusDay = $bonusDay;
        } else {
            throw new Exception('Bonusday is expected to be between 1 and 28');
        }
    }
    
    /**
     * Set the alternative bonus day
     * 
     * @param int $altBonusDay  The alternative bonus day
     * 
     * @throws Exception    The alternative bonus day is expected to be between 0 and 6
     * 
     */
    public function setAltBonusDay($altBonusDay) {
        if (0 <= $altBonusDay && $altBonusDay <= 6) {
            $this->_altBonusDay = $altBonusDay;
        } else {
            throw new Exception('Alternative bonus day is expected to be between 0 and 6');
        }
    }
    
    /**
     * Set the alternative salary day
     * 
     * @param int $altSalaryDay The alternative salary day
     * 
     * @throws Exception    The alternative salary day is expected to be between 0 and 6
     * 
     */
    public function setAltSalaryDay($altSalaryDay) {
        if (0 <= $altSalaryDay && $altSalaryDay <= 6) {
            $this->_altSalaryDay = $altSalaryDay;
        } else {
            throw new Exception('Alternative salary day is expected to be between 0 and 6');
        }
    }
    
    /**
     * Load settings from a given XML file.
     * 
     * @param string $settingsFile  The settings file
     * 
     * @throws Exception    The settings file does not exist.
     * 
     */
    public function loadSettings($settingsFile)
    {
        if (file_exists($settingsFile)) {
            $settings = simplexml_load_file($settingsFile);
            $this->setBonusDay($settings->setting[0]->value);
            $this->setAltBonusDay($settings->setting[1]->value);
            $this->setAltSalaryDay($settings->setting[2]->value);
        } else {
            throw new Exception('Settings file does not exist.');
        }
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
                && 1900 < $year && $year < 9999
          ) {
            for ($i = $firstMonth; $i <= $lastMonth; $i++) {
                $months[] = new Month((int) $i, $year, $this->_bonusDay, 
                    $this->_altBonusDay, $this->_altSalaryDay
                );
            }
            return $months;
        } else {
            throw new Exception('Invalid dates entered. Timeframe can not be 
                negative, months are expected to be between 1 and 12, year is
                expected to be between 1900 and 9999'
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

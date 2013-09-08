<?php
    /**
     * SalaryView is intended to be called from the CLI as such:
     * SalaryView.php firstmonth lastmonth year
     */

    include_once 'SalaryController.php';

    /**
     * This is unlikely to make a big difference, but it could be changed 
     * depending on where the application is used.
     */
    $timeZone = 'Europe/Brussels';

    $salaryController = new SalaryController($timeZone);
    
    if ($argc == 4) {
        try {
            $months = $salaryController->calculateMonths($argv[1], $argv[2], $argv[3]);
            $paydays = $salaryController->calculatePayDays($months);
            $salaryController->writeToFile($paydays);
        } catch (Exception $exc) {
            fwrite(STDERR, $exc->getMessage());
            exit(1);
        }
    } else {
        fwrite(STDOUT, 'SalaryView expects 3 arugments: 
            SalaryView.php first_month last_month year');
    }
?>

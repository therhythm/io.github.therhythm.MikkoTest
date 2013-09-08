# Mikko-Test

Mikko-Test is a small command-line utility, written during the application process for iController[1]. It will calculate paydays for a given timeframe.

## Usage
The application is designed to be called from the CLI as such:

    php SalaryView.php first_month last_month year

for example

    php SalaryView.php 9 12 2013

## Conventions
Days of the week are represented with a numeric value:

    Sunday = 0, Monday = 1, etc.

## Requirements
SalaryView.php requires PHP 5+ with simplexml enabled.

[1] http://icontroller.be/ 
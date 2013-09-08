<?php

/**
 * FileWriter is an interface to be implemented by classes that write to files.
 * CSVFileWriter is an implementation of this interface, designed to write some
 * given data to a given file.
 * 
 * @author Kasper Vervaecke <kaspervervaecke@gmail.com>
 */
interface FileWriter 
{
    public function _writeToFile($data, $file);
}

class CSVFileWriter implements FileWriter
{
    
    /**
     * Accepts an array of data whose entries will be formatted as CSV and
     * written to a given file.
     * 
     * @param array     $data   The array containing the data to be written
     * @param string    $file   The file the data should be written to
     * 
     */
    public function _writeToFile($data, $file)
    {
        $handle = fopen($file, 'w');
        foreach ($data as $entry) {
            $csv = fputcsv($handle, $entry);
        }
        if (!$csv) {
            throw new Exception("Failed to write to file");
        }
        fclose($handle);
    }

}

?>

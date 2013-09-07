<?php

/**
 *
 * @author Kasper Vervaecke
 */
interface FileWriter {

    public function writeToFile($data, $file);
}

class CSVFileWriter implements FileWriter {

    public function writeToFile($data, $file) {
        $handle = fopen($file, 'w');
        foreach ($data as $entry) {
            fputcsv($handle, $entry);
        }
        fclose($handle);
    }

}

?>

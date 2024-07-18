<?php

// Path to your text file
$filePath = 'dict/positives.txt';

// Initialize an empty array to hold the word-number pairs
$positive_words = array();

// Check if file exists before attempting to open
if (file_exists($filePath)) {

    // Open the file for reading
    $fileHandle = fopen($filePath, "r");

    if ($fileHandle) {
        // Go through each line of the file
        while (($line = fgets($fileHandle)) !== false) {
            // Use regular expression to extract the word and the number
            if (preg_match("/(.+) \(([-+]?[0-9]*\.?[0-9]+)\),/u", $line, $matches)) {
                // Store them in the associative array (trim used to remove any whitespace)
                $word = trim($matches[1]);
                $number = floatval($matches[2]);
                $positive_words[$word] = $number;
            }
        }
        fclose($fileHandle);
    } else {
        // Handle error when file cannot be opened
        echo "Error opening the file.";
    }
} else {
    // Handle error when file does not exist
    echo "File does not exist.";
}
?>
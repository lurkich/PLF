<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";

$EOL_patterns[0] = "/\r\n/";
$EOL_patterns[1] = "/\r/";
$EOL_patterns[2] = "/\n/";


if (($json = file_get_contents($GLOBALS['json_file_name'])) == false)
    die('Error reading json file...');


$fp = fopen($GLOBALS['csv_file_name'], 'w');



$payload = json_decode($json, true);




$headers = get_Json_Headers();



fputcsv($fp, array_keys($headers), "$");

foreach ($payload[0]['features'] as $x) {

    $csv_items = [];


    foreach(array_keys($headers) as $key) {

        $csv_items[$key] = "";
        
    }


    foreach (array_keys($x) as $level1) {

        //echo "L1 - $level1\n\r";


        if ($level1 == "geometry") {

            $csv_items["geometry"] = $x[$level1];
            $csv_items["geometry"] = json_encode($x[$level1], JSON_PRETTY_PRINT);

            // replace newlines by strings "\n" and 2 spaces by string "\t" for use when rebuilding the json file.

            // $csv_items["geometry"] = "\t\t\t" . preg_replace($EOL_patterns, '\n', $csv_items["geometry"]);
            $csv_items["geometry"] = preg_replace($EOL_patterns, '\n', $csv_items["geometry"]);
            $csv_items["geometry"] = preg_replace('/\s\s\s\s/', '\t', $csv_items["geometry"]);
            $csv_items["geometry"] = preg_replace('/\s/', '', $csv_items["geometry"]);

            
            continue;
        } 
        
        if ($level1 == "type") {
            continue;
        }
    
        foreach (array_keys($x[$level1]) as $level2) {

            //echo "  L2 - $level2\n\r";
        
            if ($level2 == "Ter_long" or 
                $level2 == "Ter_area" or 
                $level2 == "Ter_Area" or 
                $level2 == "Ter_Long") {
                // $csv_items[$level2] = floatval($x[$level1][$level2]);
                $csv_items[$level2] = preg_replace('/\./', ",", $x[$level1][$level2]);
              
            } else {
                $csv_items[$level2] = $x[$level1][$level2];
                
                
            }
            $csv_items[$level2] = preg_replace('/\r*\n*/', "", $csv_items[$level2]);
        }
        
        
    }
        
 

    fputcsv($fp, $csv_items, "$");
}

fclose($fp);

echo ("End of process.");

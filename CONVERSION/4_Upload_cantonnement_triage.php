<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";


// DB Location 

$sql_cmd = "";
$row = "";


$tbl_Cantonnements = $GLOBALS['tbl_Cantonnements'];
$tbl_Triages = $GLOBALS['tbl_Triages'];

$file_name_canton = $GLOBALS['file_name_out_cantonnements'];
$file_name_triages = $GLOBALS['file_name_out_triages'];






/**
 * 
 *  Drop the 2 tables
 */

drop_Table($tbl_Cantonnements);
drop_Table($tbl_Triages);



/**
 * 
 *  Create the tables
 * 
 */

$tbl_Definition_canton = [];

$tbl_Definition_canton["num_canton"] = "INTEGER";
$tbl_Definition_canton["nom_canton"] = "TEXT (255)";
$tbl_Definition_canton["tel_canton"] = "TEXT (255)";

Create_Table($tbl_Cantonnements, $tbl_Definition_canton);


$tbl_Definition_triage = [];

$tbl_Definition_triage["num_triage"] = "INTEGER";
$tbl_Definition_triage["nom_triage"] = "TEXT (255)";
$tbl_Definition_triage["nom_Prepose"] = "TEXT (255)";
$tbl_Definition_triage["gsm_Prepose"] = "TEXT (255)";
$tbl_Definition_triage["Ptr_Canton"] = "INTEGER";

Create_Table($tbl_Triages, $tbl_Definition_triage);




/**
 * 
 *  Connect to the database
 *  
 */

$db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);










/**
 * 
 * read file cantons and insert in DB
 * 
 */

$csv_file = fopen($GLOBALS["file_name_out_cantonnements"], 'r') or die("unable to open file" . $GLOBALS["file_name_out_cantonnements"] . " for reading. ");


while (!feof($csv_file)) {


    $rec = fgets($csv_file);
    
    $fields = explode(";", $rec);
    
    if ($fields[0] == "ID" || empty($fields[0])) {
        continue;
    }
    
    $sql_insert = "INSERT INTO $tbl_Cantonnements (" . (join(",",array_keys($tbl_Definition_canton))) .
                  " ) VALUES (" . 
                  " '$fields[1]', " .
                  " '$fields[2]', " .
                  " '$fields[3]' " .
                  ")";



    try {
        $sql_result = $db_conn->query($sql_insert);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "SQL Command : ");
        echo "$sql_insert\n\n";
    }
}







/**
 * 
 * read file triage and insert in DB
 * 
 */

$csv_file = fopen($GLOBALS["file_name_out_triages"], 'r') or die("unable to open file" . $GLOBALS["file_name_out_triages"] . " for reading. ");


while (!feof($csv_file)) {


    $rec = fgets($csv_file);

    $fields = explode(";", $rec);

    if ($fields[0] == "ID" || empty($fields[0])) {
        continue;
    }

    /**
     * 
     * Replace some invalid database interpretation characters in fields with valid ones
     */
    
     for ($i = 0; $i <  count($fields) - 1; $i++) {
        $fields[$i] = preg_replace('/"/', "", $fields[$i]);
        $fields[$i] = preg_replace("/'/", "''", $fields[$i]);
        $fields[$i] = preg_replace("/;/", ";;", $fields[$i]);        
     }

    $sql_insert = "INSERT INTO $tbl_Triages (" . (join(",", array_keys($tbl_Definition_triage))) .
        " ) VALUES (" .
        " '" . mb_convert_encoding($fields[1], 'Windows-1252', 'UTF-8') . "', " .
        " '" . mb_convert_encoding($fields[2], 'Windows-1252', 'UTF-8') . "', " .
        " '" . mb_convert_encoding($fields[3], 'Windows-1252', 'UTF-8') . "', " .
        " '" . mb_convert_encoding($fields[4], 'Windows-1252', 'UTF-8') . "', " .
        " '" . mb_convert_encoding($fields[5], 'Windows-1252', 'UTF-8') . "' " .
        ")";



    try {
        $sql_result = $db_conn->query($sql_insert);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "SQL Command : ");
        echo "$sql_insert\n\n";
    }
}

unset($db_conn);
fclose($csv_file);    
    
echo "\nEnd of process";



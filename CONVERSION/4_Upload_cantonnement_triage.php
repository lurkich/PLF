<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";


// DB Location 

$sql_cmd = "";
$row = "";


$tbl_CC = $GLOBALS['tbl_plf_CC'];



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

$tbl_Definition_canton["numero"] = "INTEGER";
$tbl_Definition_canton["nom"] = "TEXT (255)";
$tbl_Definition_canton["tel"] = "TEXT (255)";
$tbl_Definition_canton["direction"] = "TEXT (255)";
$tbl_Definition_canton["email"] = "TEXT (255)";
$tbl_Definition_canton["attache"] = "TEXT (255)";
$tbl_Definition_canton["adresse"] = "TEXT (255)";
$tbl_Definition_canton["localisation"] = "TEXT (255)";



Create_Table($tbl_Cantonnements, $tbl_Definition_canton);


$tbl_Definition_triage = [];

$tbl_Definition_triage["numero"] = "INTEGER";
$tbl_Definition_triage["nom"] = "TEXT (255)";
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
                  " '$fields[3]', " .
                  " '$fields[4]', " .
                  " '$fields[5]', " .
                  " '$fields[6]', " .
                  " '$fields[7]', " .
                  " '$fields[8]' " .
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
        " '" . $fields[1] . "', " .
        " '" . $fields[2] . "', " .
        " '" . $fields[3] . "', " .
        " '" . $fields[4] . "', " .
        " '" . $fields[5] . "' " .
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
 *  Create view view_Arlon_Cantons_Triages
 * 
 */


 $sql_cmd = "DROP TABLE $view_Cantons_Triages";

 try {
     $sql_result = $db_conn->query($sql_cmd);
 } catch (Exception $e) {
 }


 $sql_cmd = "
     CREATE VIEW $view_Cantons_Triages AS 
     SELECT $tbl_Cantonnements.tbl_id AS Canton_tbl_id,
     $tbl_Cantonnements.numero AS num_canton,
     $tbl_Cantonnements.nom AS nom_canton,
     $tbl_Cantonnements.tel AS tel_canton,
     $tbl_Cantonnements.direction AS direction_canton,
     $tbl_Cantonnements.email AS email_canton,
     $tbl_Cantonnements.attache AS attache_canton,
     $tbl_Cantonnements.adresse AS adresse_canton,
     $tbl_Cantonnements.localisation AS locatlisation_canton,

     $tbl_Triages.tbl_id AS tbl_id,
     $tbl_Triages.numero AS num_triage,
     $tbl_Triages.nom AS nom_triage,
     $tbl_Triages.nom_Prepose AS nom_Prepose,
     $tbl_Triages.gsm_Prepose AS gsm_Prepose 
     FROM ($tbl_Triages INNER JOIN $tbl_Cantonnements ON(($tbl_Triages.Ptr_Canton = $tbl_Cantonnements.tbl_id)))
     ";

 try {
     $sql_result = $db_conn->query($sql_cmd);
 } catch (Exception $e) {
     echo ("Error : " . $e->getMessage() . "SQL Command : ");
     echo "sql_Create_View_Cantons_Triages\n\n";
     return false;
 }








unset($db_conn);
fclose($csv_file);    
    
echo "\nEnd of process";



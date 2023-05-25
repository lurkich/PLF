<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";



// DB Location 

$sql_cmd = "";
$row = "";
$tbl_In_Arlon_Territories = $GLOBALS['tbl_json_final'];
$tbl_Arlon_territories = $GLOBALS["tbl_Arlon_territories"];

$tbl_In_Arlon_Cantonnements = $GLOBALS['tbl_Cantonnements'];
$tbl_Arlon_Cantonnement = $GLOBALS["tbl_Arlon_Cantonnement"];

$tbl_In_Arlon_Chasses = $GLOBALS['tbl_Chasses'];
$tbl_Arlon_Chasses = $GLOBALS["tbl_Arlon_Chasses"];


$tbl_In_Arlon_Triages = $GLOBALS['tbl_Triages'];
$tbl_Arlon_Triages = $GLOBALS["tbl_Arlon_Triages"];


$tbl_Triages = "plf_triages";
/**
 * 
 *  Duplicate the input table
 */

Duplicate_Table($tbl_In_Arlon_Territories, $tbl_Arlon_territories);
Duplicate_Table($tbl_In_Arlon_Cantonnements, $tbl_Arlon_Cantonnement);
Duplicate_Table($tbl_In_Arlon_Chasses, $tbl_Arlon_Chasses);
Duplicate_Table($tbl_In_Arlon_Triages, $tbl_Arlon_Triages);

/**
 * 
 *  Connect to the database
 *  
 */

$db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


/**
 * 
 * read into memory table Direction Arlon into array $list_Canton
 * 
 */

$list_DA_Arlon = [];


$sql_cmd = "SELECT tbl_Id, DA_Numero FROM " . $tbl_Arlon_territories;



foreach ($db_conn->query($sql_cmd) as $record) {
    
    if ($record['DA_Numero'] == "") {

        Delete_Record($db_conn, $tbl_Arlon_territories, $record['tbl_Id']);

    }

}

$sql_cmd = "SELECT tbl_Id, DA_Numero FROM " . $tbl_Arlon_Chasses;



foreach ($db_conn->query($sql_cmd) as $record) {
    
    if (substr($record['DA_Numero'],0,1) <> "9") {

        Delete_Record($db_conn, $tbl_Arlon_Chasses, $record['tbl_Id']);

    }

}




unset($db_conn);
echo ("\nEnd of process.");




function Delete_Record($db_conn, $Table, $Tbl_Id)
    {

        // Build SQL statement


        $sql_Delete = "DELETE FROM $Table WHERE " .
            " tbl_id = $Tbl_Id" ;



        // Execute SQL statement

        try {
            $sql_result = $db_conn->query($sql_Delete);
        } catch (Exception $e) {
            echo ("Error : " . $e->getMessage() . "SQL Command : ");
            echo "$sql_Delete\n\n";
            return false;
        }

        return true;

    }

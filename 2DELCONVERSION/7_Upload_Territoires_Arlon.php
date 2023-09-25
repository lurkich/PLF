<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";



// DB Location 

$sql_cmd = "";
$row = "";

$db_Territoires_Arlon = $GLOBALS["DB_Territoires_chasse_Direction_Arlon_2023_MW"];
$tbl_In = $GLOBALS["tbl_In_Territoires_chasse_Direction_Arlon_2023_MW"];
$tbl_Out = $GLOBALS["tbl_Out_Territoires_chasse_Direction_Arlon_2023_MW"];


// Create the table

$tbl_Definition = [];

$tbl_Definition["OBJECTID"] = "INTEGER";
$tbl_Definition["SHAPE_Leng"] = "DOUBLE";
$tbl_Definition["SHAPE_Area"] = "DOUBLE";
$tbl_Definition["Canto"] = "TEXT (255)";
$tbl_Definition["Numero"] = "TEXT (255)";
$tbl_Definition["Nom"] = "TEXT (255)";
$tbl_Definition["id"] = "INTEGER";

Create_Table($tbl_Out, $tbl_Definition);

/**
 * 
 *  Connect to the database Territoire_Arlon (ARCGIS) Database
 *  
 */

$db_conn_In = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_Territoires_Arlon'] . ";Uid=; Pwd=;");
$db_conn_In->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/**
 * 
 *  Connect to the PLF Database
 *  
 */

$db_conn_Out = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
$db_conn_Out->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


/**
 * 
 * read each record IN / transform special characters and Write to DB Out
 * 
 */




$sql_cmd = "SELECT * FROM $tbl_In";



foreach ($db_conn_In->query($sql_cmd) as $record_In) {


    $Canton = Transform_Special_Character($record_In["Canto"]);
    $Canton = mb_convert_encoding($Canton, 'Windows-1252', 'UTF-8');
    $Nom = Transform_Special_Character($record_In["Nom"]);
    $Nom = mb_convert_encoding($Nom, 'Windows-1252', 'UTF-8');
 

    $sql_insert = "INSERT INTO $tbl_Out (" . (join(",", array_keys($tbl_Definition))) .
        " ) VALUES (" .
        " " . round($record_In["OBJECTID"]) . ", " .
        " " . $record_In["SHAPE_Leng"] . ", " .
        " " . $record_In["SHAPE_Area"] . ", " .
        " '" . $Canton . "', " .
        " " . round($record_In["Numero"]) . ", " .
        " '" . $Nom . "', " .
        " " . round($record_In["id"]) . " " .
        ")";

    try {
        $sql_result = $db_conn_Out->query($sql_insert);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "SQL Command : ");
        echo "$sql_insert\n\n";
    }
}




unset($db_conn_In);
unset($db_conn_Out);


echo ("\nEnd of process.");









function Transform_Special_Character($rec_field)
{


    $rec_field  = preg_replace('/"/', "", $rec_field);
    $rec_field  = preg_replace("/'/", "''", $rec_field);
    $rec_field  = preg_replace("/;/", ";;", $rec_field);
    $rec_field = preg_replace('/\x3f\xae/', 'é', $rec_field);
    $rec_field = preg_replace('/\x3f\xac/', 'ê', $rec_field);
    $rec_field = preg_replace('/\x3f\xba/', 'ç', $rec_field);
    $rec_field = preg_replace('/\x3f\xbf/', 'è', $rec_field);
    $rec_field = preg_replace('/\x3f\x3f/', 'û', $rec_field);
    // $rec_field = mb_convert_encoding($rec_field, 'Windows-1252', 'UTF-8');

    return $rec_field;
}

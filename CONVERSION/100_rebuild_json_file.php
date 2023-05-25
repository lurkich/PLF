<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";


$fp = fopen($GLOBALS['json_rebuild_file_name'], 'w');
$fp2 = fopen($GLOBALS['json_rebuild_without_tabs_file_name'], 'w');


/**
 * 
 *  Connect to the database and set settings
 *  
 */

ini_set("odbc.defaultlrl", "20000");
ini_set("mssql.textlimit", "20000");
ini_set("mssql.textsize", "20000");



/**
 * 
 *   ReadDataBase
 */

 $sql_cmd = "SELECT geometry, nomenclature, territories_id, Territories_name FROM $tbl_json_final ORDER BY nomenclature";



$db = odbc_connect("Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . "", "", "");

$result = odbc_exec($db, $sql_cmd);
odbc_longreadlen($result, 300000);      // !!!!!!!! this is the maximum record length. 

$headers = "[\r\n\t{\r\n\t\t\"type\" : \"FeatureCollection\"," .
    "\r\n\t\t\"name\" : \"NewFeatureType\"," .
    "\r\n\t\t\"features\" : [";

fwrite($fp, $headers);


$Is_First_Row = true;


while (odbc_fetch_row($result) == true) {

    $v_In = odbc_result($result, 1);
    $v_nomenclature = odbc_result($result, 2);
    $v_territories_id = odbc_result($result, 3);
    $v_territories_name = odbc_result($result, 4);

    if ($Is_First_Row == true) {
        $Is_First_Row = false;

    } else {


        $v_territories_name = preg_replace('/"/', '\\"', $v_territories_name);



        $headers = ",\r\n\t\t\t\t\"properties\" : {" .
            "\r\n\t\t\t\t\t\"Nomenclature\" : \"" . $v_nomenclature . "\"," .
            "\r\n\t\t\t\t\t\"Territories_id\" : \"" . $v_territories_id . "\"," .
            "\r\n\t\t\t\t\t\"Territories_name\" : \"" . $v_territories_name . "\"" .
            "\r\n\t\t\t\t}" .
            "\r\n\t\t\t}," . 
            "\r\n\t\t\t";

        fwrite($fp, $headers);
    }





    $headers = "\r\n\t\t\t{\r\n\t\t\t\t\"type\" : \"Feature\"," .
        "\r\n\t\t\t\t\"geometry\" : ";

    fwrite($fp, $headers);








    $v_Out = $v_In;


    $v_Out = preg_replace('/("type": "MultiPolygon")/','\t$1', $v_Out);
    $v_Out = preg_replace('/("coordinates")/','\t$1', $v_Out);


    $v_Out = preg_replace('/\\\n[\\\t]+(\d)/', "$1", $v_Out);
    $v_Out = preg_replace('/(\d)\\\n[\\\t]+\]/', "$1]", $v_Out);
    $v_Out = preg_replace('/\\\n}"/',"\r\n\t}" . '"', $v_Out);



    $v_Out = preg_replace('/\\\n/', "\r\n\t\t\t", $v_Out);


    $v_Out = preg_replace('/\\\t/', "\t", $v_Out);




    $v_Out = preg_replace('/^"/', "", $v_Out, 1);   // replace first quote by empty string
    $v_Out = preg_replace('/"$/', "", $v_Out, 1);   // replace last quote by empty string

    // $v_Out = preg_replace('/\x00/', "", $v_Out);




    $record_len = strlen($v_Out);
    $written_bytes = fwrite($fp, $v_Out);



    //  echo "record --> length = $record_len : bytes written = $written_bytes\n";
}



$headers = ",\r\n\t\t\t\t\"properties\" : {" .
    "\r\n\t\t\t\t\t\"Nomenclature\" : \"" . $v_nomenclature . "\"," .
    "\r\n\t\t\t\t\t\"Territories_id\" : \"" . $v_territories_id . "\"," .
    "\r\n\t\t\t\t\t\"Territories_name\" : \"" . $v_territories_name . "\"" .
    "\r\n\t\t\t\t}" .
    "\r\n\t\t\t}" . 
    "\r\n\t\t]" . 
    "\r\n\t}" . 
    "\r\n]";

fwrite($fp, $headers);


$file_without_tabs = file_get_contents($GLOBALS['json_rebuild_file_name']);


$patterns[0] = '/\t/';
$patterns[1] = '/\n/';
$patterns[2] = '/\r/';
$patterns[3] = '/\r\n/';

$file_without_tabs = preg_replace($patterns,"",$file_without_tabs);

file_put_contents($GLOBALS['json_rebuild_without_tabs_file_name'], $file_without_tabs);



unset($db_conn);
echo ("\nEnd of process.");

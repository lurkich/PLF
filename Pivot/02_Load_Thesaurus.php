<?php

// require_once __DIR__ . "/../CONVERSION/functions.php";
require_once __DIR__ . "/../WEB/Functions.php";
require __DIR__ . "/Parameters.php";


/**
 * 
 *  Initialize variables
 * 
 */

$list_value = [];


$csv_file_name_thesaurus = $GLOBALS['csv_file_name_thesaurus'];
$tbl_Pivot_Thesaurus = $GLOBALS['tbl_Pivot_Thesaurus'];





/**
 *  open output csv file
 */

$fp_thesaurus = fopen($csv_file_name_thesaurus, 'w');


/**
 *  Create the database tables
 */

$tbl_headers["urn_name"] = "TINYTEXT";
$tbl_headers["lang"] = "VARCHAR(2)";
$tbl_headers["urn_value"] = "TEXT";
$tbl_headers["abstract"] = "TEXT";


$result = PLF::__Create_Table($tbl_Pivot_Thesaurus, $tbl_headers);




// Connect to the database

$db_connection = PLF::__Open_DB();


if ($db_connection == NULL) {

    $RC = -5;
    $RC_Msg = PLF::Get_Error();

    echo("ERROR - Load_Thesaurus : " . $RC_Msg);
    return array($RC, $RC_Msg, array());;
}

$flg_UNKNOWN_record = false;

$xml_data_full = simplexml_load_file($GLOBALS['xml_file_name_thesaurus_8']);
Process_XML_file($xml_data_full);
unset($xml_data_full);

$xml_data_full = simplexml_load_file($GLOBALS['xml_file_name_thesaurus_10']);
Process_XML_file($xml_data_full);
unset($xml_data_full);

echo("End process.");

exit;

/**
 * 
 *  Read the XML files in memory. Keep only the spec nodes
 * 
 */


function Process_XML_file($xml_data_full) {

    global $flg_UNKNOWN_record;

        global $list_value;
        global $fp_thesaurus;
        global $tbl_Pivot_Thesaurus;
        global $tbl_headers;
        global $db_connection;



         /**----------------------------------------------------------------------
         *  Add a "not available" record for use when the value field isn't used
         ------------------------------------------------------------------------*/

         if ($flg_UNKNOWN_record == false) {
                    
            $flg_UNKNOWN_record = true;

            
            $spec_key = "UNAVAILABLE=fr";
            $spec_value = array("", "non disponible");
            $list_value[$spec_key] = $spec_value;

            $spec_key = "UNAVAILABLE=nl";
            $spec_value = array("", "niet beschikbaar");
            $list_value[$spec_key] = $spec_value;

            $spec_key = "UNAVAILABLE=de";
            $spec_value = array("", "Nicht verfügbar");
            $list_value[$spec_key] = $spec_value;
            
            $spec_key = "UNAVAILABLE=en";
            $spec_value = array("", "not available");
            $list_value[$spec_key] = $spec_value;            

        }



        $xml_specs = $xml_data_full->spec;


        /**------------------------------------------------
         *  Process all the spec nodes
         --------------------------------------------------*/

        foreach ($xml_specs->spec as $spec_l1) {

            Process_Level($spec_l1);

        }



        foreach ($list_value as $key => $value) {

            $key_parts = explode("=", $key);
            fputcsv($fp_thesaurus, array($key_parts[0], $key_parts[1], $value[0], $value[1]));

            $sql_cmd = "INSERT INTO " . $tbl_Pivot_Thesaurus . 
                        " (". 
                        implode(",", array_keys($tbl_headers)) . 
                        ") VALUES ( " .
                        "'" . mb_convert_encoding($key_parts[0], 'Windows-1252', 'UTF-8') . "'," .
                        "'" . mb_convert_encoding($key_parts[1], 'Windows-1252', 'UTF-8') . "'," .
                        "'" . $value[1] . "'," .
                        "'" . $value[0] . "' " .
                        ")";



            try {

                $sql_result = $db_connection->query($sql_cmd);
            } catch (PDOException $e) {
                echo ("Error : " . $e->getMessage() . "SQL Command : \n");
                echo "$sql_cmd\n\n";
            }
    }

}






/**------------------------------------------------
 *  Process a spec. Can be from any level.
 --------------------------------------------------*/

function Process_Level($xml_Spec)
{

    global $fp_thesaurus;
    global $list_value;


    if (substr_count($xml_Spec->attributes()->urn, ":val:") > 0) {

        foreach ($xml_Spec->label as $label) {

            $spec_key = $xml_Spec->attributes()->urn . "=" . $label->attributes()->lang;
            $spec_value = array((string)($xml_Spec->abstract->value), (string)($label->value));
            if (array_key_exists($spec_key, $list_value) == false) {
                $list_value[$xml_Spec->attributes()->urn . "=" . $label->attributes()->lang] = $spec_value;
            }
        }


    }

    // if present, process sub levels   
    foreach ($xml_Spec->spec as $spec) {


        Process_Level($spec);
    }

}

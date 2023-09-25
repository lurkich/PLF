<?php

require_once __DIR__ . "/Parameters.php";

/**
 * 
 * 
 *  read JSON file and create a list with all possible header vallues
 * 
 */
function get_Json_Headers()
{

    $headers = [];



    if (($json = file_get_contents($GLOBALS['json_file_name'])) == false) {

        die('Error reading json file...');
    }

    $payload = json_decode($json, true);


    foreach ($payload[0]['features'] as $x) {


        foreach (array_keys($x) as $level1) {


            if ($level1 == "geometry") {

                $headers[$level1] = true;

                continue;
            }

            if ($level1 == "type") {
                continue;
            }

            foreach (array_keys($x[$level1]) as $level2) {

                $headers[$level2] = true;
            }
        }
    }


    return $headers;
}

/**
 * 
 * 
 *  Drop database table
 * 
 */


function drop_Table(string $table_Name)
{


    $sql_delete = "";

    $db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql_delete = "DROP TABLE $table_Name";

    try {
        $sql_result = $db_conn->query($sql_delete);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\n");
    }



    unset($b_conn);
}


/**
 * 
 * 
 *  create database table
 * 
 */

function Create_Table(string $table_Name, array $tbl_definition)
{



    $sql_Create = "";

    drop_Table($table_Name);

    /**
     * 
     *  Connect to the database
     *  
     */

    // $db_file_name = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Database1.accdb";
    $db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_Create = "CREATE TABLE $table_Name ( ";
    $sql_Create .= " tbl_id AUTOINCREMENT PRIMARY KEY, ";

    foreach ($tbl_definition as $row => $definition) {
        $sql_Create .= "[" . $row . "] ";

        $sql_Create .= " " . $definition . " ";

        $sql_Create .= ", ";
    }

    $sql_Create = substr($sql_Create, 0, strlen($sql_Create) - 2);
    $sql_Create .= ")";


    try {
        $sql_result = $db_conn->query($sql_Create);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\nSQL Command : " . $sql_Create . "\n");
    }


    unset($db_conn);
}









function Duplicate_Table(string $tbl_In, string $tbl_Out)
{

    drop_Table($tbl_Out);

    /**
     * 
     *  Connect to the database
     *  
     */

    // $db_file_name = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Database1.accdb";
    $db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_cmd = "SELECT * INTO $tbl_Out FROM $tbl_In";


    try {
        $sql_result = $db_conn->query($sql_cmd);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\nSQL Command : " . $sql_cmd) . "\n";
    }

    unset($db_conn);
}


function tbl_Add_Column(string $tbl_Name, string $col_name, string $col_type)
{


    $db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




    $sql_cmd = "ALTER TABLE $tbl_Name ADD COLUMN $col_name $col_type";

    try {
        $sql_result = $db_conn->query($sql_cmd);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\n     " . "\nSQL Command : " . $sql_cmd . "\n\n");
    }


    unset($db_conn);
}



function tbl_Drop_Column(string $tbl_Name, string $col_name)
{



    $db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




    $sql_cmd = "ALTER TABLE $tbl_Name DROP COLUMN $col_name";

    try {
        $sql_result = $db_conn->query($sql_cmd);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\n     " . "\nSQL Command : " . $sql_cmd . "\n\n");
    }


    unset($db_conn);
}


function Get_Nomenclature($db_conn, $territory) {


    $tbl_json = $GLOBALS['tbl_json_final'];

    if ($territory == "FL06") {
        $x = 1;
    }

    $sql_cmd = "SELECT DA_Numero FROM tbl_10_json_Final WHERE Territories_id = '" . $territory . "'";

    $tbl_nomenclature[] = "";

    $nomenclature = "";

    $num_rec = 0;

    foreach ($db_conn->query($sql_cmd) as $record_json) {


        if ( $record_json['DA_Numero'] == "" ) {
            continue;
        }

        $num_rec++;
        $tbl_nomenclature[$num_rec - 1] = $record_json['DA_Numero'];
    }





    if ($num_rec == 0) {
        $nomenclature = "Pas de territoire pour cette chasse.";

    } elseif ($num_rec > 1) {

        
        $nomenclature = "Il existe plusieurs nomenclatures différentes pour ce territoire. " . implode(" / ", $tbl_nomenclature);
    
    } else {

            $nomenclature = $tbl_nomenclature[0];
        
    }

    $nomenclature = mb_convert_encoding($nomenclature, 'Windows-1252', 'UTF-8');
    return $nomenclature;
}



function Normalize_Phone_Number($Phone)
{


    $Telephone = trim($Phone);

    if (is_numeric($Telephone)) {
        $phone = (string)$Telephone;
    }



    if ($Telephone != "") {

        $first_slash = strpos($Telephone, "/");

        if ($first_slash > 0) {
            $first_space = strpos($Telephone, " ");
            if ($first_space > 0) {
                $Telephone = str_replace(" ", ".", $Telephone);
            }
        } else {
            $first_space = strpos($Telephone, " ");
            if ($first_space > 0) {
                $Telephone = substr_replace($Telephone, "/", $first_space, strlen(" "));
                $Telephone = str_replace(" ", ".", $Telephone);
            }
        }
    };

    return $Telephone;
}

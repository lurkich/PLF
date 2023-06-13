<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";



// DB Location 

$sql_cmd = "";
$row = "";
$tbl_Chasses = $GLOBALS['tbl_Chasses'];
$tbl_Date_Chasses_Arlon = $GLOBALS['tbl_Chasses_Arlon'];


drop_Table($tbl_Chasses);


/**
 * 
 *  Create table_Chasses
 */


$tbl_Definition_Chasses = [];

$tbl_Definition_Chasses["Date_Chasse"] = "DATETIME";
$tbl_Definition_Chasses["Territory_ID"] = "TEXT (255)";
$tbl_Definition_Chasses["DA_Numero"] = "TEXT (255)";

Create_Table($tbl_Chasses, $tbl_Definition_Chasses);


/**
 * 
 *  Create table_Chasses_Arlon
 */


 $tbl_Definition_Chasses_Arlon = [];

 $tbl_Definition_Chasses_Arlon["Date_Chasse"] = "DATETIME";
 $tbl_Definition_Chasses_Arlon["Territories_ID"] = "TEXT (255)";
 $tbl_Definition_Chasses_Arlon["DA_Numero"] = "TEXT (255)";
 
 Create_Table($tbl_Chasses_Arlon, $tbl_Definition_Chasses_Arlon);



/**
 * 
 *  Connect to the database
 *  
 */

$db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$db_conn_Arlon = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
$db_conn_Arlon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/**
 * 
 *  Process fichier chasse (SQL file).
 * 
 */

$fp_Chasses = fopen($GLOBALS['Fichier_Chasses'], 'r');



while (!feof($fp_Chasses)) {

    $rec = fgets($fp_Chasses);

    $num_match = preg_match("/,\s'/", $rec, $rec_id);

    if ($num_match == 0 ) {
        continue 1;
    }



    $rec = preg_replace("/[\s()\']/", "", $rec);
    $rec_fields = explode(",", $rec);


    /**
     * 
     *  Skip records containing date "0000-00-00"
     */

    if ($rec_fields[1] == "0000-00-00") {
        continue 1;
    }

    $territories_id = explode("|", $rec_fields[3]);


    /**
     * 
     * [0] - recid (skip)
     * [1] - date in format yyyy-mm-dd
     * [2] - date in string format (skip)
     * [3] - empty or territories ID separate by pipe char
     *  */

    if (trim($territories_id[0], " ") == "") {
        continue 1;
    }



    $sql_cmd = "";

    foreach ($territories_id as $territory) {


        $dateInfo = date_parse_from_format('Y-n-j', $rec_fields[1]);
        $dateInfo = $dateInfo['year'] . '-' . $dateInfo['month'] . '-' . $dateInfo['day'];

        $nomenclature = get_nomenclature($db_conn, $territory);


        $sql_cmd = "INSERT INTO  $tbl_Chasses (Date_Chasse, Territory_ID, DA_Numero " .
            ")  VALUES ( " .
            " #$rec_fields[1]#, " .
            " '$territory', " .
            " '$nomenclature' " .
            ")";

        try {
            $sql_result = $db_conn->query($sql_cmd);
        } catch (PDOException $e) {
            echo ("Error : " . $e->getMessage() . "SQL Command : ");
            echo "\n$sql_cmd\n\n";
        }


        if (substr($nomenclature,0,1) == "9") {
            $sql_cmd_Arlon = "INSERT INTO  $tbl_Chasses_Arlon (Date_Chasse, Territories_ID, DA_Numero " .
            ")  VALUES ( " .
            " #$rec_fields[1]#, " .
            " '$territory', " .
            " '$nomenclature' " .
            ")";
        
            try {
                $sql_result = $db_conn_Arlon->query($sql_cmd_Arlon);
            } catch (PDOException $e) {
                echo ("Error : " . $e->getMessage() . "SQL Command : ");
                echo "\n$sql_cmd_Arlon\n\n";
            }
    
        }


    }





}

unset($db_conn);
echo ("\nEnd of process.");


exit;





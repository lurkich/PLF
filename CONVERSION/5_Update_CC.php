<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";


// DB Location 

$sql_cmd = "";
$row = "";
$tbl_In = $GLOBALS["Tbl_Json"];
$tbl_Out = $GLOBALS["Tbl_Update_CC"];



/**
 * 
 *  Duplicate the input table
 */

Duplicate_Table($tbl_In, $tbl_Out);


/**
 * 
 *  Connect to the database
 *  
 */

$db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



/**
 * 
 * Add the Ptr_CC, CC_Error fields
 */

tbl_Add_Column($tbl_Out, "ptr_CC", "INTEGER");
tbl_Add_Column($tbl_Out, "CC_ERROR", "TEXT(255)");



/**
 * 
 * read into memory table conseil cynégétique into array $list_CC_Code
 * 
 */

$list_CC_Code = [];


$sql_cmd = "SELECT CC_ID, Code FROM plf_CC";



foreach ($db_conn->query($sql_cmd) as $record_CC) {

    $list_CC_Code[$record_CC['Code']] = $record_CC['CC_ID'];
}



/**
 * 
 * Process the DB table DB_V1 and update field ptr_CC with record ID of the CC
 * 
 */


$sql_cmd = "SELECT tbl_id, Conseil_name, NOMUGC FROM $tbl_Out";


$sql_update = '';



foreach ($result = $db_conn->query($sql_cmd) as $row) {

    //echo "\nprocessing CC : " . $row['Conseil_name'] . " with ID : " . $row['tbl_id'] . " ---> ";

    $db_Conseil_Index = array_search($row['Conseil_name'], array_keys($list_CC_Code), true);



    if (empty($row['Conseil_name'] == true)) {

        // echo "  ERROR : Conseil_name field is empty.";

        $sql_update = "UPDATE $tbl_Out SET ";
        $sql_update .= " ptr_CC = " . $list_CC_Code['ERROR_EMPTY'] . ", ";
        $sql_update .= " CC_ERROR = 'ERROR : CC field is empty.'";
        $sql_update .= " WHERE tbl_id = " . $row['tbl_id'];
        
    } elseif ($db_Conseil_Index == false) {

        // echo "  ERROR : Conseil_name field NOT FOUND.";

        $sql_update = "UPDATE $tbl_Out SET ";
        $sql_update .= " ptr_CC = " . $list_CC_Code['ERROR_NOT_FOUND'] . ", ";
        $sql_update .= " CC_ERROR = 'ERROR : CC field Not found. => " . $row['Conseil_name'] . " => " . $row['NOMUGC'] . "'";
        $sql_update .= " WHERE tbl_id = " . $row['tbl_id'];
        
    } else {

        // record CC exist
        // echo '  FOUND. CC_ID is ' . $list_CC_Code[$row['Conseil_name']] . "";

        $sql_update = "UPDATE $tbl_Out SET ";
        $sql_update .= " ptr_CC = " . $list_CC_Code[$row['Conseil_name']] . ", ";
        $sql_update .= " CC_ERROR = ''";
        $sql_update .= " WHERE tbl_id = " . $row['tbl_id'];
    }

    try {
        $sql_result = $db_conn->query($sql_update);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\n     " . "SQL Command : " . $sql_update . "\n\n");
    }
}






unset($db_conn);
echo ("\nEnd of process.");

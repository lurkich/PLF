<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";



// DB Location 

$sql_cmd = "";
$row = "";
$tbl_In = $GLOBALS["Tbl_Update_CC"];
$tbl_Out = $GLOBALS["Tbl_Canton_Triages"];



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
 * Add the Ptr_Canton, Canton_Error fields
 */

tbl_Add_Column($tbl_Out, "ptr_Canton", "INTEGER");
tbl_Add_Column($tbl_Out, "Canton_Error", "TEXT(255)");

tbl_Add_Column($tbl_Out, "ptr_Triage", "INTEGER");
tbl_Add_Column($tbl_Out, "Triage_Error", "TEXT(255)");

tbl_Add_Column($tbl_Out, "temp1", "TEXT(255)");
tbl_Add_Column($tbl_Out, "temp2", "TEXT(255)");
tbl_Add_Column($tbl_Out, "temp3", "TEXT(255)");
tbl_Add_Column($tbl_Out, "temp4", "TEXT(255)");
tbl_Add_Column($tbl_Out, "temp5", "TEXT(255)");

/**
 * 
 * read into memory table Canton into array $list_Canton
 * 
 */

$list_Canton = [];


$sql_cmd = "SELECT tbl_id, num_canton FROM tbl_04_Cantonnements";



foreach ($db_conn->query($sql_cmd) as $record_Canton) {
    $list_Canton[$record_Canton['num_canton']] = $record_Canton['tbl_id'];
}




/**
 * 
 * read into memory view Cantons-Triages into array $list_Triage[<num canton><num_triage>]
 * 
 */

$list_Triage = [];


$sql_cmd = "SELECT num_canton, num_triage, tbl_id FROM VCantons_Triages";




foreach ($db_conn->query($sql_cmd) as $record_Canton_Triage) {

    $list_Triage[$record_Canton_Triage["num_canton"]][$record_Canton_Triage["num_triage"]] = $record_Canton_Triage["tbl_id"];
        
}


/**
 * 
 * Read comment field to get triage number.
 * 
 */



 
$sql_cmd = "SELECT tbl_id, DNF_NBER, COMMENTAIR FROM $tbl_Out";

$sql_update = '';


foreach ($result = $db_conn->query($sql_cmd) as $row) {

    
    
    
    $field_Value = $row['COMMENTAIR'];
    $field_Value  = preg_replace('/"/', "", $field_Value);
    $field_Value  = preg_replace("/'/", "''", $field_Value);
    $field_Value  = preg_replace("/;/", ";;", $field_Value);
    
    // $field_Value = "<a href=\"http://example.org\">My Link</a>";

    
    $matches = [];
    $Canton_id = 0;
    $Triage_id = 0;
    $New_COMMENTAIR = "";


    $sql_update = "UPDATE $tbl_Out SET ";
    
    
    $regexp_pattern = "/(Triage\s?(?:de)?[\s]R\Sf\Srence)\s*=\s*(\d*)\s*(.*)/i";
    $xx = preg_match($regexp_pattern, $field_Value, $matches);

    
    if ($xx <> 0) {       
        if (!empty($matches[1])) {        $sql_update .= " temp2 = '" . $matches[1] . "', ";        }
        if (!empty($matches[2])) {        $sql_update .= " temp3 = '" . $matches[2] . "', ";        }
        if (!empty($matches[3])) { 
            $sql_update .= " temp4 = '" . $matches[3] . "', ";        
            $New_COMMENTAIR = $matches[3];
        }
       
    } else {

        $regexp_pattern = "/(TRIAGE)\s+(\d*)\s*(.*)/i";
        $xx = preg_match($regexp_pattern, $field_Value, $matches);
        
        if ($xx <> 0) {           
            if (!empty($matches[1])) {                $sql_update .= " temp2 = '" . $matches[1] . "', ";     }
            if (!empty($matches[2])) {                $sql_update .= " temp3 = '" . $matches[2] . "', ";     }
            if (!empty($matches[3])) { 
                $sql_update .= " temp4 = '" . $matches[3] . "', ";
                $New_COMMENTAIR = $matches[3];     
            }      
        }
    }

    if (!empty($row["DNF_NBER"])) {
        $Canton_id = $list_Canton[$row["DNF_NBER"]];    
        
        if (!empty($matches[2])) {
            
            if (!empty($list_Triage[$row["DNF_NBER"]][intval($matches[2])])) {

                $Triage_id = $list_Triage[$row["DNF_NBER"]][intval($matches[2])];
            
            } else {
                $sql_update .= " Triage_Error = 'Triage du commentaire inexistant.', ";
            }
        }
       
        
    } else {
        $sql_update .= " Canton_Error = 'A compléter', ";
    }

    $sql_update .= " ptr_Canton = " . $Canton_id . ", ";
    $sql_update .= " ptr_Triage = " . $Triage_id . ", ";   
    $sql_update .= " temp1 = '" . $field_Value . "' , ";
    
    $New_COMMENTAIR = preg_replace('/^\s*-\s*/', "", $New_COMMENTAIR);
    $sql_update .= " COMMENTAIR = '" . $New_COMMENTAIR . "' ";     
    
    
    
    if (substr($sql_update, -2) == ", ") {
        $sql_update = substr($sql_update, 0, strlen($sql_update) - 2);
    }
    
    $sql_update .= " WHERE tbl_id = " . $row['tbl_id'];

    try {
        $sql_result = $db_conn->query($sql_update);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\n     " . "SQL Command : " . $sql_update . "\n\n");
    }
    
    
}



unset($db_conn);
echo ("\nEnd of process.");

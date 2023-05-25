<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";





// DB Location 

$sql_cmd = "";
$row = "";
$tbl_In = $GLOBALS["Tbl_Direction_Arlon"];
$tbl_Out = $GLOBALS['tbl_json_final'];




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





tbl_Drop_Column($tbl_Out, 'NOMUGC');
tbl_Drop_Column($tbl_Out, 'Conseil_name');

// tbl_Drop_Column($tbl_Out, 'CC_Error');
// tbl_Drop_Column($tbl_Out, 'Canton_Error');
// tbl_Drop_Column($tbl_Out, 'Triage_Error');
tbl_Drop_Column($tbl_Out, 'City_name');
tbl_Drop_Column($tbl_Out, 'Canton_name');
tbl_Drop_Column($tbl_Out, 'DNF_NBER');
tbl_Drop_Column($tbl_Out, 'DNF_NBER_1');
tbl_Drop_Column($tbl_Out, 'Nomenclature_old');
tbl_Drop_Column($tbl_Out, 'Canton_Error');
tbl_Drop_Column($tbl_Out, 'Triage_Error');
tbl_Drop_Column($tbl_Out, 'CC_Error');

tbl_Drop_Column($tbl_Out, 'DA_Canton');


tbl_Drop_Column($tbl_Out, 'Ter_Area_1');
tbl_Drop_Column($tbl_Out, 'Ter_Long_1');
tbl_Drop_Column($tbl_Out, 'Ter_Area');
tbl_Drop_Column($tbl_Out, 'Ter_Long');

tbl_Drop_Column($tbl_Out, 'OBJECTID');


tbl_Drop_Column($tbl_Out, 'temp1');
tbl_Drop_Column($tbl_Out, 'temp2');
tbl_Drop_Column($tbl_Out, 'temp3');
tbl_Drop_Column($tbl_Out, 'temp4');
tbl_Drop_Column($tbl_Out, 'temp5');


unset($db_conn);
echo ("\nEnd of process.");

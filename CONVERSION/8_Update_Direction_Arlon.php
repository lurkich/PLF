<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";



// DB Location 

$sql_cmd = "";
$row = "";
$tbl_Chasse_Arlon = $GLOBALS["tbl_Out_Territoires_chasse_Direction_Arlon_2023_MW"];
$tbl_In = $GLOBALS["tbl_Canton_Triages"];
$tbl_Out = $GLOBALS["tbl_Direction_Arlon"];



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
 * Add the Chasse Direction Arlon fields
 */

tbl_Add_Column($tbl_Out, "DA_Numero", "TEXT(255)");
tbl_Add_Column($tbl_Out, "DA_Canton", "TEXT(255)");
tbl_Add_Column($tbl_Out, "DA_Nom", "TEXT(255)");

/**
 * 
 * read into memory table Direction Arlon into array $list_Canton
 * 
 */

$list_DA_Arlon = [];


$sql_cmd = "SELECT Canto, Numero, Nom FROM " . $GLOBALS['tbl_Out_Territoires_chasse_Direction_Arlon_2023_MW'];


try {

    foreach ($db_conn->query($sql_cmd) as $record_DA) {

        $list_DA[$record_DA['Numero']] = [$record_DA['Canto'], $record_DA['Nom']];
    }
} catch (PDOException $e) {

    echo ("Error : " . $e->getMessage() . "\n     " . "SQL Command : " . $sql_cmd . "\n\n");
}




/**
 * 
 * For each DA record, remove char 8 and update record territoire
 * 
 */


foreach ($list_DA as $key => $value) {

    $numero = $key;
    $canton = $value[0];
    $nom = $value[1];



    /**
     * 
     * Replace some invalid database interpretation characters in fields with valid ones
     */

    $canton = preg_replace('/""/', '"', $canton);
    $canton = preg_replace("/'/", "''", $canton);
    $canton = preg_replace("/;/", ";;", $canton);
    $canton = preg_replace("/^\"(.*)\"$/", "$1", $canton);

    $nom = preg_replace('/""/', '"', $nom);
    $nom = preg_replace("/'/", "''", $nom);
    $nom = preg_replace("/;/", ";;", $nom);
    $nom = preg_replace("/^\"(.*)\"$/", "$1", $nom);



    $numero_json = substr($numero, 0, 7) . substr($numero, 8, 2);

    Update_Record_Territoire($db_conn, $tbl_Out, $numero_json, $numero, $canton, $nom);
}



unset($db_conn);
echo ("\nEnd of process.");



/**
 * 
 *  Update output table with new fields
 */

function Update_Record_Territoire($db_conn, $tbl_Out, $numero_json, $numero, $canton, $nom)
{

    $sql_update = "UPDATE $tbl_Out SET ";
    $sql_update .= " DA_Numero = '" . $numero . "', ";
    $sql_update .= " DA_Canton = '" . $canton . "', ";
    $sql_update .= " DA_Nom = '" . $nom . "' ";
    $sql_update .= " WHERE Nomenclature = '" . $numero_json . "'";

    try {
        $sql_result = $db_conn->query($sql_update);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\n     " . "SQL Command : " . $sql_update . "\n\n");
    }
}

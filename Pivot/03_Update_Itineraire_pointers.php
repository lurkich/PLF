<?php

require_once __DIR__ . "/../WEB/Functions.php";
require __DIR__ . "/Parameters.php";


/**
 * 
 *  Initialize variables
 * 
 */

$tbl_Pivot_Thesaurus = $GLOBALS['tbl_Pivot_Thesaurus'];
$tbl_Pivot_Itineraire  = $GLOBALS['tbl_Pivot_Itineraire'];

$tbl_languages = array( "fr", "nl" , "de", "en");
$list_distinct_values = array();

/**
 * 
 * 
 * Create a table with the list of fields to change to a pointer.
 *      key = current column name
 *      value = new column name
 * 
 */

$list_Ptr_Fields = [];
$list_Ptr_Fields["visibiliteUrn"] = "ptr_visibiliteUrn";
$list_Ptr_Fields["provinceUrn_1"] = "ptr_provinceUrn_1";
$list_Ptr_Fields["provinceUrn_2"] = "ptr_provinceUrn_2";
$list_Ptr_Fields["paysUrn_1"] = "ptr_paysUrn_1";
$list_Ptr_Fields["urn_fld_etatedit"] = "ptr_urn_fld_etatedit";
$list_Ptr_Fields["urn_fld_catcirc"] = "ptr_urn_fld_catcirc";
$list_Ptr_Fields["urn_fld_reco"] = "ptr_urn_fld_reco";
$list_Ptr_Fields["urn_fld_signal"] = "ptr_urn_fld_signal";
$list_Ptr_Fields["urn_fld_typecirc"] = "ptr_urn_fld_typecirc";
$list_Ptr_Fields["urn_fld_revet_callebotis"] = "ptr_urn_fld_revet_callebotis";
$list_Ptr_Fields["urn_fld_revet_empierre"] = "ptr_urn_fld_revet_empierre";
$list_Ptr_Fields["urn_fld_revet_goudasphpave"] = "ptr_urn_fld_revet_goudasphpave";
$list_Ptr_Fields["urn_fld_revet_gue"] = "ptr_urn_fld_revet_gue";
$list_Ptr_Fields["urn_fld_revet_pature"] = "ptr_urn_fld_revet_pature";
$list_Ptr_Fields["urn_fld_revet_terre"] = "ptr_urn_fld_revet_terre";
$list_Ptr_Fields["urn_fld_revet_autre"] = "ptr_urn_fls_revet_autre";
$list_Ptr_Fields["urn_fld_revet_sentieracc"] = "ptr_urn_fls_revet_sentieracc";
$list_Ptr_Fields["urn_fld_infusgpeddiff"] = "ptr_urn_fld_infusgpeddiff";
$list_Ptr_Fields["urn_fld_infusgvttdiff"] = "ptr_urn_fld_infusgvttdiff";
$list_Ptr_Fields["urn_fld_infusgvtc_a29bdiff"] = "ptr_urn_fld_infusgvtc_a29bdiff";








// Connect to database

$db_connection = PLF::__Open_DB();


if ($db_connection == NULL) {

    $RC = -5;
    $RC_Msg = PLF::Get_Error();

    echo("ERROR - Update_Itineraire_Pointers : " . $RC_Msg);
    return array($RC, $RC_Msg, array());;
}


foreach ($list_Ptr_Fields as $current_column_name => $new_column_name) {


    // make a list of all possible values for the column name

    $list_distinct_values = Make_List_Of_Distinct_Values($current_column_name);

        

    foreach ($tbl_languages as $lang) {

            // delete column
        try {
            PLF::__Delete_Table_Column($tbl_Pivot_Itineraire,$new_column_name . "_" . $lang);  
        } 
        catch (PDOException $e) {

        };
   
        



       
        // create new columns (1 per langage) to hold the pointer.

        PLF::__Add_Table_Column($tbl_Pivot_Itineraire,$new_column_name . "_" . $lang, "INT");



        // for each possible value, add the corresponding pointer.

        foreach ($list_distinct_values as $current_column_value) {

            Update_Pointers($current_column_name,
                            $new_column_name,
                            $current_column_value,
                            $lang);
        
        }

    }


    // delete old column
    // Delete_Table_Column($current_column_name);   
                        














    

}

unset($db_conn);

echo ("End process.");

exit;







/**----------------------------------------------------------
 *  Generate a list of all the possible values for a field
 ------------------------------------------------------------*/

function Make_List_Of_Distinct_Values($current_column_name) {

    global $db_connection;
    global $tbl_Pivot_Itineraire;

    $list_distinct_values = [];



    $sql_cmd = "SELECT DISTINCT " . $current_column_name .  
               " FROM " . $tbl_Pivot_Itineraire;

    foreach ($db_connection->query($sql_cmd) as $record) {

        $column_value = $record[$current_column_name];

        if (empty($record[$current_column_name]) == true) {
            $column_value = "UNAVAILABLE";
        }

        array_push($list_distinct_values, $column_value);
            
    }

    return $list_distinct_values;

}







/**------------------------------------------------
 *  Fill in the pointers
 --------------------------------------------------*/

 function Update_Pointers($current_column_name,
                          $new_column_name, 
                          $current_column_value,
                          $lang) {

    // get the thesaurus data

    global $db_connection;
    global $tbl_Pivot_Itineraire;


    $ptr_value = Get_Ptr_Ids_For_Field_Value( $current_column_value, $lang );

    if (empty($ptr_value)) {
        $ptr_value = Get_Ptr_Ids_For_Field_Value( $current_column_value, "fr");
    }


    $column_value = $current_column_value;
    if ($column_value == "UNAVAILABLE") { 
        $column_value = "";
    }

    $sql_cmd = "UPDATE " . $tbl_Pivot_Itineraire . 
               " SET " . $new_column_name . "_" . $lang  . 
               " = " . $ptr_value . 
               " WHERE " . $current_column_name . " = '" . $column_value . "'" ;

    try {
            $sql_result = $db_connection->query($sql_cmd);
        } 
    catch (PDOException $e) {
            echo ("Error : " . $e->getMessage() . "SQL Update Command : ");
            echo "\n$sql_cmd\n\n";
    }

}












/**------------------------------------------------
 *  Get pointer value corresponding to field value
 --------------------------------------------------*/

function Get_Ptr_Ids_For_Field_Value($current_column_value,
                                     $lang) {

    global $db_connection;
    global $tbl_Pivot_Thesaurus;


    $sql_cmd = "SELECT tbl_id " .  
               " FROM " . $tbl_Pivot_Thesaurus . 
               " WHERE urn_name = '" . $current_column_value . "' AND " . 
                       "lang = '" . $lang . "' ";

    foreach ($db_connection->query($sql_cmd) as $record) {



        return $record["tbl_id"];
 
    }


}








// /**----------------------------------------------------------
//  *  Add a new column named <fieldName "-" wlanguage>
//  ------------------------------------------------------------*/

//  function Add_Table_Column($new_name, $lang) {

//     global $tbl_Pivot_Itineraire;

//     tbl_Add_Column($tbl_Pivot_Itineraire, $new_name . "_" . $lang, "LONG INTEGER");

// }








// /**----------------------------------------------------------
//  * delete the old column
//  ------------------------------------------------------------*/

//  function Delete_Table_Column($current_name) {

//     global $tbl_Pivot_Itineraire;

//     tbl_Drop_Column($tbl_Pivot_Itineraire, $current_name);

// }





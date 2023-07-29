<?php

require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";



// DB Location 

$sql_cmd = "";
$row = "";
$tbl_In_Arlon_Territories = $GLOBALS['tbl_json_final'];
$tbl_plf_territories = $GLOBALS["tbl_plf_territories"];

$tbl_In_Arlon_Cantonnements = $GLOBALS['tbl_Cantonnements'];
$tbl_plf_Cantonnement = $GLOBALS["tbl_plf_Cantonnement"];

$tbl_In_Arlon_Chasses = $GLOBALS['tbl_Chasses'];
$tbl_plf_Chasses = $GLOBALS["tbl_plf_Chasses"];


$tbl_In_Arlon_Triages = $GLOBALS['tbl_Triages'];
$tbl_plf_Triages = $GLOBALS["tbl_plf_Triages"];

$tbl_CC = $GLOBALS["tbl_CC"];

$view_Arlon_Cantons_Triages = $GLOBALS["view_plf_Cantons_Triages"];


/**
 * 
 *  Duplicate the input table
 */

Duplicate_Table($tbl_In_Arlon_Territories, $tbl_plf_territories);
Duplicate_Table($tbl_In_Arlon_Cantonnements, $tbl_plf_Cantonnement);
Duplicate_Table($tbl_In_Arlon_Chasses, $tbl_plf_Chasses);
Duplicate_Table($tbl_In_Arlon_Triages, $tbl_plf_Triages);
Duplicate_Table($tbl_CC, $tbl_plf_CC);

/**
 * 
 *  Connect to the database
 *  
 */

$db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


/**
 * 
 * read into memory table Direction Arlon into array $list_Canton
 * 
 */

$list_DA_Arlon = [];


$sql_cmd = "SELECT tbl_Id, DA_Numero FROM " . $tbl_plf_territories;



foreach ($db_conn->query($sql_cmd) as $record) {
    
    if ($record['DA_Numero'] == "") {

        Delete_Record($db_conn, $tbl_plf_territories, $record['tbl_Id']);

    }

}

$sql_cmd = "SELECT tbl_Id, DA_Numero FROM " . $tbl_plf_Chasses;



foreach ($db_conn->query($sql_cmd) as $record) {
    
    if (substr($record['DA_Numero'],0,1) <> "9") {

        Delete_Record($db_conn, $tbl_plf_Chasses, $record['tbl_Id']);

    }

}










/**
 * 
 *  Create view view_Arlon_Cantons_Triages
 * 
 */


 $sql_cmd = "DROP TABLE $view_Arlon_Cantons_Triages";

 try {
     $sql_result = $db_conn->query($sql_cmd);
 } catch (Exception $e) {
 }


 $sql_cmd = "
     CREATE VIEW view_Cantons_Triages AS 
     SELECT plf_cantonnements.tbl_id AS Canton_tbl_id,
     plf_cantonnements.num_canton AS num_canton,
     plf_cantonnements.nom AS nom_canton,
     plf_cantonnements.tel AS tel_canton,
     plf_cantonnements.direction AS direction_canton,
     plf_cantonnements.email AS email_canton,
     plf_cantonnements.attache AS attache_canton,
     plf_cantonnements.CP AS CP_canton,
     plf_cantonnements.localite AS localite_canton,
     plf_cantonnements.rue AS rue_canton,
     plf_cantonnements.numero AS numero_canton,
     plf_cantonnements.localisation AS locatlisation_canton,
     plf_Triages.tbl_id AS tbl_id,
     plf_Triages.num_triage AS num_triage,
     plf_Triages.nom AS nom_triage,
     plf_Triages.nom_Prepose AS nom_Prepose,
     plf_Triages.gsm_Prepose AS gsm_Prepose 
     FROM (plf_Triages INNER JOIN plf_cantonnements ON((plf_Triages.Ptr_Canton = plf_cantonnements.tbl_id)));
     ";



 try {
     $sql_result = $db_conn->query($sql_cmd);
 } catch (Exception $e) {
     echo ("Error : " . $e->getMessage() . "SQL Command : ");
     echo "sql_Create_View_Cantons_Triages\n\n";
 }









/**
 * 
 *  Create view view_territoires
 * 
 */


 $sql_cmd = "DROP TABLE $view_plf_territoires";

 try {
     $sql_result = $db_conn->query($sql_cmd);
 } catch (Exception $e) {
    $x = 1;
 }


 $sql_cmd = "
    CREATE VIEW view_territoires AS 
    select `plf_territoires`.`tbl_id` AS `tbl_id`,
    `plf_territoires`.`geometry` AS `geometry`,
    `plf_territoires`.`Territories_id` AS `Territories_id`,
    `plf_territoires`.`Nomenclature` AS `Nomenclature`,
    `plf_territoires`.`DA_Numero` AS `DA_Numero`,
    `plf_territoires`.`Territories_name` AS `Territories_name`,
    `plf_territoires`.`DA_Nom` AS `DA_Nom`,
    `plf_territoires`.`SAISON` AS `SAISON`,
    `plf_territoires`.`Holder_fullname` AS `Holder_fullname`,
    `plf_territoires`.`TITULAIRE_` AS `TITULAIRE_`,
    `plf_territoires`.`NOM_TITULA` AS `NOM_TITULA`,
    `plf_territoires`.`PRENOM_TIT` AS `PRENOM_TIT`,
    `plf_territoires`.`TITULAIRE1` AS `TITULAIRE1`,
    `plf_territoires`.`COMMENTAIR` AS `COMMENTAIR`,
    `plf_territoires`.`DATE_MAJ` AS `DATE_MAJ`,
    `plf_territoires`.`ESRI_OID` AS `ESRI_OID`,
    `plf_territoires`.`Type_1` AS `Type_1`,
    `plf_territoires`.`id` AS `id`,
    `plf_territoires`.`Member_CC` AS `Member_CC`,
    `plf_territoires`.`ptr_CC` AS `ptr_CC`,
    `plf_CC`.`Code` AS `Code_CC`,
    `plf_CC`.`Nom` AS `Nom_CC`,
    `plf_CC`.`President` AS `President_CC`,
    `plf_CC`.`Secretaire` AS `Secretaire_CC`,
    `plf_CC`.`email` AS `email_CC`,
    `plf_CC`.`CP` AS `CP_CC`,
    `plf_CC`.`localite` AS `localite_CC`,
    `plf_CC`.`rue` AS `rue_CC`,
    `plf_CC`.`numero` AS `numero_CC`,
    `plf_CC`.`localisation` AS `localisation_CC`,
    `plf_CC`.`site_internet` AS `site_internet_CC`,
    `plf_CC`.`logo` AS `logo_CC`,
    `plf_territoires`.`ptr_Canton` AS `ptr_Canton`,
    `plf_cantonnements`.`num_canton` AS `num_canton`,
    `plf_cantonnements`.`nom` AS `nom_canton`,
    `plf_cantonnements`.`tel` AS `tel_canton`,
    `plf_cantonnements`.`direction` AS `direction_canton`,
    `plf_cantonnements`.`email` AS `email_canton`,
    `plf_cantonnements`.`attache` AS `attache_canton`,
    `plf_cantonnements`.`CP` AS `CP_canton`,
    `plf_cantonnements`.`localite` AS `localite_canton`,
    `plf_cantonnements`.`rue` AS `rue_canton`,
    `plf_cantonnements`.`numero` AS `numero_canton`,
    `plf_cantonnements`.`localisation` AS `localisation_canton`,
    `plf_territoires`.`ptr_Triage` AS `ptr_Triage`,
    `plf_triages`.`num_triage` AS `num_triage`,
    `plf_triages`.`nom` AS `nom_triage`,
    `plf_triages`.`nom_Prepose` AS `nom_Prepose`,
    `plf_triages`.`gsm_Prepose` AS `gsm_Prepose` 
    from (((`plf_territoires` left join `plf_CC` on((`plf_territoires`.`ptr_CC` = `plf_CC`.`tbl_ID`))) 
    left join `plf_cantonnements` on((`plf_territoires`.`ptr_Canton` = `plf_cantonnements`.`tbl_id`))) 
    left join `plf_triages` on((`plf_territoires`.`ptr_Triage` = `plf_triages`.`tbl_id`)));
    ";

    try {
        $sql_result = $db_conn->query($sql_cmd);
    } catch (Exception $e) {
        echo ("Error : " . $e->getMessage() . "SQL Command : ");
        echo "sql_Create_View_territoires\n\n";
    }










unset($db_conn);
echo ("\nEnd of process.");




function Delete_Record($db_conn, $Table, $Tbl_Id)
    {

        // Build SQL statement


        $sql_Delete = "DELETE FROM $Table WHERE " .
            " tbl_id = $Tbl_Id" ;



        // Execute SQL statement

        try {
            $sql_result = $db_conn->query($sql_Delete);
        } catch (Exception $e) {
            echo ("Error : " . $e->getMessage() . "SQL Command : ");
            echo "$sql_Delete\n\n";
            return false;
        }

        return true;

    }

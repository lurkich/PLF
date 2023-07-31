<?php
declare(strict_types=1);


require_once __DIR__ . "/../vendor/autoload.php";



// header("Content-type: application/json; charset=UTF-8");
header("Content-type: text/plain; charset=UTF-8");


spl_autoload_register(function ($class_name) {
    include "library/" . $class_name . '.php';
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

/**
 * 
 * Global Parameters
 * 
 */

 
 $duplicate_Chasses_Records = 0;
 $duplicate_Territoires_Records = 0;

 $total_Chasses_Records = 0;
 $total_Territoires_Records = 0;

//   ---> Database information and list of tables and views


 $MySql_Server = "localhost";
 $MySql_DB = "plf";
 $MySql_Login = "lurkich";
 $MySql_Password = "Chri12!!";



 $tbl_cantonnements = "plf_cantonnements";
 $tbl_CC = "plf_cc";
 $tbl_triages = "plf_triages";
 $tbl_Territoires = "plf_territoires";
 $tbl_Chasses = "plf_chasses";

 $View_Territoires = "view_territoires";

 $spw_tbl_cantonnements = "plf_spw_cantonnements";
 $spw_tbl_cc = "plf_spw_cc";
 $spw_tbl_territoires = "plf_spw_territoires";
 $spw_chasses_fermeture = "plf_spw_chasses_fermeture";
 $spw_view_territoires = "view_spw_territoires";


/**
 * 
 *  SPW territories and chasse (ARCGIS)
 * 
 * 
 */


// rest url information. 
$spw_URL = "https://geoservices3.test.wallonie.be/arcgis/rest/services";
$spw_Folder = "APP_DNFEXT";
$spw_Service = "CHASSE_DATEFERM";
$spw_MapServer_Constant = "MapServer";
$spw_Index_Territoire = "1";
$spw_Index_Chasse_Fermeture_OK = "0";
$spw_Index_Chasse = "2";


// json output file.
$spw_Territoires_Json_File = "API/tmp/spw_Territoires";
$spw_Chasses_Json_File = "API/tmp/spw_Chasses";





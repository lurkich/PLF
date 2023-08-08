<?php
declare(strict_types=1);

require_once __DIR__ . "/vendor/autoload.php";


// determine to file path of the .env file depending is call is done through batch or http

if ( empty($_SERVER["DOCUMENT_ROOT"]) == true ) {
    $env_location = __DIR__ . "/../../../../../";
} else {
    $env_location = $_SERVER["DOCUMENT_ROOT"] . "/../";
}




// load .env values

$dotenv = Dotenv\Dotenv::createImmutable($env_location);
$dotenv->load();


// Autload the API/library classes 


spl_autoload_register(function ($class_name) {
    include $_ENV["SERVER_PHP_File_Path"] . "/API/library/" . $class_name . '.php';
});



// header("Content-type: application/json; charset=UTF-8");
header("Content-type: text/plain; charset=UTF-8");


// define the error and exception handler routines

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");


 
//   ---> Database information and list of tables and views



 $tbl_cantonnements = "plf_cantonnements";
 $tbl_CC = "plf_cc";
 $tbl_triages = "plf_triages";
 $tbl_Territoires = "plf_territoires";
 $tbl_Chasses = "plf_chasses";

 $View_Territoires = "view_territoires";

 $spw_tbl_cantonnements = "plf_spw_cantonnements";
 $spw_tbl_cc = "plf_spw_cc";
 $spw_tbl_territoires = "plf_spw_territoires";
 $spw_tbl_territoires_1 = "plf_spw_territoires_1";
 $spw_chasses_1 = "plf_spw_chasses_1";
 $spw_chasses_fermeture = "plf_spw_chasses_fermeture";
 $spw_view_territoires = "view_spw_territoires";

 $cgt_itineraires = "plf_cgt_itineraires";


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
$spw_Territoires_Json_File = __DIR__ ."/API/tmp/spw_Territoires";
$spw_Chasses_Json_File = __DIR__ . "/API/tmp/spw_Chasses";



/**
 * 
 *  CGT itineraires (PIVOT)
 * 
 * 
 */

// rest url information. 
$cgt_URL = "https://pivotweb.tourismewallonie.be/PivotWeb-3.1/query/";

 // json output file.
$cgt_Itineraires_Json_File = __DIR__ ."/API/tmp/cgt_Itineraires.json";
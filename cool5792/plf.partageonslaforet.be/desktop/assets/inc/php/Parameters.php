<?php
declare(strict_types=1);

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/Functions.php";

$debug = false;


// determine to file path of the .env file depending is call is done through batch or http

if ( empty($_SERVER["DOCUMENT_ROOT"]) == true ) {
    $env_location = __DIR__ . "/../../../../../";
} else {
    $env_location = $_SERVER["DOCUMENT_ROOT"] . "/../";
}


set_time_limit(0);

ini_set('mysql.connect_timeout','0');   
ini_set('mysqli.connect_timeout','0'); 
ini_set('max_execution_time', '7200');   


// load .env values

$dotenv = Dotenv\Dotenv::createImmutable($env_location);
$dotenv->load();


// Autload the API/library classes 



spl_autoload_register(function ($class_name) {
    include $_ENV["SERVER_PHP_File_Path"] . "/API/library/" . $class_name . '.php';
});


// header("Content-type: text/plain; charset=UTF-8");


// define the error and exception handler routines

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");


date_default_timezone_set("Europe/Brussels");

 
//   ---> Database information and list of tables and views

$spw_cantonnements = "plf_spw_cantonnements";
$spw_cantonnements_tmp = "plf_spw_cantonnements_tmp";

$spw_tbl_territoires = "plf_spw_territoires";
$spw_tbl_territoires_tmp = "plf_spw_territoires_tmp";

$spw_chasses = "plf_spw_chasses";
$spw_chasses_tmp = "plf_spw_chasses_tmp";
$spw_cc = "plf_spw_cc";
$spw_cc_tmp = "plf_spw_cc_tmp";

$spw_view_territoires = "view_spw_territoires";
$spw_view_cantonnements = "view_spw_cantonnements";
$spw_view_cc = "view_spw_cc";

$cgt_itineraires = "plf_cgt_itineraires";
$cgt_itineraires_tmp = "plf_cgt_itineraires_tmp";

$plf_infos = "plf_infos";



/**
 * 
 *  SPW territories and chasse (ARCGIS)
 * 
 */


// rest url information. -> Chasses et Territoires
//$spw_URL = "https://geoservices3.test.wallonie.be/arcgis/rest/services";
$spw_URL = "https://geoservices3.wallonie.be/arcgis/rest/services";
$spw_Folder = "APP_DNFEXT";
$spw_Service = "CHASSE_DATEFERM";
$spw_MapServer_Constant = "MapServer";
$spw_Index_Territoire = "1";
$spw_Index_Chasse_Fermeture_OK = "0";
$spw_Index_Chasse = "2";

// rest url information. - Conseil Cantonnements
$spw_Cantonnement_URL = "https://geoservices.wallonie.be/arcgis/rest/services";
$spw_Cantonnement_Folder = "FAUNE_FLORE";
$spw_Cantonnement_Service = "LIMITES_DNF";
$spw_Cantonnement_MapServer_Constant = "MapServer";
$spw_Cantonnement_Index_Cantonnement = "1";

// rest url information. - Conseil cynégétique
$spw_CC_URL = "https://geoservices.wallonie.be/arcgis/rest/services";
$spw_CC_Folder = "FAUNE_FLORE";
$spw_CC_Service = "CONS_CYN";
$spw_CC_MapServer_Constant = "MapServer";
$spw_CC_Index_CC = "0";


// json output file.
$spw_Territoires_Json_File = __DIR__ ."/API/tmp/spw_Territoires";
$spw_Chasses_Json_File = __DIR__ . "/API/tmp/spw_Chasses";
$spw_CC_Json_File = __DIR__ . "/API/tmp/spw_CC";
$spw_Cantonnement_Json_File = __DIR__ . "/API/tmp/spw_Cantonnement";



/**
 * 
 *  CGT itineraires (PIVOT)
 * 
 */

// rest url information. 
$cgt_URL = "https://pivotweb.tourismewallonie.be/PivotWeb-3.1/query/";

 // json output file.
$cgt_Itineraires_Json_File = __DIR__ ."/API/tmp/cgt_Itineraires.json";




<?php
/**
*  DEBUGGING : XDEBUG_SESSION=thunder
*/





require_once __DIR__ . "/Parameters.php";

$requestUri = $_SERVER["REQUEST_URI"];
$requestUri = preg_replace("/(\?)*XDEBUG_SESSION=thunder/", "",$requestUri);

$parts = explode("/",$requestUri);

// change all parts names to uppercase (this does the trick)
$parts = array_flip($parts);
$parts = array_change_key_case($parts,CASE_UPPER);
$parts = array_flip($parts);

/**
 * 
 * possible values for parts array :
 * 
 * 
 *  0 = ""
 *  1 = "api"
 *  2 = "spw"
 *  3 = "chasses"
 *  4 = "1"
 *
 *  0 = ""
 *  1 = "api"
 *  2 = "spw"
 *  3 = "chasses"
 *  4 = "2"
 *
 *  0 = ""
 *  1 = "api"
 *  2 = "spw"
 *  3 = "territoires"
 *
 *  0 = ""
 *  1 = "api"
 *  2 = "pivot"
 *  3 = "itineraires"
 * 
 */


if ($parts[1] != "API") {

    // http_response_code(404);
}





switch ($parts[2]) {
    
    case "PIVOT":       // -> api/pivot/itineraires

        switch ($parts[3]) {
            case "ITINERAIRES":

                echo json_encode([
                    "0" => "", 
                    "1" => "api",
                    "2" => "pivot",
                    "3" => "itineraires"
                ]);

                $database = new Database($GLOBALS["MySql_Server"], $GLOBALS["MySql_DB"], $GLOBALS["MySql_Login"], $GLOBALS["MySql_Password"]);
                $gateway = new Pivot_ItinerairesGateway($database);
                $controller = new Pivot_ItinerairesController($gateway);            
                $controller->processRequest($_SERVER["REQUEST_METHOD"], "15");
                
                break;

            default:
                http_response_code(404);
        }

        break;



    case "SPW":
    
        switch ($parts[3]) {
    
            case "CHASSES":                  
            
                if ($parts[4] == 1) {           // -> api/spw/chasse/1
                   
                    echo json_encode([
                        "0" => "",
                        "1" => "api",
                        "2" => "spw",
                        "3" => "chasses",
                        "4" => "1"]);

                $database = new Database($GLOBALS["MySql_Server"], $GLOBALS["MySql_DB"], $GLOBALS["MySql_Login"], $GLOBALS["MySql_Password"]);
                $gateway = new SPW_Chasses_1_Gateway($database);
                $controller = new SPW_Chasses_1_Controller($gateway);            
                $controller->processRequest($_SERVER["REQUEST_METHOD"], "15");


                } elseif ($parts[4] == 2) {     // -> api/spw/chasse/2
                    echo "(warning) : " . implode([
                        "0" => "", 
                        "1" => "api",
                        "2" => "spw",
                        "3" => "chasses",
                        "4" => "2"]) . PHP_EOL; 
                    // echo ([
                    //     "0" => "", 
                    //     "1" => "api",
                    //     "2" => "spw",
                    //     "3" => "chasses",
                    //     "4" => "2"]);

                    $database = new Database($GLOBALS["MySql_Server"], $GLOBALS["MySql_DB"], $GLOBALS["MySql_Login"], $GLOBALS["MySql_Password"]);
                    $gateway = new SPW_Chasses_Fermeture_OK_Gateway($database);
                    $controller = new SPW_Chasses_Fermeture_OK_Controller($gateway);            
                    $controller->processRequest($_SERVER["REQUEST_METHOD"], "15");


                } else {
                    http_response_code(404);
                }
                
                break;


            case "TERRITOIRES":                 // // -> api/spw/territoires
                
                echo json_encode([
                    "0" => "",
                    "1" => "api",
                    "2" => "spw",
                    "3" => "territoires"]);
                
                $database = new Database($GLOBALS["MySql_Server"], $GLOBALS["MySql_DB"], $GLOBALS["MySql_Login"], $GLOBALS["MySql_Password"]);
                $gateway = new SPW_Territoires_Gateway($database);
                $controller = new SPW_Territoires_Controller($gateway);            
                $controller->processRequest($_SERVER["REQUEST_METHOD"], "15");
                break;


            default:
                http_response_code(404);
                break;
        }

        break;



    default:
        http_response_code(404);
        break;

    }


exit;

// $id = $parts[2] ?? null;





$gateway = new ProductGateway($database);

$controller = new ProductController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
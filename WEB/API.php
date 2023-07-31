<?php
/**
*  DEBUGGING : XDEBUG_SESSION=thunder
*/


use PHPMailer\PHPMailer\PHPMailer;


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

                    // echo "(warning) : " . implode([
                    //     "0" => "", 
                    //     "1" => "api",
                    //     "2" => "spw",
                    //     "3" => "chasses",
                    //     "4" => "2"]) . PHP_EOL; 


                    $database = new Database($GLOBALS["MySql_Server"], $GLOBALS["MySql_DB"], $GLOBALS["MySql_Login"], $GLOBALS["MySql_Password"]);
                    $gateway = new SPW_Chasses_Fermeture_OK_Gateway($database);                    
                    $controller = new SPW_Chasses_Fermeture_OK_Controller($gateway);    
                    array_push(errorHandler::$Run_Information, ["Info", "calling URI : api/spw/chasses/2" . PHP_EOL]); 
                    $controller->processRequest();
                    Send_Run_logs_By_eMail();

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


function Send_Run_logs_By_eMail(): void {



    require_once __DIR__ . "../../vendor/autoload.php";
    
    $plf_mail = new PHPMailer();
    $plf_mail->From = "Christian.lurkin@hotmail.com";
    $plf_mail->FromName = "Christian Lurkin PLF";
    $plf_mail->addAddress("christian.lurkin@gmail.com");
    $plf_mail->addReplyTo("Christian.lurkin@hotmail.com");
    $plf_mail->isHTML(true);
    $plf_mail->Subject = "PLF logging";

    $plf_mail->AltBody = "Run Log for spw API call.";



    $plf_mail->Body = "<br><i>Run Log for spw API call.</i> - run of " .date("d/m/Y H:i:s") . "<br><br>";

    foreach (errorHandler::$Run_Information as $run_item) {

        $run_item[1] = preg_replace("/\n/", "<br>", $run_item[1]);
        
        $plf_mail->Body .= "(<b>" . $run_item[0] . "</b>) - " . $run_item[1];

    }

    if ( !$plf_mail->send()) {
        echo "Mailer Error: " . $plf_mail->ErrorInfo;
    } else {
        echo "message successfully sent.";
    }


}
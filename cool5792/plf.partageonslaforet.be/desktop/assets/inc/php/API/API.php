<?php
/**
*  DEBUGGING : XDEBUG_SESSION=thunder
*/

$Print_Mail_header = "";
$Print_Mail_Footer = "";

use PHPMailer\PHPMailer\PHPMailer;


require_once __DIR__ . "/../Parameters.php";

$requestUri = $_SERVER["REQUEST_URI"];
$requestUri = preg_replace("/(\?)*XDEBUG_SESSION=thunder/", "",$requestUri);


$Url_cleaned = preg_replace("/.*\/API.php/i", "", $requestUri);

$parts = explode("/",$Url_cleaned);

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
 *  2 = "cgt"
 *  3 = "itineraires"
 * 
 */


if ($parts[1] != "API") {

    http_response_code(404);
    exit;
}





switch ($parts[2]) {
    
    case "CGT":       // -> api/cgt/itineraires

        switch ($parts[3]) {
            case "ITINERAIRES":

                echo json_encode([
                    "0" => "", 
                    "1" => "api",
                    "2" => "cgt",
                    "3" => "itineraires"
                ]);
                $Print_Mail_header = "<br><i>Run Log for CGT Itineraires API call.</i> - run of " .date("d/m/Y H:i:s") . "<br><br>";        
                $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );
                $gateway = new CGT_Itineraires_Gateway($database);
                $controller = new CGT_Itineraires_Controller($gateway);            
                array_push(errorHandler::$Run_Information, ["Info", "calling URI : api/cgt/itineraires" . PHP_EOL]); 
                $controller->processRequest();
                $Print_Mail_Footer = "<br><br><i>END Run Log for CGT itineraires API call.</i> - run of " . date("d/m/Y H:i:s") . "<br><br>";
                Send_Run_logs_By_eMail();
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
                
                
                $Print_Mail_header = "<br><i>Run Log for SPW Chasses (1) API call.</i> - run of " .date("d/m/Y H:i:s") . "<br><br>";    
                $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );
                $gateway = new SPW_Chasses_1_Gateway($database);
                $controller = new SPW_Chasses_1_Controller($gateway);            
                $controller->processRequest($_SERVER["REQUEST_METHOD"], "15");
                $Print_Mail_Footer = "<br><br><i>END Run Log for SWP Chasses (1) API call.</i> - run of " . date("d/m/Y H:i:s") . "<br><br>";
                Send_Run_logs_By_eMail();

                } elseif ($parts[4] == 2) {     // -> api/spw/chasse/2

                    // echo "(warning) : " . implode([
                    //     "0" => "", 
                    //     "1" => "api",
                    //     "2" => "spw",
                    //     "3" => "chasses",
                    //     "4" => "2"]) . PHP_EOL; 

                    $Print_Mail_header = "<br><i>Run Log for SPW Territoires/Chasses (2) API call.</i> - run of " .date("d/m/Y H:i:s") . "<br><br>";    
                    $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );
                    $gateway = new SPW_Chasses_Fermeture_OK_Gateway($database);                    
                    $controller = new SPW_Chasses_Fermeture_OK_Controller($gateway);    
                    array_push(errorHandler::$Run_Information, ["Info", "calling URI : api/spw/chasses/2" . PHP_EOL]); 
                    $controller->processRequest();
                    $Print_Mail_Footer = "<br><br><i>END Run Log for SPW Territoires/Chasses (2) API call.</i> - run of " . date("d/m/Y H:i:s") . "<br><br>";
                    Send_Run_logs_By_eMail();
                    break;

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

            
                $Print_Mail_header = "<br><i>Run Log for SPW Territoires API call.</i> - run of " .date("d/m/Y H:i:s") . "<br><br>";                    
                $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );
                $gateway = new SPW_Territoires_Gateway($database);
                $controller = new SPW_Territoires_Controller($gateway);            
                $controller->processRequest($_SERVER["REQUEST_METHOD"], "15");
                $Print_Mail_Footer = "<br><br><i>END Run Log for SPW Territoires API call.</i> - run of " . date("d/m/Y H:i:s") . "<br><br>";                
                Send_Run_logs_By_eMail();
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

    global $Print_Mail_header;
    global $Print_Mail_Footer;


    require_once __DIR__ . "../../vendor/autoload.php";
    
    $plf_mail = new PHPMailer();
    $plf_mail->From = "Christian.lurkin@hotmail.com";
    $plf_mail->FromName = "Christian Lurkin PLF";
    $plf_mail->addAddress("christian.lurkin@gmail.com");
    $plf_mail->addReplyTo("Christian.lurkin@hotmail.com");
    $plf_mail->isHTML(true);
    $plf_mail->Subject = "PLF logging";

    $plf_mail->AltBody = "Run Log for spw API call.";



    $plf_mail->Body = $Print_Mail_header;

    foreach (errorHandler::$Run_Information as $run_item) {

        $run_item[1] = preg_replace("/\n/", "<br>", $run_item[1]);
        
        $plf_mail->Body .= "(<b>" . $run_item[0] . "</b>) - " . $run_item[1];

    }

    $plf_mail->Body .= $Print_Mail_Footer;

    if ( !$plf_mail->send()) {
        echo "Mailer Error: " . $plf_mail->ErrorInfo;
    } else {
        echo "message successfully sent.";
    }


}
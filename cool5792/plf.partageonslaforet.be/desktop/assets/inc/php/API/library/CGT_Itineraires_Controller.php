<?php


class CGT_Itineraires_Controller
{


    private string $_Rest_Url;
    private string $_Query_Parameters;
    public static int $_Total_Itineraires;
    public static int $_Duplicate_Itineraires;
    public static DateTime $_Run_Time;




    public function __construct(private CGT_Itineraires_Gateway $gateway)
    {

        $this->_Rest_Url = "";

        ErrorHandler::$Run_Information = [];

        $this->_Query_Parameters = "OTH-A0-009F-5MSN";
        $this->_Query_Parameters .= ";content=3";
        $this->_Query_Parameters .= ";info=true";
        $this->_Query_Parameters .= ";infolvl=0";
        self::$_Total_Itineraires = 0;
        self::$_Duplicate_Itineraires = 0;

    }
        
    public static function __Increment_Total_Itineraires(): void {
        self::$_Total_Itineraires++ ;
    }

    public static function __Increment_Duplicate_Itineraires(): void {
        self::$_Duplicate_Itineraires++ ;
    }




    public function processRequest(): void
    {
           
        $this->Prepare_Web_Service_URL();

        $RC = $this->Get_Json_Data_Into_Files();

        if ($RC == false) { return;}

        $this->gateway->Drop_Table($GLOBALS["cgt_itineraires_tmp"]);
        
        $this->gateway->Create_DB_Table_Itineraires($GLOBALS["cgt_itineraires_tmp"]);

        $this->Process_Json_Files();

        $this->gateway->Drop_Table($GLOBALS["cgt_itineraires"]);

        $this->gateway->Rename_Table($GLOBALS["cgt_itineraires_tmp"], $GLOBALS["cgt_itineraires"]);

        array_push(errorHandler::$Run_Information, ["Info", "" . PHP_EOL]);
        array_push(errorHandler::$Run_Information, ["Info", self::$_Duplicate_Itineraires . " duplicate itineraires records." . PHP_EOL]);
        array_push(errorHandler::$Run_Information, ["Info", self::$_Total_Itineraires . " new itineraires." . PHP_EOL]);
        array_push(errorHandler::$Run_Information, ["Info", "End of process."]);


    }





    /**=======================================================================
     * 
     * Format the web Service URL without the query itself.
     * 
     *   ARGUMENTS :
     * 
     *   INPUT : https://pivotweb.tourismewallonie.be/PivotWeb-3.1/query/OTH-A0-009F-5MSN;content=3;info=true
     * 
     *   OUTPUT :  $Rest_Url (containing the common fields of the web Service URL)
     * 
     * =======================================================================*/

    private function Prepare_Web_Service_URL(): void
    {
        
        $this->_Rest_Url = $GLOBALS['cgt_URL'];

    }


    /**=======================================================================
     * 
     * Retrieve the JSON information from the SPF Web Site and save chunks if files
     * 
     *   ARGUMENTS : URI curl query string
     * 
     *   INPUT :
     * 
     *   OUTPUT : json file(s)
     * 
     * =======================================================================*/
    private function Get_Json_Data_Into_Files(): Bool
    { 
    

            $json_file = $GLOBALS['cgt_Itineraires_Json_File'];

            try  {
                unlink($json_file);             // delete file if it exists
            } catch (Exception $e) {

            }
            
            $fp = fopen($json_file, "w");   // create file for writing
    
       
    
            $curl_Url = $this->_Rest_Url . $this->_Query_Parameters;
    

            array_push(errorHandler::$Run_Information, ["Info", "Retrieving CGT API itineraires " . PHP_EOL]);
    
    
            $Curl = curl_init();
    
            $http_header = array(
                "Content-Type: application/json", 
                "Accept: application/json", 
                "ws_key: cd8680b9-43c8-4faf-a6a8-d9574e2470e3"
            );

            curl_setopt($Curl, CURLOPT_HEADER, 0);
            curl_setopt($Curl, CURLOPT_URL, $curl_Url);
            curl_setopt($Curl, CURLOPT_FILE, $fp);
            curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 300);
            curl_setopt($Curl, CURLOPT_VERBOSE, true);
            curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($Curl, CURLOPT_HTTPHEADER, $http_header);
    
            $RC_Bool = curl_exec($Curl);
            $headers = curl_getinfo($Curl);

            fclose($fp);

            switch ($headers["http_code"]) {
                case 200: 
                    break;
                case 503:
                    array_push(errorHandler::$Run_Information, ["CRITICAL", "CGT service unavailable : http_code = " . $headers["http_code"] . " Calling URL = " . $headers["url"] . PHP_EOL]);
                    return false;
                case 404:
                    array_push(errorHandler::$Run_Information, ["CRITICAL", "CGT resource page not found : http_code = " . $headers["http_code"] . " Calling URL = " . $headers["url"] . PHP_EOL]);
                    return false;
                default:
                    array_push(errorHandler::$Run_Information, ["CRITICAL", "CGT service call error : http_code = " . $headers["http_code"] . " Calling URL = " . $headers["url"] . PHP_EOL]);
                    return false;
                
            }
       
        return true;
    }

 
    /**=======================================================================
     * 
     * Retrieve the JSON information from the SPF Web Site.
     * 
     *   ARGUMENTS :
     * 
     *   INPUT : json files created in previous step
     * 
     *   OUTPUT : MySql table updated
     * 
     * =======================================================================*/

    private function Process_Json_Files(): void {


        $json_file = $GLOBALS['cgt_Itineraires_Json_File'];

        $itineraires = JsonMachine\Items::fromFile($json_file, ['pointer' => '/offre']);

        foreach ($itineraires as $itineraire) {

            $itineraire = json_decode(json_encode($itineraire), true);

            $DB_Fields = array();

            $DB_Fields["nom"] = $itineraire["nom"];
            $DB_Fields["localite"] = $itineraire["adresse1"]["localite"][0]["value"];
            $DB_Fields["organisme"] = $itineraire["adresse1"]["organisme"]["label"];


            $relOffre = $itineraire["relOffre"];

            if (empty($relOffre)) {
                $DB_Fields["gpx_url"] = "";                
            } else {
                $DB_Fields["gpx_url"] = $this->Get_GPX_Url($relOffre);    
            }




            foreach ($itineraire["spec"] as $spec ) {

                
                switch ($spec["urn"]) {

                    case "urn:fld:urlweb":
                        $DB_Fields["urlweb"] = $spec["value"];
                        break;

                    case "urn:fld:idreco":
                        $DB_Fields["idreco"] = $spec["value"];
                        break;

                    case "urn:fld:typecirc":
                        $DB_Fields["typecirc"] = $spec["valueLabel"][0]["value"];
                        break;

                    case "urn:fld:signal":
                        $DB_Fields["signal"] = $spec["valueLabel"][0]["value"];
                        break;

                    case "urn:fld:dist":
                        $DB_Fields["distance"] = $spec["value"];
                        break;

                    case "urn:fld:hdifmin":
                        $DB_Fields["hdifmin"] = $spec["value"];
                        break;

                    case "urn:fld:hdifmax":
                        $DB_Fields["hdifmax"] = $spec["value"];
                        break;
                    
                    default:

                    }                             
            }



            $this->gateway->New_Itineraire($DB_Fields);

            
            

        }
    }
    
    private function Get_GPX_Url(array $relOffre): string  {

        foreach ($relOffre as $key => $spec) {

            if ($spec["urn"] != "urn:lnk:media:autre") {
                continue;
            }

            foreach ($spec["offre"]["spec"] as $media_spec ) {

                if ($media_spec["urn"] == "urn:fld:url") {
                    if ( strtoupper(substr($media_spec["value"],-4)) == strtoupper(".gpx")) {
                        return $media_spec["value"];
                    }
                }


            }
   
    
        }

        return "";
    
    }



}


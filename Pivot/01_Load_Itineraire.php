<?php


require_once __DIR__ . "/../WEB/Functions.php";
require __DIR__ . "/Parameters.php";



/**
 * 
 *  Initialize variables
 * 
 */
$list_value = [];
$xml_file_name_offres = $GLOBALS['xml_file_name_offres'];
$csv_file_name_offres = $GLOBALS['csv_file_name_thesaurus'];
$tbl_headers = [];
$rec_out = [];

$tbl_Pivot_Itineraire = $GLOBALS['tbl_Pivot_Itineraire'];



$EOL_patterns[0] = "/\r\n/";
$EOL_patterns[1] = "/\r/";
$EOL_patterns[2] = "/\n/";






/**
 *  open output csv file
 */

$fp_offre_csv = fopen($csv_file_name_offres, 'w');



/**------------------------------------------------
 *  FIRST PASS : get all the headers
 --------------------------------------------------*/


$pass = 1;

// Initialize xmlReader variable

$xml = new XMLReader();
$xml->open($xml_file_name_offres);

// skip all nodes before first "offre"
while ($xml->read() && $xml->name != "offre") {
}

// process only offre nodes
while ($xml->name == "offre") {

    $offre = new SimpleXMLElement($xml->readOuterXml());

    Process_Visibilite($pass, $offre->visibiliteUrn);
    process_nom($pass, $offre->nom);
    process_adresse($pass, $offre->adresse1, "1");
    process_adresse($pass, $offre->adresse2, "2");
    foreach ($offre->spec as $spec) { Process_spec($pass, $spec);  }

    // go to next offre

    $xml->next('offre');

}

$xml->close();
unset($xml);



// Create table itineraire and compose a record template.

Process_Headers();






/**------------------------------------------------
 *  SECOND PASS : get all the data
 --------------------------------------------------*/

$pass = 2;
$count = 0;



// Connect to database

$db_connection = PLF::__Open_DB();


if ($db_connection == NULL) {

    $RC = -5;
    $RC_Msg = PLF::Get_Error();

    echo("ERROR - Load_Itineraire : " . $RC_Msg);
    return array($RC, $RC_Msg, array());;
}

// Initialize xmlReader

$xml = new XMLReader();
$xml->open($xml_file_name_offres);



// skip all nodes before first "offre"

while ($xml->read() && $xml->name != "offre") {
}


// process only offre nodes

while ($xml->name == "offre") {

    $count++;

    print_r("processing offre : " . $count . "\n\n");


    // read offre

    $offre = new SimpleXMLElement($xml->readOuterXml());

    print_r("Offre a traiter : " . Remove_Tabs($offre->nom) . "\n");
    //echo ($count . " element name is : " . Remove_Tabs($offre->nom) . "\n");



     //Count the number of fields in the offer

    $p_cnt = count($offre->spec);
    //echo ("count number of specs for offre : " . $p_cnt . "\n");



    // reinitialize output record
    empty_rec_out();


    Process_Visibilite($pass, $offre->visibiliteUrn);
    process_nom($pass, $offre->nom);
    process_adresse($pass, $offre->adresse1, "1");
    process_adresse($pass, $offre->adresse2, "2");

    foreach ($offre->spec as $spec) { Process_spec($pass, $spec); }

    write_Offre();

    fputcsv($fp_offre_csv, array_values($rec_out), "$");


    // go to next offre

    $xml->next('offre');
    
}

unset($db_connection);
unset($xml);



/**
 * 
 * 
 *  Process tag "Visibilite". Value is urn:val
 * 
 */

function Process_Visibilite($pass, SimpleXMLElement $visibiliteUrn)
{


    global $rec_out;


    if ($pass == 1) {
        Update_tbl_headers("visibiliteUrn","string");
        return;
    }


    $visibiliteUrn = Remove_Tabs($visibiliteUrn->attributes()->urn);

    $rec_out["visibiliteUrn"] =  $visibiliteUrn;

    //echo ("Visibilite : " . $visibiliteUrn  . "\n");
}




/**
 * 
 * 
 *  Process tag "nom"
 * 
 */

function Process_Nom($pass, SimpleXMLElement $nom)
{

    global $rec_out;

    if ($pass == 1) {

        Update_tbl_headers("nom","");
        return;
    
    }

    $nom = Remove_Tabs($nom);

    $nom = preg_replace('/\n/', '<br>', $nom);
    // $nom = mb_convert_encoding($nom, 'Windows-1252', 'UTF-8');


    $rec_out["nom"] =  $nom;

    // echo "nom : " . $nom . "\n";
}








/**
 * 
 * 
 *  Process tag "adresse1 and adresse2". Some fields contains urn:val values
 * 
 */


function Process_Adresse($pass, SimpleXMLElement $address, $adressId)
{

    global $rec_out;


    if ($pass == 1) {
        Update_tbl_headers("rue_" . $adressId, "string");
        Update_tbl_headers("numero_" . $adressId, "string");
        Update_tbl_headers("cp_" . $adressId, "string");
        Update_tbl_headers("provinceUrn_" . $adressId, "string");
        Update_tbl_headers("paysUrn_" . $adressId, "string");
        Update_tbl_headers("lieuPrecis_" . $adressId, "string");
        Update_tbl_headers("lambertX_" . $adressId, "string");
        Update_tbl_headers("lambertY_" . $adressId, "string");
        Update_tbl_headers("latitude_" . $adressId, "string");
        Update_tbl_headers("longitude_" . $adressId, "string");
        Update_tbl_headers("altitude_" . $adressId, "string");
        Update_tbl_headers("parcNaturel_" . $adressId, "string");
        Update_tbl_headers("organisme_" . $adressId, "string");
    }

    if ($pass == 2) {

        // echo ("\nProcessing Adresse" . $adressId . "\n");
        // $rue =  mb_convert_encoding(Remove_Tabs($address->rue), 'Windows-1252', 'UTF-8');
        $rue =  Remove_Tabs($address->rue);
        $numero = Remove_Tabs($address->numero);
        $cp = Remove_Tabs($address->cp);
        $provinceUrn = Remove_Tabs($address->provinceUrn->attributes()->urn);       // urn:val
        $paysUrn = Remove_Tabs($address->paysUrn->attributes()->urn);               // urn:val
        // $lieuPrecis = mb_convert_encoding(Remove_Tabs($address->lieuPrecis), 'Windows-1252', 'UTF-8');
        $lieuPrecis = Remove_Tabs($address->lieuPrecis);
        $lambertX = Remove_Tabs($address->lambertX);
        $lambertY = Remove_Tabs($address->lambertY);
        $latitude = Remove_Tabs($address->latitude);
        $longitude = Remove_Tabs($address->longitude);
        $altitude = Remove_Tabs($address->altitude);
        $parcNaturel = Remove_Tabs($address->parcNaturel->label);
        $organisme = Remove_Tabs($address->parcNaturel->label);
    
    
        /**
         * 
         * 
         *  save each value in the associative array which key is the name of the variable itself
         * 
         */
    
    
    
        $list_vars = Get_Defined_vars();
    
        $rec_out["rue_" . $adressId] =  $rue;
        $rec_out["numero_" . $adressId] =  $numero;
        $rec_out["cp_" . $adressId] =  $cp;
        $rec_out["provinceUrn_" . $adressId] =  $provinceUrn;
        $rec_out["paysUrn_" . $adressId] =  $paysUrn;
        $rec_out["lieuPrecis_" . $adressId] =  $lieuPrecis;
        $rec_out["lambertX_" . $adressId] =  $lambertX;
        $rec_out["lambertY_" . $adressId] =  $lambertY;
        $rec_out["latitude_" . $adressId] =  $latitude;
        $rec_out["longitude_" . $adressId] =  $longitude;
        $rec_out["altitude_" . $adressId] =  $altitude;
        $rec_out["parcNaturel_" . $adressId] =  $parcNaturel;
        $rec_out["organisme_" . $adressId] =  $organisme;
    
    
    
        // echo ("  rue : " . $rue . "\n");
        // echo ("  numero : " . $numero . "\n");
        // echo ("  cp : " . $cp . "\n");
        // echo ("  provinceUrn : " . $provinceUrn . "\n");           // urn:val
        // echo ("  paysUrn : " . $paysUrn . "\n");                   // urn:val
        // echo ("  lieuPrecis : " . $lieuPrecis . "\n");
        // echo ("  lambertX : " . $lambertX . "\n");
        // echo ("  lambertY : " . $lambertY . "\n");
        // echo ("  latitude : " . $latitude . "\n");
        // echo ("  longitude : " . $longitude . "\n");
        // echo ("  altitude : " . $altitude . "\n");
        // echo ("  parcNaturel : " . $parcNaturel . "\n");
        // echo ("  organisme : " . $organisme . "\n");
    
        
    }

    // localite has 1 entry/langage




    foreach ($address->localite as $field) {


        $localite = Remove_Tabs($field->value);
        $lang = (string)$field->attributes()->lang;

        $csv_key = "localite" . "_" .  $lang . "_" . $adressId;


        if ($pass == 1) {
            Update_tbl_headers($csv_key, "string");
            continue;
        }




        $rec_out[$csv_key] =  Remove_Tabs($field->value);
        // echo ("  localite " . $lang . " - " . $localite . "\n");
    }





    // commune has 1 entry/langage
    foreach ($address->commune as $field) {

        $commune = Remove_Tabs($field->value);
        $lang = (string)$field->attributes()->lang;

        $csv_key = "commune" . "_" .  $lang . "_" . $adressId;


        if ($pass == 1) {
            Update_tbl_headers($csv_key, "string");
            continue;
        }


        $rec_out[$csv_key] =  Remove_Tabs($field->value) ;

        // echo ("  commune " . $lang . " - " . $commune . "\n");
    }
}


/**
 * 
 * 
 *  process each specification and take only the ones we need
 * 
 */

function Process_Spec($pass, SimpleXMLElement $spec)
{

    global $tbl_headers;
    global $rec_out;
    global $EOL_patterns;


    $spec_urn = (string)$spec->attributes()->urn;
    $spec_type = $spec->type;
    $spec_value = Remove_Tabs($spec->value);





    // skip unwanted fields

    if (
        //preg_match("/urn:.*descmarket10.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*descmarket30.*/i", $spec_urn) > 0 || 
        //preg_match("/urn:.*homepage.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*nomofr.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*idreco.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*typecirc.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*catcirc.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*reco.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*signal.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*urlweb.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*dist.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*envtrav.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*revet.*/i", $spec_urn) > 0 ||
        //preg_match("/urn:.*etatedit.*/i", $spec_urn) > 0 ||
        
        //preg_match("/urn:.*infus.*/i", $spec_urn) > 0 ||  
        //preg_match("/urn:.*cirkwi.*/i", $spec_urn) > 0 ||
        preg_match("/urn:.*filtwebxxxxx.*/i", $spec_urn) > 0     
        

        )
    {

        return;
    }


    /**
     * 
     * 
     *  Some urn do not have the "fr" langage in front (e.a. : nomofr, signalnrec, ....) 
     *      to avoid exception, add "fr:" in front of the urn.
     * 
     */


    if (
        preg_match("/^urn:.*signalnrec$/i", $spec_urn) > 0 ||
        preg_match("/^urn:.*descmarket10$/i", $spec_urn) > 0 ||
        preg_match("/^urn:.*descmarket$/i", $spec_urn) > 0
    ) {
        $spec_urn = "fr:" . $spec_urn;
    }

    if (substr_count($spec_value, ":val:") > 0) {

        if ($pass == 1) {
            Update_tbl_headers($spec_urn, $spec_type );
            return;
        }

        $spec_urn = csv_key_transform_with_underscore($spec_urn);
        $rec_out[$spec_urn] =  $spec_value;
        //echo ("Spec " . $spec_urn . " is a value : " . $spec_value . "\n");
        return;
    }




    if (substr($spec_urn, 0, 3) <> "urn") {



        if ($pass == 1) {
            Update_tbl_headers($spec_urn, $spec_type);
            return;
        }

            // if the value is in html format, change all new lines by <br>
    if ($spec->type == "TextML" || 
        $spec->type == "FirstUpperStringML") {

        $spec_value = preg_replace('/\n/', '<br>', $spec_value);
        // $spec_value = mb_convert_encoding($spec_value, 'Windows-1252', 'UTF-8');
    }

        $spec_urn = csv_key_transform_with_underscore($spec_urn);
        $rec_out[$spec_urn] =  $spec_value;
        // echo ("multi language spec. ===> ");
        // echo ("    Spec : " . $spec_urn . " subcat : " . $spec_subcategory  . " value : " . $spec_value . "\n");

    } else {


        if ($pass == 1) {
            Update_tbl_headers($spec_urn, $spec_type);
            return;
        }

        $spec_urn = csv_key_transform_with_underscore($spec_urn);
        $rec_out[$spec_urn] =  $spec_value;
        // echo ("Spec : " . $spec_urn . " subcat : " . $spec_subcategory . " value : " . $spec_value . "\n");
    }
}



/**
 * 
 * 
 *  All headers are processed for all offres. Fill in the template record.
 * 
 */

function Process_Headers()
{

    global $tbl_headers;
    global $fp_offre_csv;

    global $rec_out;
    global $tbl_Pivot_Itineraire;


    foreach ($tbl_headers as $key => $value) {

        $rec_out[$key] = "";

    }


    PLF::__Create_Table($tbl_Pivot_Itineraire, $tbl_headers);
    echo("DEBUG : creating ttable\n");

    fputcsv($fp_offre_csv, array_keys($tbl_headers), "$");


}



function csv_key_transform_with_underscore($csv_key){

    return preg_replace("/:/", "_", $csv_key,);

}




function Remove_Tabs($xmlString)
{

    return trim(preg_replace('/\t+/', '', $xmlString));
}


/**
 * 
 * 
 *  Update tbl_headers. Each key must be a unique value
 * 
 */


function Update_tbl_headers($key, $type)
{

    global $tbl_headers;
    $field_type = "TEXT";

    switch (strtolower($type)) {

        case strtolower("choice"):
            $field_type = "TEXT";
            break;

        case strtolower("string"):
            $field_type = "TEXT";
            break;

        case strtolower("StringML"):
            $field_type = "TEXT";
            break;

        case strtolower("FirstUpperStringML"):
            $field_type = "TEXT";
            break;

        case strtolower("TextML"):
            $field_type = "TEXT";
            break;

        case strtolower("URL"):
            $field_type = "TEXT";
            break;

        case strtolower("URLAccessi"):
            $field_type = "TEXT";
            break;

        case strtolower("urlFacebook"):
            $field_type = "TEXT";
            break;

        case strtolower("urlInstagram"):
            $field_type = "TEXT";
            break;

        case strtolower("urlYoutube"):
            $field_type = "TEXT";
            break;

        case strtolower("urlflickr"):
            $field_type = "TEXT";
            break;

        case strtolower("Boolean"):
            $field_type = "BIT";
            break;

        case strtolower("Coords"):
            $field_type = "FLOAT";
            break;

        case strtolower("UFloat"):
            $field_type = "FLOAT";
            break;

        case strtolower("SFloat"):
                $field_type = "FLOAT";
            break;

        case strtolower("Value"):
            $field_type = "TEXT";
            break;

        case strtolower("Duration"):           
            $field_type = "TIME";
            break;
        
    }



    $key = csv_key_transform_with_underscore($key);

    if (array_key_exists($key, $tbl_headers) == false) {


        $tbl_headers[$key] = $field_type;
        if ($key == "nom" || 
            preg_match("/.*descmarket.*/i", $key) ||
            preg_match("/.*desccirc.*/i", $key)
            ) {
            $tbl_headers[$key] = "LONGTEXT";
        }

    }
}





function write_Offre() {

    global $tbl_headers;
    global $rec_out;
    global $tbl_Pivot_Itineraire;
    global $db_connection;

    $sql_cmd = "";


    $sql_field_list = implode(", ",array_keys($tbl_headers));

    $sql_field_values = "";

    foreach ($tbl_headers as $key => $value) {


        $rec_out[$key] = preg_replace('/""/', '"', $rec_out[$key]);
        $rec_out[$key] = preg_replace("/'/", "''", $rec_out[$key]);
        $rec_out[$key] = preg_replace("/;/", ";;", $rec_out[$key]);
        $rec_out[$key] = preg_replace("/^\"(.*)\"$/", "$1", $rec_out[$key]);
        $rec_out[$key] = preg_replace('/"/', "", $rec_out[$key]);
        $rec_out[$key] = preg_replace("/'/", "''", $rec_out[$key]);
        $rec_out[$key] = preg_replace("/;/", ";;", $rec_out[$key]);
        $rec_out[$key] = preg_replace('/\x3f\xae/', 'é', $rec_out[$key]);
        $rec_out[$key] = preg_replace('/\x3f\xac/', 'ê', $rec_out[$key]);
        $rec_out[$key] = preg_replace('/\x3f\xba/', 'ç', $rec_out[$key]);
        $rec_out[$key] = preg_replace('/\x3f\xbf/', 'è', $rec_out[$key]);
        $rec_out[$key] = preg_replace('/\x3f\x3f/', 'û', $rec_out[$key]);


        $insert_value = $rec_out[$key];

        switch (strtolower($tbl_headers[$key])) {

            case strtolower("TEXT"):
                $insert_value = "'" . $insert_value . "'";
                // $insert_value = mb_convert_encoding($rec_out[$key], 'Windows-1252', 'UTF-8');
                break;


            case strtolower("BIT"):
                if (empty($insert_value)) {
                    $insert_value = "false";
                }
                break;



            case strtolower("FLOAT"):
                if (is_numeric($insert_value) == false) {
                    $insert_value = -1;
                }
                break;


            case strtolower("TIME"):
                if (empty($insert_value)) {
                    $insert_value = "0:0";
                }
                $array_time_items = explode(":",$insert_value);

                $insert_value = "'" . $array_time_items[0] . ":" . $array_time_items[1] . "'";              

                break;



            default:
                $insert_value = "'" . $insert_value. "'";
        }


        $sql_field_values .=  $insert_value . ",";

    }





    $sql_field_values = substr($sql_field_values, 0, strlen($sql_field_values) - 1);


    echo("DEBUG : executing SQL command\n");
    $sql_cmd = "INSERT INTO $tbl_Pivot_Itineraire ($sql_field_list) VALUES ($sql_field_values)";


    try {

        $sql_result = $db_connection->query($sql_cmd);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "SQL Command : \n");
        echo "$sql_cmd\n\n";
        $RC_Msg =  'Error Create Table' . " - ";
        $RC_Msg .= $e->getMessage() . " - ";
        $RC_Msg .= $sql_cmd. "\n";
    }
    catch (mysqli_sql_exception $e) {
        $RC_Msg =  'Error Create Table' . " - ";
        $RC_Msg .= $e->getMessage() . " - ";
        $RC_Msg .= $sql_cmd . "\n";
        echo("sql_cmd : " . $sql_cmd . "\n");
    }




}





function empty_rec_out()
{

    global $tbl_headers;
    global $rec_out;

    foreach ($tbl_headers as $key => $value) {

        $rec_out[$key] = "";
    }
}









/**
 * 
 * 
 * Get the name of the variable (will be the key of the array) $csv_out associative array
 * 
 */

function Get_Variable_Name($list_Vars, $var)
{


    foreach ($list_Vars as $myvar => $myvar_value) {

        if ($myvar_value === $var) {

            return $myvar;
        }
    }
}



/**
 * @param $xml
 * @return array
 * https://hotexamples.com/examples/-/-/simplexml_to_array/php-simplexml_to_array-function-examples.html
 */

function simplexmlToArray($xml)
{
    $ar = array();
    foreach ($xml->children() as $k => $v) {
        $child = simplexmlToArray($v);
        if (count($child) == 0) {
            $child = (string) $v;
        }
        foreach ($v->attributes() as $ak => $av) {
            if (!is_array($child)) {
                $child = array("value" => $child);
            }
            $child[$ak] = (string) $av;
        }
        if (!array_key_exists($k, $ar)) {
            $ar[$k] = $child;
        } else {
            if (!is_string($ar[$k]) && isset($ar[$k][0])) {
                $ar[$k][] = $child;
            } else {
                $ar[$k] = array($ar[$k]);
                $ar[$k][] = $child;
            }
        }
    }
    return $ar;
}

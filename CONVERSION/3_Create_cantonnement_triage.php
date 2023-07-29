<?php


require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";





$file_in_cantonnements = fopen($GLOBALS['file_name_in_cantonnements'],'r') or die("unable to open file" . $GLOBALS['file_name_in_cantonnements'] . "for reading. ");
$file_out_cantonnements = fopen($GLOBALS['file_name_out_cantonnements'], 'w') or die("unable to open file" . $GLOBALS['file_name_out_cantonnements'] . " for writing. ");
$file_out_triages = fopen($GLOBALS['file_name_out_triages'], 'w') or die("unable to open file" . $GLOBALS['file_name_out_triages'] . " for writing. ");


$Record_ID_Canton = 0;
$Record_ID_Triage = 0;

$tbl_cantons = [];
$tbl_triages = [];
$tbl_out = [];

// header('Content-Type: text/plain;charset=ascii');

while(! feof($file_in_cantonnements)) {


    
    // read records including title. and remove all double quotes

    $rec = fgets($file_in_cantonnements);
 
    // echo mb_convert_encoding($rec, "UTF-8");

    // echo mb_detect_encoding($rec) . "\n";


    // print headers
    
    if (str_contains($rec,"OBJECTID")){
        $tbl_cantons = array(
            "ID" => "ID",
            "Num_Canton" => "Num_Canton",
            "Nom" => "Nom",
            "TEL" => "TEL",
            "direction" => "Direction",
            "email" => "email",
            "attache" => "Attache",
            "CP" => "CP",
            "localite" => "localite",
            "rue" => "rue",
            "numero" => "numero",
            "altitude" => "altitude",
            "longitude" => "longitude"
        );

        fwrite($file_out_cantonnements, join(";", $tbl_cantons) . "\n");

        $tbl_triages = array(
            "ID" => "ID",
            "Num_Triage" => "Num_Triage",
            "Nom" => "Nom",
            "Nom_Prepose" => "Nom_Prepose",
            "GSM_Prepose" => "GSM_Prepose",
            "Ptr_Canton" => "Ptr_Canton",
        );
        
        fwrite($file_out_triages, join(";", $tbl_triages) . "\n");  
        
        continue;
    }
    
    
    // process other records !! EOL for each record contains \r\n. Remove it
    
    
    
    $rec = str_replace("\r\n", "", $rec);   
    
    if ($rec == "") continue;
       
        
    $array_items = explode(";", $rec);
    
    
    $array_items[0] = str_replace("\"", "", $array_items[0]);
    $array_items[1] = str_replace("\"", "", $array_items[1]);
    $array_items[2] = str_replace("\"", "", $array_items[2]);
    $array_items[3] = str_replace("\"", "", $array_items[3]);
    $array_items[4] = str_replace("\"", "", $array_items[4]);
    $array_items[5] = str_replace("\"", "", $array_items[5]);
    $array_items[6] = str_replace("\"", "", $array_items[6]);
    $array_items[7] = str_replace("\"", "", $array_items[7]);
    $array_items[8] = str_replace("\"", "", $array_items[8]);
    $array_items[9] = str_replace("\"", "", $array_items[9]);
    $array_items[10] = str_replace("\"", "", $array_items[10]);
    $array_items[11] = str_replace("\"", "", $array_items[11]);
    $array_items[12] = str_replace("\"", "", $array_items[12]);
    $array_items[13] = str_replace("\"", "", $array_items[13]);
    $array_items[14] = str_replace("\"", "", $array_items[14]);
    $array_items[15] = str_replace("\"", "", $array_items[15]);
    $array_items[16] = str_replace("\"", "", $array_items[16]);


    // normalize phone numbers
    
    $array_items[5] = Normalize_Phone_Number($array_items[5]);
    $array_items[7] = Normalize_Phone_Number($array_items[7]);

    
    
    // If canton information are not yet saved, write the record.

   
    if (!isset($tbl_cantons["Num_Canton"]) || $tbl_cantons["Num_Canton"] != $array_items[2] ) {

       
        $Record_ID_Canton++;
        
        $tbl_cantons = array(
            "ID" => mb_convert_encoding($Record_ID_Canton, 'Windows-1252', 'UTF-8'),
            "Num_Canton" => mb_convert_encoding($array_items[2], 'Windows-1252', 'UTF-8'),
            "Nom" =>  mb_convert_encoding($array_items[6], 'Windows-1252', 'UTF-8'),
            "TEL" =>  mb_convert_encoding($array_items[7], 'Windows-1252', 'UTF-8'),
            "direction" =>  mb_convert_encoding($array_items[8] , 'Windows-1252', 'UTF-8'),
            "email" =>  mb_convert_encoding($array_items[9] , 'Windows-1252', 'UTF-8'),
            "attache" =>  mb_convert_encoding($array_items[10], 'Windows-1252', 'UTF-8'),
            "CP" =>  mb_convert_encoding($array_items[11] , 'Windows-1252', 'UTF-8'),
            "localite" =>  mb_convert_encoding($array_items[12], 'Windows-1252', 'UTF-8'),
            "rue" =>  mb_convert_encoding($array_items[13], 'Windows-1252', 'UTF-8'),
            "numero" =>  mb_convert_encoding($array_items[14], 'Windows-1252', 'UTF-8'),
            "latitude" =>  mb_convert_encoding($array_items[15], 'Windows-1252', 'UTF-8'),
            "longitude" =>  mb_convert_encoding($array_items[16], 'Windows-1252', 'UTF-8'),
        );

        
        fwrite($file_out_cantonnements, join(";",$tbl_cantons) . "\n");
    }

    




    // write triage record
    $Record_ID_Triage++;
    
    $tbl_triages = array (
        "ID" => $Record_ID_Triage,
        "Num_Triage" => $array_items[1],
        "Nom" => Correct_Field($array_items[3]),
        "Nom_Prepose" => Correct_Field($array_items[4]),
        "GSM_Prepose" => Correct_Field($array_items[5]),
        "Ptr_Canton" => $Record_ID_Canton
        );
    fwrite($file_out_triages, join(";", $tbl_triages) . "\n");    
    
}


fclose($file_in_cantonnements);
fclose($file_out_cantonnements);
fclose($file_out_triages);

echo "Enf of process.";


function Correct_Field($field) {


    $field  = preg_replace('/"/', "", $field);
    $field  = preg_replace("/'/", "''", $field);
    $field  = preg_replace("/;/", ";;", $field);
    $field = preg_replace('/\x3f\xae/', 'é', $field);
    $field = preg_replace('/\x3f\xac/', 'ê', $field);
    $field = preg_replace('/\x3f\xba/', 'ç', $field);
    $field = preg_replace('/\x3f\xbf/', 'è', $field);
    $field = preg_replace('/\x3f\x3f/', 'û', $field);
    // $field = preg_replace('/\xe7/', "\xc3\xa7", $field);
    mb_convert_encoding($field, 'Windows-1252', 'UTF-8');


    return $field;
}
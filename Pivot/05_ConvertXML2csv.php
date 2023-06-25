<?php

require __DIR__ . "/Parameters.php";
// require __DIR__ . "/functions.php";

// $EOL_patterns[0] = "/\r\n/";
// $EOL_patterns[1] = "/\r/";
// $EOL_patterns[2] = "/\n/";


// if (($XML = file_get_contents($GLOBALS['xml_file_name'])) == false)
//     die('Error reading XML file...');


/**
 * 
 *    Define the fields that will be saved to the csv file
 */

$tbl_headers["nom"] = "nom";
// $tbl_headers["estActive"] = "Active";   ==============> deprecated
$tbl_headers["estActiveUrn:urn"] = "estActiveUrn";
$tbl_headers["estActiveUrn:fr"] = "estActiveUrn_fr";
$tbl_headers["estActiveUrn:nl"] = "estActiveUrn_nl";
$tbl_headers["estActiveUrn:en"] = "estActiveUrn_en";
$tbl_headers["estActiveUrn:de"] = "estActiveUrn_de";


// typeOffre
$tbl_headers["typeOffre:idTypeOffre"] = "typeOffre";
$tbl_headers["typeOffre:fr"] = "typeOffre_fr";
$tbl_headers["typeOffre:nl"] = "typeOffre_nl";
$tbl_headers["typeOffre:en"] = "typeOffre_en";
$tbl_headers["typeOffre:de"] = "typeOffre_de";

// // visibilite
// $tbl_headers["fr:urn:val:visibilite:reg"] = "reg_fr";
// $tbl_headers["nl:urn:val:visibilite:reg"] = "reg_nl";
// $tbl_headers["en:urn:val:visibilite:reg"] = "reg_en";
// $tbl_headers["de:urn:val:visibilite:reg"] = "reg_de";



// adresse 1

$tbl_headers["adresse1:rue"] = "adresse1_rue";
$tbl_headers["adresse1:numero"] = "adresse1_numero";
$tbl_headers["adresse1:boite"] = "adresse1_boite";

$tbl_headers["adresse1:idIns"] = "adresse1_idIns";

$tbl_headers["adresse1:ins"] = "adresse1_ins";

$tbl_headers["adresse1:cp"] = "adresse1_cp";

$tbl_headers["adresse1:localite:fr"] = "adresse1_localite_fr";
$tbl_headers["adresse1:localite:nl"] = "adresse1_localite_nl";
$tbl_headers["adresse1:localite:en"] = "adresse1_localite_en";
$tbl_headers["adresse1:localite:de"] = "adresse1_localite_de";

$tbl_headers["adresse1:commune:fr"] = "adresse1_commune_fr";
$tbl_headers["adresse1:commune:nl"] = "adresse1_commune_nl";
$tbl_headers["adresse1:commune:en"] = "adresse1_commune_en";
$tbl_headers["adresse1:commune:de"] = "adresse1_commune_de";

$tbl_headers["adresse1:lieuPrecis"] = "adresse1_lieuPrecis";


// $tbl_headers["adresse1:province"] = "adresse1_province";   ==============> deprecated
$tbl_headers["adresse1:provinceUrn:urn"] = "adresse1_provinceUrn_urn";
$tbl_headers["adresse1:provinceUrn:fr"] = "adresse1_provinceUrn_fr";
$tbl_headers["adresse1:provinceUrn:nl"] = "adresse1_provinceUrn_nl";
$tbl_headers["adresse1:provinceUrn:en"] = "adresse1_provinceUrn_en";
$tbl_headers["adresse1:provinceUrn:de"] = "adresse1_provinceUrn_de";

// $tbl_headers["adresse1:pays"] = "adresse1_pays";   ==============> deprecated
$tbl_headers["adresse1:paysUrn:urn"] = "adresse1_pays_urn";
$tbl_headers["adresse1:paysUrn:fr"] = "adresse1_pays_fr";
$tbl_headers["adresse1:paysUrn:nl"] = "adresse1_pays_nl";
$tbl_headers["adresse1:paysUrn:en"] = "adresse1_pays_en";
$tbl_headers["adresse1:paysUrn:de"] = "adresse1_pays_de";

$tbl_headers["adresse1:lambertX"] = "adresse1_lambertX";
$tbl_headers["adresse1:lambertY"] = "adresse1_lambertY";
$tbl_headers["adresse1:latitude"] = "adresse1_latitude";
$tbl_headers["adresse1:longitude"] = "adresse1_longitude";
$tbl_headers["adresse1:altitude"] = "adresse1_altitude";

$tbl_headers["adresse1:noaddress"] = "adresse1_noaddress";

$tbl_headers["adresse1:parcNaturel:label"] = "adresse1_parcNaturel_label";
$tbl_headers["adresse1:parcNaturel:idPn"] = "adresse1_parcNaturel_idPn";

$tbl_headers["adresse1:organisme"] = "adresse1_organisme";
$tbl_headers["adresse1:organisme:idMdt"] = "adresse1_organisme_idMdt";




// adresse 2

$tbl_headers["adresse2:rue"] = "adresse2_rue";
$tbl_headers["adresse2:numero"] = "adresse2_numero";
$tbl_headers["adresse2:boite"] = "adresse2_boite";

$tbl_headers["adresse2:idIns"] = "adresse2_idIns";

$tbl_headers["adresse2:ins"] = "adresse2_ins";

$tbl_headers["adresse2:cp"] = "adresse2_cp";

$tbl_headers["adresse2:localite:fr"] = "adresse2_localite_fr";
$tbl_headers["adresse2:localite:nl"] = "adresse2_localite_nl";
$tbl_headers["adresse2:localite:en"] = "adresse2_localite_en";
$tbl_headers["adresse2:localite:de"] = "adresse2_localite_de";

$tbl_headers["adresse2:commune:fr"] = "adresse2_commune_fr";
$tbl_headers["adresse2:commune:nl"] = "adresse2_commune_nl";
$tbl_headers["adresse2:commune:en"] = "adresse2_commune_en";
$tbl_headers["adresse2:commune:de"] = "adresse2_commune_de";

$tbl_headers["adresse2:lieuPrecis"] = "adresse2_lieuPrecis";


// $tbl_headers["adresse1:province"] = "adresse1_province";   ==============> deprecated
$tbl_headers["adresse2:provinceUrn:urn"] = "adresse2_provinceUrn_urn";
$tbl_headers["adresse2:provinceUrn:fr"] = "adresse2_provinceUrn_fr";
$tbl_headers["adresse2:provinceUrn:nl"] = "adresse2_provinceUrn_nl";
$tbl_headers["adresse2:provinceUrn:en"] = "adresse2_provinceUrn_en";
$tbl_headers["adresse2:provinceUrn:de"] = "adresse2_provinceUrn_de";

// $tbl_headers["adresse1:pays"] = "adresse1_pays";   ==============> deprecated
$tbl_headers["adresse2:paysUrn:urn"] = "adresse2_pays_urn";
$tbl_headers["adresse2:paysUrn:fr"] = "adresse2_pays_fr";
$tbl_headers["adresse2:paysUrn:nl"] = "adresse2_pays_nl";
$tbl_headers["adresse2:paysUrn:en"] = "adresse2_pays_en";
$tbl_headers["adresse2:paysUrn:de"] = "adresse2_pays_de";

$tbl_headers["adresse2:lambertX"] = "adresse2_lambertX";
$tbl_headers["adresse2:lambertY"] = "adresse2_lambertY";
$tbl_headers["adresse2:latitude"] = "adresse2_latitude";
$tbl_headers["adresse2:longitude"] = "adresse2_longitude";
$tbl_headers["adresse2:altitude"] = "adresse2_altitude";

$tbl_headers["adresse2:noaddress"] = "adresse2_noaddress";

$tbl_headers["adresse2:parcNaturel:label"] = "adresse2_parcNaturel_label";
$tbl_headers["adresse2:parcNaturel:idPn"] = "adresse2_parcNaturel_idPn";

$tbl_headers["adresse2:organisme"] = "adresse2_organisme";
$tbl_headers["adresse2:organisme:idMdt"] = "adresse2_organisme_idMdt";



// specs






// output record that will be written to the csv_File
$rec_out = [];

// template record containing a key/value pair for all the fields in the output csv
$tmpl_rec_out = [];

// csv file
$fp = fopen($GLOBALS['csv_file_name'], 'w');

// write header record (list of fields from tbl_headers)

Write_Header_Line();



$level_1_xml = simplexml_load_file($GLOBALS['xml_file_name']);
$level_1_array = simplexmlToArray($level_1_xml);

/**------------------------------------------
 *     level 1  ====>  Offre
 *------------------------------------------*/

foreach ($level_1_array as $key_level_1 => $key_level_1_value) {



    /**------------------------------------------
     *     level 2 ====> Index of the offer
     *------------------------------------------*/



    foreach ($key_level_1_value as $key_level_2 => $key_level_2_value) {  // index of the offers

        empty_tmp_rec_out();

        /**----------------------------------------------------------------------
         *     level 3 ===> each level 3 item is processed in a separate function.
         *----------------------------------------------------------------------*/



        foreach ($key_level_2_value as $key_level_3 => $key_level_3_value) {

            switch (strtolower($key_level_3)) {   // index of the offer



                case strtolower("userCreation"):
                    break;

                case strtolower("userGlobalCreation"):
                    break;

                case strtolower("userModification"):
                    break;

                case strtolower("userGlobalModification"):
                    break;

                case strtolower("nom"):
                    Proc_nom($key_level_3_value);
                    break;

                case strtolower("estActiveUrn"):
                    Proc_estActive($key_level_3, $key_level_2_value[$key_level_3]);  // upper level for the name and data
                    break;

                case strtolower("visibilite"):
                    break;

                case strtolower("visibiliteUrn"):
                    break;

                case strtolower("typeOffre"):
                    Proc_typeOffre($key_level_3, $key_level_2_value[$key_level_3]);  // upper level for the name and data

                    // Proc_typeOffre($key_level_3_value);
                    break;

                case strtolower("adresse1"):
                    Proc_adresse1($key_level_3_value);
                    break;

                case strtolower("adresse2"):
                    Proc_adresse2($key_level_3_value);
                    break;

                case strtolower("spec"):
                    Proc_Spec($key_level_3_value);
                    break;

                case strtolower("relOffre"):

                    break;
            }
        }

        fputcsv($fp, array_values($tmpl_rec_out), "$");
    }
}


fclose($fp);

echo ("End of process.");



/**-------------------------------------------------------------------
 * 
 *  Functions to process all level 3 items
 * 
 */




function Proc_nom($nom)
{

    global $tmpl_rec_out;

    $nom =  mb_convert_encoding($nom, 'Windows-1252', 'UTF-8');
    $tmpl_rec_out["nom"] = $nom;
}


function Proc_estActive($upperLevel, $estActive)
{

    global $tmpl_rec_out;

    foreach (array_keys($estActive) as $item_key) {

        if (is_array($estActive[$item_key]) == true) {

            foreach ($estActive[$item_key] as $pays) {
                $value = $pays["value"];
                $lang = $pays["lang"];
                $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                $tmpl_rec_out[$upperLevel . ":" . $lang] = $value;
            }
        } else {
            $value =  mb_convert_encoding($estActive[$item_key], 'Windows-1252', 'UTF-8');
            $tmpl_rec_out[$upperLevel . ":" . $item_key] = $value;
        }
    }

}



function Proc_typeOffre($upperLevel, $typeOffre)
{


    global $tmpl_rec_out;

    foreach (array_keys($typeOffre) as $item_key) {

        if (is_array($typeOffre[$item_key]) == true) {

            foreach ($typeOffre[$item_key] as $pays) {
                $value = $pays["value"];
                $lang = $pays["lang"];
                $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                $tmpl_rec_out[$upperLevel . ":" . $lang] = $value;
            }
        } else {
            $value =  mb_convert_encoding($typeOffre[$item_key], 'Windows-1252', 'UTF-8');
            $tmpl_rec_out[$upperLevel . ":" . $item_key] = $value;
        }
    }


}





function Proc_adresse1($adresse1)
{

    // global $tbl_headers;
    global $tmpl_rec_out;


    // level "label"
    foreach ($adresse1 as $key => $key_value) {



        switch (strtoLower($key)) {


            case strtolower("localite"):

                foreach ($key_value as $localite) {

                    $value = $localite["value"];
                    $lang = $localite["lang"];
                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse1:" . $key . ":" . $lang] = $value;
                }
                break;

            case strtolower("commune"):

                foreach ($key_value as $commune) {

                    $value = $commune["value"];
                    $lang = $commune["lang"];
                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse1:" . $key . ":" . $lang] = $value;
                }
                break;


            case strtolower("provinceUrn"):

                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $province) {
                            $value = $province["value"];
                            $lang = $province["lang"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse1:" . $key . ":" . $lang] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse1:" . $key . ":" . $item_key] = $value;
                    }
                }



                break;


            case strtolower("paysUrn"):




                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $pays) {
                            $value = $pays["value"];
                            $lang = $pays["lang"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse1:" . $key . ":" . $lang] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse1:" . $key . ":" . $item_key] = $value;
                    }
                }

                break;



            case strtolower("parcNaturel"):



                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $parcNaturel) {
                            $value = $parcNaturel["label"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse1:" . $key] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse1:" . $key . ":" . $item_key] = $value;
                    }
                }


                break;


            case strtolower("organisme"):

                foreach ($key_value as $item_value) {

                    $item_value =  mb_convert_encoding($key_value["label"], 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse1:" . $key] = $item_value;

                    $item_value =  mb_convert_encoding($key_value["idMdt"], 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse1:" . $key . ":idMdt"] = $item_value;
                }

                break;



            default:


                if (array_key_exists("adresse1:" . $key, $tmpl_rec_out) == true) {

                    if (is_array($key_value) == true) {
                        break;
                    }

                    $value = $key_value;
                    if (is_numeric($key_value) == true) {

                        $value = str_replace(".", ",", $key_value);
                    }

                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse1:" . $key] = $value;
                    break;
                }
        }

    }

}

function Proc_adresse2($adresse2)
{

    // global $tbl_headers;
    global $tmpl_rec_out;


    foreach ($adresse2 as $key => $key_value) {

    // level "label"

        switch (strtoLower($key)) {



            case strtolower("localite"):

                foreach ($key_value as $localite) {

                    $value = $localite["value"];
                    $lang = $localite["lang"];
                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
                }
                break;

            case strtolower("commune"):

                foreach ($key_value as $commune) {

                    $value = $commune["value"];
                    $lang = $commune["lang"];
                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
                }
                break;


            case strtolower("provinceUrn"):

                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $province) {
                            $value = $province["value"];
                            $lang = $province["lang"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
                    }
                }



                break;


            case strtolower("paysUrn"):




                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $pays) {
                            $value = $pays["value"];
                            $lang = $pays["lang"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
                    }
                }

                break;



            case strtolower("parcNaturel"):



                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $parcNaturel) {
                            $value = $parcNaturel["label"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse2:" . $key] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
                    }
                }


                break;


            case strtolower("organisme"):

                foreach ($key_value as $item_value) {

                    $item_value =  mb_convert_encoding($key_value["label"], 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key] = $item_value;

                    $item_value =  mb_convert_encoding($key_value["idMdt"], 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key . ":idMdt"] = $item_value;
                }

                break;



            default:


                if (array_key_exists("adresse2:" . $key, $tmpl_rec_out) == true) {

                    if (is_array($key_value) == true) {
                        break;
                    }

                    $value = $key_value;
                    if (is_numeric($key_value) == true) {

                        $value = str_replace(".", ",", $key_value);
                    }

                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key] = $value;
                    break;
                }
        }

    }
}



function Proc_Spec($spec)
{

    // global $tbl_headers;
    global $tmpl_rec_out;


    foreach ($spec as $key => $key_value) {

    // level "label"

            $exploded_string = explode(":", $key_value["urn"]);

            $field_name = $exploded_string[count($exploded_string) - 1];
            $lang = $exploded_string[0];


                $x = 2;
            }
            // if ( str_contains(strtolower($key_value["urn"]), strtolower("homepage")) {
            //     $tmpl_rec_out["SPEC:" . "homepage"] = $key_value["value"];
            // }



    exit;

        switch (strtoLower($key)) {



            case strtolower("localite"):

                foreach ($key_value as $localite) {

                    $value = $localite["value"];
                    $lang = $localite["lang"];
                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
                }
                break;

            case strtolower("commune"):

                foreach ($key_value as $commune) {

                    $value = $commune["value"];
                    $lang = $commune["lang"];
                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
                }
                break;


            case strtolower("provinceUrn"):

                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $province) {
                            $value = $province["value"];
                            $lang = $province["lang"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
                    }
                }



                break;


            case strtolower("paysUrn"):




                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $pays) {
                            $value = $pays["value"];
                            $lang = $pays["lang"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
                    }
                }

                break;



            case strtolower("parcNaturel"):



                foreach (array_keys($key_value) as $item_key) {

                    if (is_array($key_value[$item_key]) == true) {

                        foreach ($key_value[$item_key] as $parcNaturel) {
                            $value = $parcNaturel["label"];
                            $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                            $tmpl_rec_out["adresse2:" . $key] = $value;
                        }
                    } else {
                        $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
                        $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
                    }
                }


                break;


            case strtolower("organisme"):

                foreach ($key_value as $item_value) {

                    $item_value =  mb_convert_encoding($key_value["label"], 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key] = $item_value;

                    $item_value =  mb_convert_encoding($key_value["idMdt"], 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key . ":idMdt"] = $item_value;
                }

                break;



            default:


                if (array_key_exists("adresse2:" . $key, $tmpl_rec_out) == true) {

                    if (is_array($key_value) == true) {
                        break;
                    }

                    $value = $key_value;
                    if (is_numeric($key_value) == true) {

                        $value = str_replace(".", ",", $key_value);
                    }

                    $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
                    $tmpl_rec_out["adresse2:" . $key] = $value;
                    break;
                }
        }

    }





function Write_Header_Line()
{

    global $tbl_headers;
    global $fp;
    global $tmpl_rec_out;

    empty_tmp_rec_out();

    foreach ($tbl_headers as $key => $value) {

        $tmpl_rec_out[$key] = $value;
    }

    $rec_out = array_values($tmpl_rec_out);

    fputcsv($fp, array_values($tbl_headers), "$");
}




function empty_tmp_rec_out()
{

    global $tbl_headers;
    global $fp;
    global $tmpl_rec_out;

    foreach ($tbl_headers as $key => $value) {

        $tmpl_rec_out[$key] = "";
    }
}




function flatten(array $array)
{

    $output = array();
    array_walk_recursive($array, function ($arr) use (&$output) {
        $output[] = $arr;
    });
    return $output;
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


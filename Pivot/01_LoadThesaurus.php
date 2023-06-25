<?php

require __DIR__ . "/Parameters.php";
// require __DIR__ . "/functions.php";


$function_depth_counter = 0;
$fld_urn = "";
$fld_lang = "";
$fld_value = "";
$fld_value_fr = "";
$fld_value_nl = "";
$fld_value_en = "";
$fld_value_de = "";
$fld_abstract_flg = false;
$fld_abstract_value = "";



// $EOL_patterns[0] = "/\r\n/";
// $EOL_patterns[1] = "/\r/";
// $EOL_patterns[2] = "/\n/";


// if (($XML = file_get_contents($GLOBALS['xml_file_name'])) == false)
//     die('Error reading XML file...');


/**
 * 
 *    Define the fields that will be saved to the csv file
 */

$tbl_headers["urn"] = "urn";
// $tbl_headers["estActive"] = "Active";   ==============> deprecated
$tbl_headers["Value"] = "urn_Value";



// output record that will be written to the csv_File
$rec_out = [];

// template record containing a key/value pair for all the fields in the output csv
$tmpl_rec_out = [];

// csv file
$fp = fopen($GLOBALS['csv_file_name_thesaurus'], 'w');

// write header record (list of fields from tbl_headers)

Write_Header_Line();



$level_1_xml = simplexml_load_file($GLOBALS['xml_file_name_thesaurus']);
$level_1_array = simplexmlToArray($level_1_xml);



// list all urn:fld values


foreach ($level_1_array as $level2 => $level2_value) {

    Process_Level($level2_value);
    // if (is_array($level2_value) == true ) {

    //     foreach($level2_value as $level3 => $level3_value) {

    //         if (is_array($level2_value) == true ) {

    //             foreach($level2_value as $level3 => $level3_value) {
    //             }
                    
    //         } else {
    //             echo $level3_value;
    //     }
    // }
        
    // } else {
    //     echo $level2_value;
    // }

}

echo 'end of process.';
exit;




function Process_Level($level) {

    // var_dump($level);

   global $fld_urn ;
   global $fld_lang ;
   global $fld_value ;
   global $fld_value_fr ;
   global $fld_value_nl ;
   global $fld_value_en ;
   global $fld_value_de ;
   global $fld_abstract_flg ;
   global $fld_abstract_value ;
   global $function_depth_counter;


    if (is_array($level) == true ) {

        $function_depth_counter++;

        // echo "function depth counter : " . $function_depth_counter;
        
        foreach ($level as $child_level => $child_value) {

            // echo "Processing child_level : " . $child_level . "\n";


            if ($child_level == "spec") {

                if ($fld_lang == "" ) {
                    echo "value found for urn : " . $fld_urn .  " --> value for fld_value_" . " -> " . $fld_value . "\n";

                } else {
                    echo "value found for urn : " . $fld_urn .  " --> value for fld_value_" . $fld_lang . " -> " . ${"fld_value_" . $fld_lang} . "\n";

                }


                $fld_urn = "";
                $fld_lang = "" ;
                $fld_value = "";
                $fld_value_fr = "";
                $fld_value_nl = "" ;
                $fld_value_en = "" ;
                $fld_value_de = "";      
                $fld_abstract_flg = false;    
                $fld_abstract_value = ""; 

        }

            if (is_array($child_value) == true ) {

                if ($child_level == "abstract") {

                    $fld_abstract_flg = true;
                }

                Process_Level($child_value);

            } else {


                $child_level = trim($child_level," \r\n\t");
                $child_value = trim($child_value," \r\n\t");    


                if ($child_level == "lang") {
                    $fld_lang = $child_value;
                    ${"fld_value_" . $fld_lang} = $fld_value; 

                }

                if ($child_level == "value") {
                    if ($fld_abstract_flg == true ) {
                        $fld_abstract_value = $child_value ;
                        $fld_abstract_flg = false;
                    } else {
                        $fld_value = $child_value;

                    }
                }


                if ($child_level == "urn") {
                    $fld_urn = $child_value;


    
                }





                // switch ( strtolower($child_level) ) {

                //     case strtolower("dateModification"):
                //         break;
                //     case strtolower("dateCreation"):
                //         break;
                //     default:

                //         if (str_contains($child_value,"urn:val:") == true) {

                //             $found_value = trim($child_value," \r\n\t");


                //             // if (array_key_exists("lang", $level["label"])) {
                //             //     $lang = $level["label"]["lang"];

                //             // }

                //             try {
                //                 $field_value = $level["value"];
                //             }

                //             catch (Exception $e) {
                //                 $x = 5;
                //             }

                          

                //             // echo "value found for $child_level -> $found_value. -> " . $field_value . "\n";

                //             $x = 2;
                //         }


                // }


            }

        }



    } else {
        $found_value = trim($level," ");
        echo "this is a single value $level\n";
    }

    $function_depth_counter--;


}


















// /**------------------------------------------
//  *     level 1  ====>  Offre
//  *------------------------------------------*/

// foreach ($level_1_array as $key_level_1 => $key_level_1_value) {



//     /**------------------------------------------
//      *     level 2 ====> Index of the offer
//      *------------------------------------------*/



//     foreach ($key_level_1_value as $key_level_2 => $key_level_2_value) {  // index of the offers

//         empty_tmp_rec_out();

//         /**----------------------------------------------------------------------
//          *     level 3 ===> each level 3 item is processed in a separate function.
//          *----------------------------------------------------------------------*/



//         foreach ($key_level_2_value as $key_level_3 => $key_level_3_value) {

//             switch (strtolower($key_level_3)) {   // index of the offer



//                 case strtolower("userCreation"):
//                     break;

//                 case strtolower("userGlobalCreation"):
//                     break;

//                 case strtolower("userModification"):
//                     break;

//                 case strtolower("userGlobalModification"):
//                     break;

//                 case strtolower("nom"):
//                     Proc_nom($key_level_3_value);
//                     break;

//                 case strtolower("estActiveUrn"):
//                     Proc_estActive($key_level_3, $key_level_2_value[$key_level_3]);  // upper level for the name and data
//                     break;

//                 case strtolower("visibilite"):
//                     break;

//                 case strtolower("visibiliteUrn"):
//                     break;

//                 case strtolower("typeOffre"):
//                     Proc_typeOffre($key_level_3, $key_level_2_value[$key_level_3]);  // upper level for the name and data

//                     // Proc_typeOffre($key_level_3_value);
//                     break;

//                 case strtolower("adresse1"):
//                     Proc_adresse1($key_level_3_value);
//                     break;

//                 case strtolower("adresse2"):
//                     Proc_adresse2($key_level_3_value);
//                     break;

//                 case strtolower("spec"):
//                     Proc_Spec($key_level_3_value);
//                     break;

//                 case strtolower("relOffre"):

//                     break;
//             }
//         }

//         fputcsv($fp, array_values($tmpl_rec_out), "$");
//     }
// }


// fclose($fp);

// echo ("End of process.");



// /**-------------------------------------------------------------------
//  * 
//  *  Functions to process all level 3 items
//  * 
//  */




// function Proc_nom($nom)
// {

//     global $tmpl_rec_out;

//     $nom =  mb_convert_encoding($nom, 'Windows-1252', 'UTF-8');
//     $tmpl_rec_out["nom"] = $nom;
// }


// function Proc_estActive($upperLevel, $estActive)
// {

//     global $tmpl_rec_out;

//     foreach (array_keys($estActive) as $item_key) {

//         if (is_array($estActive[$item_key]) == true) {

//             foreach ($estActive[$item_key] as $pays) {
//                 $value = $pays["value"];
//                 $lang = $pays["lang"];
//                 $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                 $tmpl_rec_out[$upperLevel . ":" . $lang] = $value;
//             }
//         } else {
//             $value =  mb_convert_encoding($estActive[$item_key], 'Windows-1252', 'UTF-8');
//             $tmpl_rec_out[$upperLevel . ":" . $item_key] = $value;
//         }
//     }

// }



// function Proc_typeOffre($upperLevel, $typeOffre)
// {


//     global $tmpl_rec_out;

//     foreach (array_keys($typeOffre) as $item_key) {

//         if (is_array($typeOffre[$item_key]) == true) {

//             foreach ($typeOffre[$item_key] as $pays) {
//                 $value = $pays["value"];
//                 $lang = $pays["lang"];
//                 $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                 $tmpl_rec_out[$upperLevel . ":" . $lang] = $value;
//             }
//         } else {
//             $value =  mb_convert_encoding($typeOffre[$item_key], 'Windows-1252', 'UTF-8');
//             $tmpl_rec_out[$upperLevel . ":" . $item_key] = $value;
//         }
//     }


// }





// function Proc_adresse1($adresse1)
// {

//     // global $tbl_headers;
//     global $tmpl_rec_out;


//     // level "label"
//     foreach ($adresse1 as $key => $key_value) {



//         switch (strtoLower($key)) {


//             case strtolower("localite"):

//                 foreach ($key_value as $localite) {

//                     $value = $localite["value"];
//                     $lang = $localite["lang"];
//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse1:" . $key . ":" . $lang] = $value;
//                 }
//                 break;

//             case strtolower("commune"):

//                 foreach ($key_value as $commune) {

//                     $value = $commune["value"];
//                     $lang = $commune["lang"];
//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse1:" . $key . ":" . $lang] = $value;
//                 }
//                 break;


//             case strtolower("provinceUrn"):

//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $province) {
//                             $value = $province["value"];
//                             $lang = $province["lang"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse1:" . $key . ":" . $lang] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse1:" . $key . ":" . $item_key] = $value;
//                     }
//                 }



//                 break;


//             case strtolower("paysUrn"):




//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $pays) {
//                             $value = $pays["value"];
//                             $lang = $pays["lang"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse1:" . $key . ":" . $lang] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse1:" . $key . ":" . $item_key] = $value;
//                     }
//                 }

//                 break;



//             case strtolower("parcNaturel"):



//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $parcNaturel) {
//                             $value = $parcNaturel["label"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse1:" . $key] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse1:" . $key . ":" . $item_key] = $value;
//                     }
//                 }


//                 break;


//             case strtolower("organisme"):

//                 foreach ($key_value as $item_value) {

//                     $item_value =  mb_convert_encoding($key_value["label"], 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse1:" . $key] = $item_value;

//                     $item_value =  mb_convert_encoding($key_value["idMdt"], 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse1:" . $key . ":idMdt"] = $item_value;
//                 }

//                 break;



//             default:


//                 if (array_key_exists("adresse1:" . $key, $tmpl_rec_out) == true) {

//                     if (is_array($key_value) == true) {
//                         break;
//                     }

//                     $value = $key_value;
//                     if (is_numeric($key_value) == true) {

//                         $value = str_replace(".", ",", $key_value);
//                     }

//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse1:" . $key] = $value;
//                     break;
//                 }
//         }

//     }

// }

// function Proc_adresse2($adresse2)
// {

//     // global $tbl_headers;
//     global $tmpl_rec_out;


//     foreach ($adresse2 as $key => $key_value) {

//     // level "label"

//         switch (strtoLower($key)) {



//             case strtolower("localite"):

//                 foreach ($key_value as $localite) {

//                     $value = $localite["value"];
//                     $lang = $localite["lang"];
//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
//                 }
//                 break;

//             case strtolower("commune"):

//                 foreach ($key_value as $commune) {

//                     $value = $commune["value"];
//                     $lang = $commune["lang"];
//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
//                 }
//                 break;


//             case strtolower("provinceUrn"):

//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $province) {
//                             $value = $province["value"];
//                             $lang = $province["lang"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
//                     }
//                 }



//                 break;


//             case strtolower("paysUrn"):




//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $pays) {
//                             $value = $pays["value"];
//                             $lang = $pays["lang"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
//                     }
//                 }

//                 break;



//             case strtolower("parcNaturel"):



//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $parcNaturel) {
//                             $value = $parcNaturel["label"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse2:" . $key] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
//                     }
//                 }


//                 break;


//             case strtolower("organisme"):

//                 foreach ($key_value as $item_value) {

//                     $item_value =  mb_convert_encoding($key_value["label"], 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key] = $item_value;

//                     $item_value =  mb_convert_encoding($key_value["idMdt"], 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key . ":idMdt"] = $item_value;
//                 }

//                 break;



//             default:


//                 if (array_key_exists("adresse2:" . $key, $tmpl_rec_out) == true) {

//                     if (is_array($key_value) == true) {
//                         break;
//                     }

//                     $value = $key_value;
//                     if (is_numeric($key_value) == true) {

//                         $value = str_replace(".", ",", $key_value);
//                     }

//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key] = $value;
//                     break;
//                 }
//         }

//     }
// }



// function Proc_Spec($spec)
// {

//     // global $tbl_headers;
//     global $tmpl_rec_out;


//     foreach ($spec as $key => $key_value) {

//     // level "label"

//             $exploded_string = explode(":", $key_value["urn"]);

//             $field_name = $exploded_string[count($exploded_string) - 1];
//             $lang = $exploded_string[0];


//                 $x = 2;
//             }
//             // if ( str_contains(strtolower($key_value["urn"]), strtolower("homepage")) {
//             //     $tmpl_rec_out["SPEC:" . "homepage"] = $key_value["value"];
//             // }



//     exit;

//         switch (strtoLower($key)) {



//             case strtolower("localite"):

//                 foreach ($key_value as $localite) {

//                     $value = $localite["value"];
//                     $lang = $localite["lang"];
//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
//                 }
//                 break;

//             case strtolower("commune"):

//                 foreach ($key_value as $commune) {

//                     $value = $commune["value"];
//                     $lang = $commune["lang"];
//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
//                 }
//                 break;


//             case strtolower("provinceUrn"):

//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $province) {
//                             $value = $province["value"];
//                             $lang = $province["lang"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
//                     }
//                 }



//                 break;


//             case strtolower("paysUrn"):




//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $pays) {
//                             $value = $pays["value"];
//                             $lang = $pays["lang"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse2:" . $key . ":" . $lang] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
//                     }
//                 }

//                 break;



//             case strtolower("parcNaturel"):



//                 foreach (array_keys($key_value) as $item_key) {

//                     if (is_array($key_value[$item_key]) == true) {

//                         foreach ($key_value[$item_key] as $parcNaturel) {
//                             $value = $parcNaturel["label"];
//                             $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                             $tmpl_rec_out["adresse2:" . $key] = $value;
//                         }
//                     } else {
//                         $value =  mb_convert_encoding($key_value[$item_key], 'Windows-1252', 'UTF-8');
//                         $tmpl_rec_out["adresse2:" . $key . ":" . $item_key] = $value;
//                     }
//                 }


//                 break;


//             case strtolower("organisme"):

//                 foreach ($key_value as $item_value) {

//                     $item_value =  mb_convert_encoding($key_value["label"], 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key] = $item_value;

//                     $item_value =  mb_convert_encoding($key_value["idMdt"], 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key . ":idMdt"] = $item_value;
//                 }

//                 break;



//             default:


//                 if (array_key_exists("adresse2:" . $key, $tmpl_rec_out) == true) {

//                     if (is_array($key_value) == true) {
//                         break;
//                     }

//                     $value = $key_value;
//                     if (is_numeric($key_value) == true) {

//                         $value = str_replace(".", ",", $key_value);
//                     }

//                     $value =  mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
//                     $tmpl_rec_out["adresse2:" . $key] = $value;
//                     break;
//                 }
//         }

//     }





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




// function flatten(array $array)
// {

//     $output = array();
//     array_walk_recursive($array, function ($arr) use (&$output) {
//         $output[] = $arr;
//     });
//     return $output;
// }




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


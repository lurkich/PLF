<?php

require __DIR__ . "/Parameters.php";

$list_value = [];

$fp_thesaurus = fopen($GLOBALS['csv_file_name_thesaurus'], 'w');

// write header record (list of fields from tbl_headers)

// Write_Header_Line();



$xml_data_full = simplexml_load_file($GLOBALS['xml_file_name_thesaurus']);


$xml_specs = $xml_data_full->spec;

$p_cnt = count($xml_specs->spec);




$p_cnt = count($xml_specs);
// echo("count : " . $p_cnt . "\n");

foreach ($xml_specs->spec as $spec_l1) {

    Process_Level($spec_l1, "");

}


/*             $fields = array(array((string)($xml_Spec->abstract->value), (string)($xxx->value)));
            fputcsv($fp_thesaurus, 
                array($xml_Spec->attributes()->urn, 
                        $xml_Spec->abstract->value, 
                        $xxx->attributes()->lang, 
                        $xxx->value) ,
                "$"); */

                foreach ($list_value as $key => $value) {

                    $key_parts = explode("=",$key);
                    fputcsv($fp_thesaurus, array($key_parts[0], $key_parts[1], $value[0], $value[1]));

                }

exit;




function Process_Level($xml_Spec, $indent) {

    global $fp_thesaurus;
    global $list_value;


    $indent.= "  ";
    $level = substr_count($indent, " ") / 2 + 1;
    //echo($indent . "Processing level " . $level);

    $number_specs = count($xml_Spec->spec);


    //if ($indent == "") {echo("\n");}
    //echo($indent . "count : " . $number_specs);
    //echo ("\n");

    if (substr_count($xml_Spec->attributes()->urn, ":val:") > 0 ) {

        foreach ($xml_Spec->label as $xxx) {

            echo("found a value : " . $xml_Spec->attributes()->urn . " abstract : " . $xml_Spec->abstract->value . " with value " . $xxx->label . " ---- " . $xxx->attributes()->lang . ":" . $xxx->value . "\n");


            $spec_key = $xml_Spec->attributes()->urn . "=" . $xxx->attributes()->lang ;
            $spec_value = array((string)($xml_Spec->abstract->value), (string)($xxx->value));
            if (array_key_exists($spec_key, $list_value) == false) {
                $list_value[$xml_Spec->attributes()->urn . "=" . $xxx->attributes()->lang ] = $spec_value;
            }

       }

    }

    //echo($indent . "  " . $xml_Spec->attributes() . "\n");
    //echo($indent . "  " .$xml_Spec->label->attributes()->lang . " -> " . $xml_Spec->label->value . "\n");


    foreach ($xml_Spec->spec as $spec) {


        Process_Level($spec, $indent);

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


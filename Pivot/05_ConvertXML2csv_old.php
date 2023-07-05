<?php

require __DIR__ . "/Parameters.php";
// require __DIR__ . "/functions.php";

$EOL_patterns[0] = "/\r\n/";
$EOL_patterns[1] = "/\r/";
$EOL_patterns[2] = "/\n/";


// if (($XML = file_get_contents($GLOBALS['xml_file_name'])) == false)
//     die('Error reading XML file...');


/**
 * 
 *    Define the fields that will be saved to the csv file
 */


$tbl_headers["nom"] = "nom";
// $tbl_headers["estActive"] = "Active";   ==============> deprecated
//$tbl_headers["estActiveUrn:urn"] = "estActiveUrn";
//$tbl_headers["estActiveUrn:fr"] = "estActiveUrn_fr";
//$tbl_headers["estActiveUrn:nl"] = "estActiveUrn_nl";
//$tbl_headers["estActiveUrn:en"] = "estActiveUrn_en";
//$tbl_headers["estActiveUrn:de"] = "estActiveUrn_de";


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
$tbl_headers["spec:000001"] = "spec_000001";
$tbl_headers["spec:000002"] = "spec_000002";
$tbl_headers["spec:00000I"] = "spec_00000I";
$tbl_headers["spec:00000J"] = "spec_00000J";
$tbl_headers["spec:00000T"] = "spec_00000T";
$tbl_headers["spec:0001"] = "spec_0001";
$tbl_headers["spec:0055"] = "spec_0055";
$tbl_headers["spec:00XO"] = "spec_00XO";
$tbl_headers["spec:00XR"] = "spec_00XR";
$tbl_headers["spec:00YT"] = "spec_00YT";
$tbl_headers["spec:032O"] = "spec_032O";
$tbl_headers["spec:0A33"] = "spec_0A33";
$tbl_headers["spec:0A5G"] = "spec_0A5G";
$tbl_headers["spec:0IBQ"] = "spec_0IBQ";
$tbl_headers["spec:0NOU"] = "spec_0NOU";
$tbl_headers["spec:3PBC"] = "spec_3PBC";
$tbl_headers["spec:3SXU"] = "spec_3SXU";
$tbl_headers["spec:3ULN"] = "spec_3ULN";
$tbl_headers["spec:a29b"] = "spec_a29b";
$tbl_headers["spec:a29bdiff"] = "spec_a29bdiff";
$tbl_headers["spec:a29bdiff"] = "spec_a29bdiff";
$tbl_headers["spec:a29bdur"] = "spec_a29bdur";
$tbl_headers["spec:abris"] = "spec_abris";
$tbl_headers["spec:acctransport"] = "spec_acctransport";
$tbl_headers["spec:adolescent"] = "spec_adolescent";
$tbl_headers["spec:adr"] = "spec_adr";
$tbl_headers["spec:adrenaline"] = "spec_adrenaline";
$tbl_headers["spec:airjeuenf"] = "spec_airjeuenf";
$tbl_headers["spec:altmax"] = "spec_altmax";
$tbl_headers["spec:altmin"] = "spec_altmin";
$tbl_headers["spec:amatsensa"] = "spec_amatsensa";
$tbl_headers["spec:autre"] = "spec_autre";
$tbl_headers["spec:balade"] = "spec_balade";
$tbl_headers["spec:baladenature"] = "spec_baladenature";
$tbl_headers["spec:bbq"] = "spec_bbq";
$tbl_headers["spec:callebotis"] = "spec_callebotis";
$tbl_headers["spec:catcirc"] = "spec_catcirc";
$tbl_headers["spec:champ"] = "spec_champ";
$tbl_headers["spec:competition"] = "spec_competition";
$tbl_headers["spec:couples"] = "spec_couples";
$tbl_headers["spec:courseau"] = "spec_courseau";
$tbl_headers["spec:culturel"] = "spec_culturel";
$tbl_headers["spec:cycliste"] = "spec_cycliste";
$tbl_headers["spec:date"] = "spec_date";
$tbl_headers["spec:datedeb"] = "spec_datedeb";
$tbl_headers["spec:datefin"] = "spec_datefin";
$tbl_headers["spec:decouverte"] = "spec_decouverte";
$tbl_headers["spec:desccirc"] = "spec_desccirc";
$tbl_headers["spec:desccirc:de"] = "spec_desccirc_de";
$tbl_headers["spec:desccirc:en"] = "spec_desccirc_en";
$tbl_headers["spec:desccirc:nl"] = "spec_desccirc_nl";
$tbl_headers["spec:descmarket"] = "spec_descmarket";
$tbl_headers["spec:descmarket:de"] = "spec_descmarket_de";
$tbl_headers["spec:descmarket:en"] = "spec_descmarket_en";
$tbl_headers["spec:descmarket:nl"] = "spec_descmarket_nl";
$tbl_headers["spec:descmarket10"] = "spec_descmarket10";
$tbl_headers["spec:descmarket10:de"] = "spec_descmarket10_de";
$tbl_headers["spec:descmarket10:en"] = "spec_descmarket10_en";
$tbl_headers["spec:descmarket10:nl"] = "spec_descmarket10_nl";
$tbl_headers["spec:descmarket30"] = "spec_descmarket30";
$tbl_headers["spec:descmarket30:de"] = "spec_descmarket30_de";
$tbl_headers["spec:descmarket30:en"] = "spec_descmarket30_en";
$tbl_headers["spec:descmarket30:nl"] = "spec_descmarket30_nl";
$tbl_headers["spec:dist"] = "spec_dist";
$tbl_headers["spec:distforet"] = "spec_distforet";
$tbl_headers["spec:empierre"] = "spec_empierre";
$tbl_headers["spec:eqpsrvautre"] = "spec_eqpsrvautre";
$tbl_headers["spec:eqpsrvautre"] = "spec_eqpsrvautre";
$tbl_headers["spec:eqpsrvautre:nl"] = "spec_eqpsrvautre_nl";
$tbl_headers["spec:etatedit"] = "spec_etatedit";
$tbl_headers["spec:famille"] = "spec_famille";
$tbl_headers["spec:foret"] = "spec_foret";
$tbl_headers["spec:goudasphpave"] = "spec_goudashpave";
$tbl_headers["spec:gue"] = "spec_gue";
$tbl_headers["spec:halage"] = "spec_halage";
$tbl_headers["spec:handimental"] = "spec_handimental";
$tbl_headers["spec:hdifmax"] = "spec_hdifmax";
$tbl_headers["spec:hdifmin"] = "spec_hdifmin";
$tbl_headers["spec:historique"] = "spec_historique";
$tbl_headers["spec:homepage"] = "spec_homepage";
$tbl_headers["spec:idcirkwi"] = "spec_idcirkwi";
$tbl_headers["spec:idhades"] = "spec_idhades";
$tbl_headers["spec:idreco"] = "spec_idreco";
$tbl_headers["spec:infusgenduro"] = "spec_infusgenduro";
$tbl_headers["spec:infusgendurodiff"] = "spec_infusgendurodiff";
$tbl_headers["spec:infusgendurodur"] = "spec_infusgendurodur";
$tbl_headers["spec:infusgequ"] = "spec_infusgequ";
$tbl_headers["spec:infusgequdiff"] = "spec_infusgequdiff";
$tbl_headers["spec:infusgequdur"] = "spec_infusgequdur";
$tbl_headers["spec:infusgnwalk"] = "spec_infusgnwalk";
$tbl_headers["spec:infusgped"] = "spec_infusgped";
$tbl_headers["spec:infusgpeddiff"] = "spec_infusgpeddiff";
$tbl_headers["spec:infusgpeddprm"] = "spec_infusgpeddpmr";
$tbl_headers["spec:infusgpeddur"] = "spec_infusgpeddur";
$tbl_headers["spec:infusgpedpmr"] = "spec_infusgpedpmr";
$tbl_headers["spec:infusgpedpou"] = "spec_infusgpedpou";
$tbl_headers["spec:infusgtrail"] = "spec_infusgtrail";
$tbl_headers["spec:infusgtraildiff"] = "spec_infusgtraildiff";
$tbl_headers["spec:infusgtraildur"] = "spec_infusgtraildur";
$tbl_headers["spec:infusgvelotour"] = "spec_infusgvelotour";
$tbl_headers["spec:infusgvtc"] = "spec_infusgvtc";
$tbl_headers["spec:infusgvtcdiff"] = "spec_infusgvtcdiff";
$tbl_headers["spec:infusgvtcdur"] = "spec_infusgvtcdur";
$tbl_headers["spec:infusgvtt"] = "spec_infusgvtt";
$tbl_headers["spec:infusgvttdiff"] = "spec_infusgvttdiff";
$tbl_headers["spec:infusgvttdur"] = "spec_infusgvttdur";
$tbl_headers["spec:infusgxc"] = "spec_infusgxc";
$tbl_headers["spec:infusgxcdiff"] = "spec_infusgxcdiff";
$tbl_headers["spec:infusgxcdur"] = "spec_infusgxcdur";
$tbl_headers["spec:malentandant"] = "spec_malentandant";
$tbl_headers["spec:message"] = "spec_message";
$tbl_headers["spec:nomofr:de"] = "spec_nomofr_de";
$tbl_headers["spec:nomofr:en"] = "spec_nomofr_en";
$tbl_headers["spec:nomofr:fr"] = "spec_nomofr_fr";
$tbl_headers["spec:nomofr:nl"] = "spec_nomofr_nl";
$tbl_headers["spec:nonvoyant"] = "spec_nonvoyant";
$tbl_headers["spec:note"] = "spec_note";
$tbl_headers["spec:parking"] = "spec_parking";
$tbl_headers["spec:patrimoine"] = "spec_patrimoine";
$tbl_headers["spec:pature"] = "spec_pature";
$tbl_headers["spec:personage"] = "spec_personage";
$tbl_headers["spec:piqniq"] = "spec_piqniq";
$tbl_headers["spec:planeau"] = "spec_planeau";
$tbl_headers["spec:rampe"] = "spec_rampe";
$tbl_headers["spec:randoagueri"] = "spec_randoagueri";
$tbl_headers["spec:randonnee"] = "spec_randonnee";
$tbl_headers["spec:randooccas"] = "spec_randooccas";
$tbl_headers["spec:reco"] = "spec_reco";
$tbl_headers["spec:religieux"] = "spec_religieux";
$tbl_headers["spec:rural"] = "spec_rural";
$tbl_headers["spec:sentieracc"] = "spec_sentieracc";
$tbl_headers["spec:signal"] = "spec_signal";
$tbl_headers["spec:signalcode"] = "spec_signalcode";
$tbl_headers["spec:signalnrec"] = "spec_signalnrec";
$tbl_headers["spec:signalnrec:de"] = "spec_signalnrec_de";
$tbl_headers["spec:signalnrec:en"] = "spec_signalnrec_en";
$tbl_headers["spec:signalnrec:nl"] = "spec_signalnrec_nl";
$tbl_headers["spec:sportif"] = "spec_sportif";
$tbl_headers["spec:taborient"] = "spec_taborient";
$tbl_headers["spec:taborient"] = "spec_taborient";
$tbl_headers["spec:terre"] = "spec_terre";
$tbl_headers["spec:themat"] = "spec_themat";
$tbl_headers["spec:tourismemoire"] = "spec_tourismemoire";
$tbl_headers["spec:type"] = "spec_type";
$tbl_headers["spec:typecirc"] = "spec_typecirc";
$tbl_headers["spec:urlfacebook"] = "spec_urlfacebook";
$tbl_headers["spec:urlweb"] = "spec_halage";
$tbl_headers["spec:village"] = "spec_village";
$tbl_headers["spec:zoneinond"] = "spec_zoneinond";































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
    global $tbl_headers;
    global $EOL_patterns;


    foreach ($spec as $key => $key_value) {

        // level "spec"



        $exploded_string = explode(":", $key_value["urn"]);

        $field_name = $exploded_string[count($exploded_string) - 1];

        $lang = $exploded_string[0];

        if ($lang == "fr" or $lang == "nl" or $lang == "de" or $lang == "en") {
        } else {
            $lang = "NA";
        }





        if ($field_name == "note" or 
            $field_name == "date" ) {




        
        



                switch (strtoLower($field_name)) {



                    case strtolower("note"):
        
                        foreach ($key_value["spec"] as $note) {

                            $exploded_string_l3 = explode(":", $note["urn"]);
                            $field_name_l3 = $exploded_string_l3[count($exploded_string_l3) - 1];

                            $lang_l3 = $exploded_string_l3[0];
        
                            if ($lang_l3 == "fr" or $lang_l3 == "nl" or $lang_l3 == "de" or $lang_l3 == "en") {
                            } else {
                                $lang_l3 = "NA";
                            }



                            $tmpl_key_l3 = "spec:" . $field_name_l3;
                            if ($lang_l3 <> "NA") {
                                $tmpl_key_l3 .= ":" . $lang_l3;
                            } 

                            if (key_exists($tmpl_key_l3, $tbl_headers) == false) {
                                echo "the key level 3 value does not exist : " . $tmpl_key_l3 . "\n";
                            } else {
                    
                                if (key_exists("value",$note) == false) {
                                    echo "\n it is a level 3 error : \n";
                                    var_dump($key_value["spec"]);
                                }
                                $tmpl_rec_out[$tmpl_key_l3] = $note["value"];
                            }


                        }
                        break;
        
                    case strtolower("date"):
        
                        foreach ($key_value["spec"] as $recdate) {
        
                            $exploded_string_l3 = explode(":", $recdate["urn"]);
                            $field_name_l3 = $exploded_string_l3[count($exploded_string_l3) - 1];

                            $lang_l3 = $exploded_string_l3[0];
        
                            if ($lang_l3 == "fr" or $lang_l3 == "nl" or $lang_l3 == "de" or $lang_l3 == "en") {
                            } else {
                                $lang_l3 = "NA";
                            }



                            $tmpl_key_l3 = "spec:" . $field_name_l3;
                            if ($lang_l3 <> "NA") {
                                $tmpl_key_l3 .= ":" . $lang_l3;
                            } 

                            if (key_exists($tmpl_key_l3, $tbl_headers) == false) {
                                echo "the key level 3 value does not exist : " . $tmpl_key_l3 . "\n";
                            } else {
                    
                                if (key_exists("value",$recdate) == false) {
                                    echo "\n it is a level 3 error : \n";
                                    var_dump($key_value["spec"]);
                                }
                                $tmpl_rec_out[$tmpl_key_l3] = $recdate["value"];
                            }
                        }
                        break;
        
        
        
                }

            continue;
        }

        $tmpl_key = "spec:" . $field_name;
        if ($lang <> "NA") {
            $tmpl_key .= ":" . $lang;
        } 


        if (key_exists($tmpl_key, $tbl_headers) == false) {
            echo "the key value does not exist : " . $tmpl_key . "\n";
        } else {

            if (key_exists("value",$key_value) == false) {
                echo "\n it is an error : \n";
                var_dump($key_value);
            }

            // replace newlines by strings "\n" and 2 spaces by string "\t" for use when rebuilding the json file.

            // $csv_items["geometry"] = "\t\t\t" . preg_replace($EOL_patterns, '\n', $csv_items["geometry"]);

            if (strtolower($key_value["type"]) == strtolower("TextML")) {

                $key_value["value"] = preg_replace('/\n/', '<br>', $key_value["value"]);
                // $key_value["value"] = preg_replace('/\s\s\s\s/', '\t', $key_value["value"]);
                // $key_value["value"] = preg_replace('/\s/', '', $key_value["value"]);
    
            }

            $tmpl_rec_out[$tmpl_key] = mb_convert_encoding($key_value["value"], 'Windows-1252', 'UTF-8');
        }



    }
}
                $x = 2;
            
            // if ( str_contains(strtolower($key_value["urn"]), strtolower("homepage")) {
            //     $tmpl_rec_out["SPEC:" . "homepage"] = $key_value["value"];
            // }











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


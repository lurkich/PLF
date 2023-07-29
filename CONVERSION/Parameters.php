<?php


// Access DataBase

$db_file_name = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Data\PLF.accdb";


// Step 1 - convert Json to CSV   - Step 2
$json_file_name = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Data\TerritoriesAuth2022b.json";
$csv_file_name = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Data\TerritoriesAuth2022b.csv";


// Step 2 - Upload Table Json

$tbl_Json = "tbl_02_Upload_json";

//step 3 - Create file cantonnement - Step 4

$file_name_in_cantonnements = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Cantons et Triage du site Internet\Liste des cantonnements.csv";
$file_name_out_cantonnements = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Data\PHP_Cantonnements.csv";
$file_name_out_triages = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Data\PHP_triages.csv";


// Step 4 - Upload cantonnement / Triages

$tbl_Cantonnements = "tbl_04_cantonnements";
$tbl_Triages = "tbl_04_Triages";
$view_Cantons_Triages = "view_cantons_triages";


// Step 5 - Upload CC

$file_CC = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Conseils Cynegetiques\conseils cynégétiques.xlsx";
$tbl_CC = "tbl_05_CC";
$tbl_Update_CC = "tbl_05_Update_CC";


// Step 6 - Update Canton / Triages
$tbl_Canton_Triages = "tbl_06_Update_Canton_Triage";


// Step 7 - Upload Territoire Arlon

$DB_Territoires_chasse_Direction_Arlon_2023_MW = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Cartes\Territoires_chasse_Direction_Arlon_2023_MW.accdb";
$tbl_In_Territoires_chasse_Direction_Arlon_2023_MW = "Territoires_chasse_Direction_Arlon_2023_MW";
$tbl_Out_Territoires_chasse_Direction_Arlon_2023_MW = "tbl_07_Chasses_Direction_Arlon";


// Step 8 - Update Direction Arlon

$tbl_Direction_Arlon = "tbl_08_Update_Chasse_Direction_Arlon";

// Step 10 - Remove unecessary Fields

$tbl_json_final = "tbl_10_json_Final";



// Step 100 - Rebuild Json

$json_rebuild_file_name = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\www\PartageonsLaForet\json_rebuild.json";
$json_rebuild_without_tabs_file_name = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\www\PartageonsLaForet\json_rebuild_without_tabs.json";


// Step 110 - Upload fichier chasse


$Fichier_Chasses = "C:\Users\chris\OneDrive\IT\PartageonsLaForet\Fichier_Chasses\dates_chasses.sql";
$tbl_Chasses = "tbl_110_Chasses";
$tbl_Direction_Arlon2 = "Territoires_chasse_Direction_Arlon_2023_MW";


// Step 120 - Create DB Arlon

$tbl_plf_territories = "plf_territoires";
$tbl_plf_Cantonnement = "plf_cantonnements";
$tbl_plf_Chasses = "plf_chasses";
$tbl_plf_Triages = "plf_triages";
$tbl_plf_CC = "plf_CC";
$view_plf_Cantons_Triages = "view_cantons_triages";
$view_plf_territoires = "view_territoires";

<?php


require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";





//goto Test1;
//goto Test2;
//goto Test3;
//goto Test4;
//goto Test5;
//goto Test6;
//goto Test7;



/**********************************************************************************************
 * 
 *  Call to retrieve list of territories
 */


Test1:





$List_Territoires = PLF::Get_Territoire_List(TypeTerritoire: "T");

if (empty($List_Territoires)) {

    $error = plf::Get_Error();
    echo $error;
    exit;
}






$List_Territoires = PLF::Get_Territoire_List();

if (empty($List_Territoires)) {

    $error = plf::Get_Error();
    echo $error;
    exit;
}




/**********************************************************************************************
 * 
 *  Retrieve all information for a territoire
 */


 Test2:


 $Territories_Info = PLF::Get_Territoire_Info(Territoire_Name: "AR210", TypeTerritoire: "T");
 
 if (empty($Territories_Info)) {
 
     $error = plf::Get_Error();
     echo $error;
     exit;
 }
 
 





 $Territories_Info = PLF::Get_Territoire_Info(Territoire_Name: "9133383017");
 
 if (empty($Territories_Info)) {
 
     $error = plf::Get_Error();
     echo $error;
     exit;
 }












/**********************************************************************************************
 * 
 *  Call to retrieve list of chasse territories by date 
 */


 Test3:

 $List_Chasse_Territories_By_Date = PLF::Get_Chasse_By_Date(Chasse_Date: "3-10-2021" ,TypeTerritoire: "T");

 if (empty($List_Chasse_Territories_By_Date)) {
 
     $error = plf::Get_Error();
     echo $error;
     exit;
 } 

 if ($List_Chasse_Territories_By_Date[0] == 999) {

    echo "Pas de chasse pour cette date.";
 }






 $List_Chasse_Territories_By_Date = PLF::Get_Chasse_By_Date(Chasse_Date: "3-10-2021" );

 if (empty($List_Chasse_Territories_By_Date)) {
 
     $error = plf::Get_Error();
     echo $error;
     exit;
 } 







/**********************************************************************************************
 * 
 *  Call to retrieve list of chasse_dates for a territories 
 */


 Test4:

 $List_Chasse_Dates_By_Territories = PLF::Get_Chasse_By_Territoire(Territoire_Name: "VI08-03" ,TypeTerritoire: "T");

 if (empty($List_Chasse_Dates_By_Territories)) {
 
    $error = plf::Get_Error();
    echo $error;
    exit;
} 

if ($List_Chasse_Dates_By_Territories[0] == 999) {

    echo "Pas de date de chasse pour ce territoire.";
}






 $List_Chasse_Dates_By_Territories = PLF::Get_Chasse_By_Territoire(Territoire_Name: "9120000034" );

 if (empty($List_Chasse_Dates_By_Territories)) {
 
     $error = plf::Get_Error();
     echo $error;
     exit;
 } 


 if ($List_Chasse_Dates_By_Territories[0] == 999) {

    echo "Pas de date de chasse pour ce territoire.";
 }







/**********************************************************************************************
 * 
 *  Add new date chasse 
 */


Test5:

$RC_Insert = PLF::Chasse_Date_New(Territoire_Name: "HA22" ,Chasse_Date: "31-07-23" ,TypeTerritoire: "T");

 if ($RC_Insert == false) {
 
    $error = plf::Get_Error();
    echo $error;
    exit;
} 



 $RC_Insert = PLF::Chasse_Date_New(Territoire_Name: "9420000058" ,Chasse_Date: "27-07-24");

 if ($RC_Insert == false) {
 
     $error = plf::Get_Error();
     echo $error;
     exit;
 } 


/**********************************************************************************************
 * 
 *  Delete date chasse 
 */


 Test6:

$RC_Delete = PLF::Chasse_Date_Delete(Territoire_Name: "HA22" ,Chasse_Date: "31-07-23" ,TypeTerritoire: "T");

 if ($RC_Delete == false) {
 
    $error = plf::Get_Error();
    echo $error;
    exit;
} 


 $RC_Delete = PLF::Chasse_Date_Delete(Territoire_Name: "9420000058" ,Chasse_Date: "27-07-24");

 if ($RC_Delete == false) {
 
     $error = plf::Get_Error();
     echo $error;
     exit;
 } 



/**********************************************************************************************
 * 
 *  Build JSON file for territoire
 */


 Test7:

 $Territoire_Geometry = PLF::Territoire_JSON(Territoire_Name: "HA22",TypeTerritoire: "T");

 if ($Territoire_Geometry == "") {
 
    $error = plf::Get_Error();
    echo $error;
    exit;
} 


$fp = fopen("C:\Users\chris\OneDrive\Documents\\Result_Territories_id.json", 'w');
fwrite($fp, $Territoire_Geometry);




 $Territoire_Geometry = PLF::Territoire_JSON(Territoire_Name: "9420000058");

 if ($Territoire_Geometry == "") {
 
     $error = plf::Get_Error();
     echo $error;
     exit;
 } 

 $fp = fopen("C:\Users\chris\OneDrive\Documents\\Result_DA_Numero.json", 'w');
 fwrite($fp, $Territoire_Geometry);



End:

echo PHP_EOL . "Enf of process.";



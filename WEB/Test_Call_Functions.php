<?php


require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";




//goto Test1_new;
goto Test2_new;
//goto Test3_new;
//goto Test4_new;
//goto Test5_new;
//goto Test6_new;
//goto Test7_new;
//goto Test8_new;
//goto Test12_new;


//goto Test1;
//goto Test2;
//goto Test2a;
//goto Test3;
//goto Test3a;
//goto Test4;
//goto Test4a;
//goto Test5;
//goto Test6;
//goto Test7;
//goto Test8;
//goto Test9;
//goto Test9a;
//goto Test10;
//goto Test10a;
//goto Test11;
//goto Test11a;


/**
 *    **    **    ******   **        **        **
 *    ****  **    **        **      ****      **
 *    ** ** **    *****      **    **  **    **
 *    **  ****    **          **  **    **  **
 *    **   ***    **           ****      ****
 *    **    **    ******        **        **
 */



Test1_new:

$list_Territoires = PLF::SPW_Get_Territoire_List();

if ($List_Territoires[0] < 0) {

   echo $List_Territoires[1];

   //
   // .... traitement de l'erreur
   //
}







Test2_new:

$Territories_Info = PLF::SPW_Get_Territoire_Info("7113041054");

if ($Territories_Info[0] < 0) {

   echo $$Territories_Info[1];

   //
   // .... traitement de l'erreur
   //
}




Test3_new:

$List_Chasse_Territories_By_Date = PLF::SPW_Get_Chasse_By_Date(Chasse_Date: "6-10-2023");

if ($List_Chasse_Territories_By_Date[0] < 0) {

   echo $List_Chasse_Territories_By_Date[1];

   //
   // .... traitement de l'erreur
   //
}

if ($List_Chasse_Territories_By_Date[0] == -14) {

   echo "Pas de chasse pour cette date.";
}







Test4_new:


$List_Chasse_Dates_By_Territories = PLF::SPW_Get_Chasse_By_Territoire(Territoire_Name: "7113184002");

if ($List_Chasse_Dates_By_Territories[0] < 0) {

   echo $List_Chasse_Dates_By_Territories[1];

   //
   // .... traitement de l'erreur
   //
}



if ($List_Chasse_Dates_By_Territories[0] == -15) {


   echo "Pas de date de chasse pour ce territoire.";
}





Test5_new:

$List_Cantons = PLF::SPW_Get_Canton_List();

if ($List_Cantons[0] < 0) {

   echo $List_Cantons[1];

   //
   // .... traitement de l'erreur
   //
}






Test6_new:

$List_Territoire_By_Canton = PLF::SPW_Get_Territoire_By_Canton(Num_Canton: "912");

if ($List_Territoire_By_Canton[0] < 0) {

   echo $List_Territoire_By_Canton[1];

   //
   // .... traitement de l'erreur
   //
}


if ($List_Territoire_By_Canton[0] == 0) {


   echo "Pas de Territoire pour ce canton.";
}







Test7_new:

$List_CC = PLF::SPW_Get_CC_List();

if ($List_CC[0] < 0) {

   echo $List_CC[1];

   //
   // .... traitement de l'erreur
   //
}



Test8_new:

$List_Territoire_By_CC = PLF::SPW_Get_Territoire_By_CC(Code_CC: "CCFARM");

if ($List_Territoire_By_CC[0] < 0) {

   echo $List_Territoire_By_CC[1];

   //
   // .... traitement de l'erreur
   //
}






Test12_new:

$Territoire_Geometry = PLF::SPW_Territoire_JSON(Territoire_Name: "7113041008");

if ($Territoire_Geometry[0] < 0) {

   echo $Territoire_Geometry[1];

   //
   // .... traitement de l'erreur
   //

} else {

   $fp = fopen("C:\Users\chris\OneDrive\Documents\\Result_DA_Numero.json", 'w');
   fwrite($fp, $Territoire_Geometry[2]);
}











/**
 *     ******    **       *******
 *    ********   **       ********
 *    **    **   **       **     ** 
 *    **    **   **       **     ** 
 *    ********   *******  ******** 
 *     ******    *******  ******* 
 */


Test1:


$List_Territoires = PLF::Get_Territoire_List(TypeTerritoire: "T");


if ($List_Territoires[0] < 0) {

   echo $List_Territoires[1];

   //
   // .... traitement de l'erreur
   //
}


$List_Territoires = PLF::Get_Territoire_List();

if ($List_Territoires[0] < 0) {

   echo $List_Territoires[1];

   //
   // .... traitement de l'erreur
   //
}









Test2:


$Territories_Info = PLF::Get_Territoire_Info(Territoire_Name: "AR210", TypeTerritoire: "T");

if ($Territories_Info[0] < 0) {

   echo $List_Territoires[1];

   //
   // .... traitement de l'erreur
   //
}


Test2a:

$Territories_Info = PLF::Get_Territoire_Info(Territoire_Name: "9133383017");

if ($Territories_Info[0] < 0) {

   echo $List_Territoires[1];

   //
   // .... traitement de l'erreur
   //
}










Test3:

$List_Chasse_Territories_By_Date = PLF::Get_Chasse_By_Date(Chasse_Date: "3-10-2021", TypeTerritoire: "T");

if ($List_Chasse_Territories_By_Date[0] < 0) {

   echo $List_Chasse_Territories_By_Date[1];

   //
   // .... traitement de l'erreur
   //
}

if ($List_Chasse_Territories_By_Date[0] == 0) {

   echo "Pas de chasse pour cette date.";
}



Test3a:

$List_Chasse_Territories_By_Date = PLF::Get_Chasse_By_Date(Chasse_Date: "3-10-2021");


if ($List_Chasse_Territories_By_Date[0] < 0) {

   echo $List_Chasse_Territories_By_Date[1];

   //
   // .... traitement de l'erreur
   //
}

if ($List_Chasse_Territories_By_Date[0] == 0) {

   echo "Pas de chasse pour cette date.";
}








Test4:

$List_Chasse_Dates_By_Territories = PLF::Get_Chasse_By_Territoire(Territoire_Name: "VI08-03", TypeTerritoire: "T");

if ($List_Chasse_Dates_By_Territories[0] < 0) {

   echo $List_Chasse_Dates_By_Territories[1];

   //
   // .... traitement de l'erreur
   //
}



if ($List_Chasse_Dates_By_Territories[0] == 0) {


   echo "Pas de date de chasse pour ce territoire.";
}



Test4a:

$List_Chasse_Dates_By_Territories = PLF::Get_Chasse_By_Territoire(Territoire_Name: "9110501001");

if ($List_Chasse_Dates_By_Territories[0] < 0) {

   echo $List_Chasse_Dates_By_Territories[1];

   //
   // .... traitement de l'erreur
   //
}



if ($List_Chasse_Dates_By_Territories[0] == 0) {


   echo "Pas de date de chasse pour ce territoire.";
}







/**********************************************************************************************
 * 
 *  Call to retrieve list of Cantons 
 */


Test5:

$List_Cantons = PLF::Get_Canton_List();

if ($List_Cantons[0] < 0) {

   echo $List_Cantons[1];

   //
   // .... traitement de l'erreur
   //
}




/**********************************************************************************************
 * 
 *  Call to retrieve list of territories by Canton 
 */


Test6:

$List_Territoire_By_Canton = PLF::Get_Territoire_By_Canton(Num_Canton: "913");

if ($List_Territoire_By_Canton[0] < 0) {

   echo $List_Territoire_By_Canton[1];

   //
   // .... traitement de l'erreur
   //
}



if ($List_Territoire_By_Canton[0] == 0) {


   echo "Pas de Territoire pour ce canton.";
}











/**********************************************************************************************
 * 
 *  Call to retrieve list of Conseil Cynégétique 
 */


Test7:

$List_CC = PLF::Get_CC_List();

if ($List_CC[0] < 0) {

   echo $List_CC[1];

   //
   // .... traitement de l'erreur
   //
}






/**********************************************************************************************
 * 
 *  Call to retrieve list of territories by Conseil cynégétique 
 */


Test8:

$List_Territoire_By_CC = PLF::Get_Territoire_By_CC(Code_CC: "CCFARM");

if ($List_Territoire_By_CC[0] < 0) {

   echo $List_Territoire_By_CC[1];

   //
   // .... traitement de l'erreur
   //
}



if ($List_Territoire_By_CC[0] == 0) {


   echo "Pas de Territoire pour ce conseil cynégétique.";
}






/**********************************************************************************************
 * 
 *  Add new date chasse 
 */


Test9:

$RC_Insert = PLF::Chasse_Date_New(Territoire_Name: "HA22", Chasse_Date: "31-07-23", TypeTerritoire: "T");

if ($RC_Insert[0] < 0) {

   echo $RC_Insert[1];

   //
   // .... traitement de l'erreur
   //
}


Test9a:

$RC_Insert = PLF::Chasse_Date_New(Territoire_Name: "9110501001", Chasse_Date: "27-07-24");

if ($RC_Insert[0] < 0) {

   echo $RC_Insert[1];

   //
   // .... traitement de l'erreur
   //
}



/**********************************************************************************************
 * 
 *  Delete date chasse 
 */


Test10:

$RC_Delete = PLF::Chasse_Date_Delete(Territoire_Name: "HA22", Chasse_Date: "31-07-23", TypeTerritoire: "T");

if ($RC_Delete == false) {

   $error = plf::Get_Error();
   echo $error;
   exit;
}


$RC_Delete = PLF::Chasse_Date_Delete(Territoire_Name: "9110501001", Chasse_Date: "27-07-24");

if ($RC_Delete == false) {

   $error = plf::Get_Error();
   echo $error;
   exit;
}






/**********************************************************************************************
 * 
 *  Build JSON file for territoire
 */


Test11:

$Territoire_Geometry = PLF::Territoire_JSON(Territoire_Name: "HA22", TypeTerritoire: "T");

if ($Territoire_Geometry[0] < 0) {

   echo $Territoire_Geometry[1];

   //
   // .... traitement de l'erreur
   //
} else {

   $fp = fopen("C:\Users\chris\OneDrive\Documents\\Result_Territories_id.json", 'w');
   fwrite($fp, $Territoire_Geometry[2]);
}



Test11a:


$Territoire_Geometry = PLF::Territoire_JSON(Territoire_Name: "9420000058");

if ($Territoire_Geometry[0] < 0) {

   echo $Territoire_Geometry[1];

   //
   // .... traitement de l'erreur
   //

} else {

   $fp = fopen("C:\Users\chris\OneDrive\Documents\\Result_DA_Numero.json", 'w');
   fwrite($fp, $Territoire_Geometry[2]);
}





End:

echo PHP_EOL . "Enf of process.";

<?php

// use  \vendor\phpoffice\phpspreadsheet\src\PhpSpreadsheet\IOFactory;
require __DIR__ . "/Parameters.php";
require __DIR__ . "/functions.php";
require __DIR__ . "/../vendor/autoload.php";


// include the autoloader, so we can use PhpSpreadsheet
//require_once __DIR__ . '../vendor/autoload.php';



// DB Location 

$sql_cmd = "";
$row = "";
$tbl_CC = $GLOBALS["tbl_CC"];
$tbl_In = $GLOBALS["tbl_Json"];
$tbl_Out = $GLOBALS["tbl_Update_CC"];
$file_CC = $GLOBALS["file_CC"];

/**
 * 
 *  Create and upload table CC
 */


Upload_plf_CC($tbl_CC, $file_CC);

/**
 * 
 *  Duplicate the input table
 */

Duplicate_Table($tbl_In, $tbl_Out);


/**
 * 
 *  Connect to the database
 *  
 */

$db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



/**
 * 
 * Add the Ptr_CC, CC_Error fields
 */

tbl_Add_Column($tbl_Out, "ptr_CC", "INTEGER");
tbl_Add_Column($tbl_Out, "CC_ERROR", "TEXT(255)");



/**
 * 
 * read into memory table conseil cynégétique into array $list_CC_Code
 * 
 */

$list_CC_Code = [];


$sql_cmd = "SELECT tbl_id, Code FROM $tbl_CC";



foreach ($db_conn->query($sql_cmd) as $record_CC) {

    $list_CC_Code[$record_CC['Code']] = $record_CC['tbl_id'];
}



/**
 * 
 * Process the DB table DB_V1 and update field ptr_CC with record ID of the CC
 * 
 */


$sql_cmd = "SELECT tbl_id, Conseil_name, NOMUGC FROM $tbl_Out";


$sql_update = '';



foreach ($result = $db_conn->query($sql_cmd) as $row) {

    //echo "\nprocessing CC : " . $row['Conseil_name'] . " with ID : " . $row['tbl_id'] . " ---> ";

    $db_Conseil_Index = array_search($row['Conseil_name'], array_keys($list_CC_Code), true);



    if (empty($row['Conseil_name'] == true)) {

        // echo "  ERROR : Conseil_name field is empty.";

        $sql_update = "UPDATE $tbl_Out SET ";
        $sql_update .= " ptr_CC = " . $list_CC_Code['ERROR_EMPTY'] . ", ";
        $sql_update .= " CC_ERROR = 'ERROR : CC field is empty.'";
        $sql_update .= " WHERE tbl_id = " . $row['tbl_id'];
    } elseif ($db_Conseil_Index == false) {

        // echo "  ERROR : Conseil_name field NOT FOUND.";

        $sql_update = "UPDATE $tbl_Out SET ";
        $sql_update .= " ptr_CC = " . $list_CC_Code['ERROR_NOT_FOUND'] . ", ";
        $sql_update .= " CC_ERROR = 'ERROR : CC field Not found. => " . $row['Conseil_name'] . " => " . $row['NOMUGC'] . "'";
        $sql_update .= " WHERE tbl_id = " . $row['tbl_id'];
    } else {

        // record CC exist
        // echo '  FOUND. CC_ID is ' . $list_CC_Code[$row['Conseil_name']] . "";

        $sql_update = "UPDATE $tbl_Out SET ";
        $sql_update .= " ptr_CC = " . $list_CC_Code[$row['Conseil_name']] . ", ";
        $sql_update .= " CC_ERROR = ''";
        $sql_update .= " WHERE tbl_id = " . $row['tbl_id'];
    }

    try {
        $sql_result = $db_conn->query($sql_update);
    } catch (PDOException $e) {
        echo ("Error : " . $e->getMessage() . "\n     " . "SQL Command : " . $sql_update . "\n\n");
    }
}






unset($db_conn);
echo ("\nEnd of process.");





/**
 * 
 *  Upload Table_CC
 * 
 */


function Upload_plf_CC($tbl_CC, $File_CC)
{



    // DB Location 

    $sql_cmd = "";
    $row = "";


    /**
     * 
     *  Drop the table
     */

    drop_Table($tbl_CC);



    /**
     * 
     *  Create the tables
     * 
     */

    $tbl_Definition_CC = [];

    $tbl_Definition_CC["Code"] = "TEXT (255)";
    $tbl_Definition_CC["Nom"] = "TEXT (255)";
    $tbl_Definition_CC["President"] = "TEXT (255)";
    $tbl_Definition_CC["Secretaire"] = "TEXT (255)";
    $tbl_Definition_CC["email"] = "TEXT (255)";
    $tbl_Definition_CC["adresse"] = "TEXT (255)";
    $tbl_Definition_CC["localisation"] = "TEXT (255)";
    $tbl_Definition_CC["site_internet"] = "TEXT (255)";
    $tbl_Definition_CC["logo"] = "TEXT (255)";





    Create_Table($tbl_CC, $tbl_Definition_CC);





    /**
     * 
     *  Connect to the database
     *  
     */

    $db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;");
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /**
     * 
     *  Process fichier CC.
     * 
     */


    # Create a new Xls Reader
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    // Tell the reader to only read the data. Ignore formatting etc.
    $reader->setReadDataOnly(true);

    // Read the spreadsheet file.
    $spreadsheet = $reader->load($GLOBALS["file_CC"]);

    $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
    $data = $sheet->toArray();


    $x = 0;

    foreach ($data as $rec) {

        if ($rec[0] == "Code") {            // skip header row

            continue;
        }


        for ($i = 0; $i <  count($rec) - 1; $i++) {
            if (empty($rec[$i] == false)) {
                $rec[$i] = preg_replace('/"/', "", $rec[$i]);
                $rec[$i] = preg_replace("/'/", "''", $rec[$i]);
                $rec[$i] = preg_replace("/;/", ";;", $rec[$i]);
                $rec[$i] = mb_convert_encoding($rec[$i], 'Windows-1252', 'UTF-8');
            }
        }

        $x++; 

        $sql_cmd = "INSERT INTO  $tbl_CC (Code, Nom, President, Secretaire, email, adresse, localisation, site_internet, logo  " .
            ")  VALUES ( " .
            " '" . $rec[0] . "', " .
            " '" . $rec[1] . "', " .
            " '" . $rec[2] . "', " .
            " '" . $rec[3] . "', " .
            " '" . "AA" . $x . "', " .
            " '" . "BB" . $x . "', " .
            " '" . "CC" . $x . "', " .
            " '" . "DD" . $x . "', " .
            " '" . "EE" . $x . "' " .
            ")";

        try {
            $sql_result = $db_conn->query($sql_cmd);
        } catch (PDOException $e) {
            echo ("Error : " . $e->getMessage() . "SQL Command : ");
            echo "\n$sql_cmd\n\n";
        }
    }






























    unset($db_conn);
    echo ("\nEnd of process.");
}

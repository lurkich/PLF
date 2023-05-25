<?php

require_once __DIR__ . "/Parameters.php";


class PLF
{

    private static $error;

    /**
     * 
     *  List of return codes
     *      -1 : no records found
     *      -2 : territoire does not exist
     *      -3 : mutltiple records found for territoire
     *      -4 : Invalid date
     *      -5 : MySql error
     *      -99 : other errors 
     *       xx : positive integer -> number of records deleted
     */

    /**
     * 
     *    Make a list of territories by "Territories_id" OR "Nomenclature" (DA_Numero)
     * 
     *      Input     : Database "PLF_Territoires"
     *
     *      Calling   : Get_Territoire_List(TypeTerritoire: "T")
     *                  Get_Territoire_List()
     * 
     *      Arguments : TypeTerritoire --> "T"       Select on "Territories_di"
     *                                     Nothing   Select on "Nomenclature"  (DA_Numero)
     * 
     *      Return    : Array containing all "Territories_Id" OR "Nomenclature" (DA_Numero)
     *                  Order by "Territories_Id" OR "Nomenclature"
     *                  DISTINCT
     *                      Structure : Array[] = values "Territories_id" OR values "Nomenclature"
     *                  Array[0] = -1
     *
     *                  Possible return codes :
     *                      -5 : MySql error
     * 
     */


    public static function Get_Territoire_List($TypeTerritoire = NULL)
    {

        $db_connection = PLF::__Open_DB();

        if ($db_connection == NULL) {

            $error_msg = PLF::Get_Error();
            return NULL;
        }

        // Build SQL statement

        $sql_cmd = "SELECT DISTINCT DA_Numero, Territories_id FROM $GLOBALS[tbl_Territoires] ORDER BY ";


        if (strtolower($TypeTerritoire ?? '') == "t") {
            $sql_cmd .= "Territories_id";
        } else {
            $sql_cmd .= "DA_Numero";
        }


        $List_Array = [];

        // Process SQL records


        try {

            foreach ($db_connection->query($sql_cmd) as $record) {

                if (strtolower($TypeTerritoire ?? '') == "t") {
                    array_push($List_Array, $record["Territories_id"]);
                } else {
                    array_push($List_Array, $record["DA_Numero"]);
                }
            }
        } catch (Exception $e) {


            self::$error =  'Error SELECT ' . PHP_EOL;
            self::$error .= $e->getMessage() . PHP_EOL;
            self::$error .= $sql_cmd;
            return NULL;
        }




        // Close Database

        PLF::__Close_DB($db_connection);



        // return values

        return $List_Array;
    }





    /**
     * 
     *    Return all the information regarding a territoire based on the "Territories_id" OR "Nomenclature" (DA_Numero)
     *
     *      Input     : Database "VTerritoires"
     * 
     *      Calling   : Get_Territoire_List(TypeTerritoire: "T")
     *                  Get_Territoire_List()
     *
     *      Arguments : TypeTerritoire    --> "T"       Select on "Territories_id"
     *                                        Nothing   Select on "Nomenclature" (DA_Numero)
     *                  Territoire_Name   --> Territories_id OR Nomenclature (DA_Numero)
     * 
     *      Return    : Associative Array containing key/value pair
     *                      Structure Array[<key>] = <value>
     * 
     *                  Possible return codes :
     *                      -2 territoire_name is not found
     *                      -3 multiple territoire records exist.
     *                      -5 : MySql error
     */


    public static function Get_Territoire_Info($Territoire_Name, $TypeTerritoire = NULL)
    {


        $List_Columns = [

            "Territories_id",
            "Nomenclature",
            "DA_Numero",
            "Territories_name",
            "DA_Nom",
            "TITULAIRE_",
            "NOM_TITULA",
            "PRENOM_TIT",
            "TITULAIRE1",
            "SAISON",
            "num_canton",
            "nom_canton",
            "tel_canton",
            "Code_CC",
            "Nom_CC",
            "President_CC",
            "Secretaire_CC",
            "COMMENTAIR",
            "num_triage",
            "nom_triage",
            "nom_Prepose",
            "gsm_Prepose",
            "DATE_MAJ",
            "ESRI_OID",
            "tbl_id"

        ];


        $db_connection = PLF::__Open_DB();

        if ($db_connection == NULL) {

            $error_msg = PLF::Get_Error();
            return NULL;
        }


        /**
         * 
         *  Build the SQL statement based on the $list_Columns array
         */


        $sql_cmd = "SELECT ";
        foreach (array_values($List_Columns) as $array_value) {

            $sql_cmd .= "$array_value, ";
        }

        $sql_cmd = preg_replace("/,\s*$/", "", $sql_cmd);
        $List_Array = [];


        $sql_cmd .= " FROM $GLOBALS[View_Territoires] WHERE ";

        if (strtolower($TypeTerritoire ?? '') == "t") {
            $sql_cmd .= " Territories_id = '";
        } else {
            $sql_cmd .= " DA_Numero = '";
        }

        $sql_cmd .= "$Territoire_Name'";







        // Process SQL records

        $List_Array = [];

        try {

            foreach ($db_connection->query($sql_cmd) as $record) {

                foreach ($List_Columns as $Column) {

                    $List_Array[$Column] = $record[$Column];
                }
            }
        } catch (Exception $e) {


            self::$error =  'Error SELECT ' . PHP_EOL;
            self::$error .= $e->getMessage() . PHP_EOL;
            self::$error .= $sql_cmd;
            return NULL;
        }




        // Close Database

        PLF::__Close_DB($db_connection);



        // return values

        return $List_Array;
    }






    /**
     * 
     *    Make a list of territories by "Date de chasse"
     * 
     *      Input     : Database "PLF_Chasses"
     *
     *      Calling   : Get_Chasse_By_Date(Date_Chasse: <Date Chasse>) 
     *    
     *      Arguments : TypeTerritoire    --> "T"       Select on "Territories_id" 
     *                  Date_Chasse       --> "Date" format DD-MM-YYYY
     * 
     *      Return    : Array of Array containing all "Territories_Id" AND "Nomenclature" (DA_Numero)
     *                      Structure : Array[] = [<value of Territories_id>], [<value of Nomenclature>]
     * 
     *                  Possible return codes :
     *                      -1 no records found
     *                      -4 Invalid date
     *                      -5 : MySql error
     *
     */

    public static function Get_Chasse_By_Date($Chasse_Date, $TypeTerritoire = NULL)
    {

        $db_connection = PLF::__Open_DB();

        if ($db_connection == NULL) {

            $error_msg = PLF::Get_Error();
            return NULL;
        }

        // Build SQL statement

        $sql_cmd = "SELECT DA_Numero, Territories_id FROM $GLOBALS[tbl_Chasses] ";
        $sql_cmd .= "WHERE Date_Chasse = ";

        $date_delimiter = "'";       // for MySql

        if (strtolower($GLOBALS['DB_MSAccess_or_MySql'] ?? '') == "msaccess") {
            $date_delimiter = "#";       // for MsAccess

        }

        $sql_cmd .= $date_delimiter . PLF::__Convert_2_Sql_Date(Date_DD_MM_YYYY: $Chasse_Date);
        $sql_cmd .= $date_delimiter;

        $sql_cmd .= " ORDER BY ";

        if (strtolower($TypeTerritoire ?? '') == "t") {
            $sql_cmd .= "Territories_id";
        } else {
            $sql_cmd .= "DA_Numero";
        }




        // Execute SQL statement


        $List_Array = [];

        // Process SQL records

        try {


            foreach ($db_connection->query($sql_cmd) as $record) {

                if (strtolower($TypeTerritoire ?? '') == "t") {
                    array_push($List_Array, $record["Territories_id"]);
                } else {
                    array_push($List_Array, $record["DA_Numero"]);
                }
            }
        } catch (Exception $e) {

            self::$error =  'Error SELECT ' . PHP_EOL;
            self::$error .= $e->getMessage() . PHP_EOL;
            self::$error .= $sql_cmd;
            return NULL;
        }



        // Close Database

        PLF::__Close_DB($db_connection);



        // return values

        if (count($List_Array) == 0) {
            $List_Array[0] = 999;
        }
        return $List_Array;
    }

    /**
     * 
     *    Make a list of "Date de chasse" by Territories_id OR Nomenclature (DA_Numero)
     * 
     *      Input     : Database "PLF_Chasses"
     *
     *      Calling   : Get_Territoire_List(TypeTerritoire: "T", <territories_id> or <Nomenclature>)
     *                  Get_Territoire_List()     
     *  
     *      Arguments : TypeTerritoire : --> "T"        select on "territories_id"
     *                                   --> Nothing    select on "Nomenclature"
     *                  Territoire     : --> <territories_id> or <Nomenclature>
     * 
     *      Return    : Array containing all date de chasse for the "Territories_Id" OR the "Nomenclature" (DA_Numero)
     *                      Structure Array[] = "date" 
     * 
     *                  Possible return codes :
     *                      -1 no records found
     *                      -2 Territoire does not exist
     *                      -5 : MySql error
     */

    public static function Get_Chasse_By_Territoire($Territoire_Name, $TypeTerritoire = NULL)
    {


        $db_connection = PLF::__Open_DB();

        if ($db_connection == NULL) {

            $error_msg = PLF::Get_Error();
            return NULL;
        }

        // Build SQL statement

        $sql_cmd = "SELECT Date_Chasse FROM $GLOBALS[tbl_Chasses] ";
        $sql_cmd .= " WHERE ";

        if (strtolower($TypeTerritoire ?? '') == "t") {
            $sql_cmd .= " Territories_id = '";
        } else {
            $sql_cmd .= " DA_Numero = '";
        }

        $sql_cmd .= $Territoire_Name;


        $sql_cmd .= "' ORDER BY Date_Chasse ";


        // Execute SQL statement

        $List_Array = [];

        // Process SQL records

        try {

            foreach ($db_connection->query($sql_cmd) as $record) {

                $sqlDate = new DateTime($record["Date_Chasse"]);
                array_push($List_Array, $sqlDate->format('d-m-Y'));
            }
        } catch (Exception $e) {

            self::$error =  'Error SELECT ' . PHP_EOL;
            self::$error .= $e->getMessage() . PHP_EOL;
            self::$error .= $sql_cmd;
            return NULL;
        }







        // Close Database

        PLF::__Close_DB($db_connection);



        // return values
        
        if (count($List_Array) == 0) {
            $List_Array[0] = 999;
        }
        return $List_Array;
    }


    /**
     * 
     *    Create a new "Date de chasse"
     * 
     *      Output    : Database "PLF_Chasses"
     *      
     *      Calling   : Chasse_Date_New(TypeTerritoire: "T", Territoire_Name: <Name of territoire), Date_Chasse: <DD-MM-YYY>)
     *                  Chasse_Date_New(Territoire_Name: <Name of territoire), Date_Chasse: <DD-MM-YYY>)
     * 
     *      Arguments : TypeTerritoire   --> "T"          select on "territories_id"
     *                                   --> Nothing      select on "Nomenclature"
     *                  Territoire_Name  --> Territories_id OR Nomenclature (DA_Numero)
     *                  Date_Chasse      --> "Date"       format DD-MM-YYYY
     * 
     *      Return    : True             --> successful insert
     *                  False            --> unsuccessful insert
     * 
     *                  Possible return codes :
     *                      -2 Territoire does not exist
     *                      -4 invalid date   
     *                      -5 MySql error
     */

    public static function Chasse_Date_New($Territoire_Name, $Chasse_Date, $TypeTerritoire = NULL)
    {


        $db_connection = PLF::__Open_DB();

        if ($db_connection == NULL) {

            $error_msg = PLF::Get_Error();
            return false;
        }


        // Get the territories_id corresponding to DA_Numero and vice versa


        $territoire = PLF::__Get_Corresponding_Territoire(db_connection: $db_connection, Territoire_Name: $Territoire_Name, TypeTerritoire: $TypeTerritoire);

        if ($territoire == NULL) {
            return false;
        }


        // Build SQL statement

        $Territoire_id = $territoire[0];
        $DA_Numero = $territoire[1];


        $sql_insert = "INSERT INTO $GLOBALS[tbl_Chasses] ( Date_Chasse, Territories_id, DA_Numero " .
            " ) VALUES (" .
            " '"   . PLF::__Convert_2_Sql_Date($Chasse_Date) . "', " .
            " '" . $Territoire_id . "', " .
            " '" . $DA_Numero . "' " .
            ")";



        // Execute SQL statement

        try {
            $sql_result = $db_connection->query($sql_insert);
        } catch (Exception $e) {
            echo ("Error : " . $e->getMessage() . "SQL Command : ");
            echo "$sql_insert\n\n";
            return false;
        }

        return true;

        plf::__Close_DB($db_connection);
    }






    /**
     * 
     *    Deleta a "Date de chasse"
     * 
     *      Output    :  Database "PLF_Chasses"
     *
     *      Calling   : Chasse_Date_Delete(TypeTerritoire: "T", Territoire_Name: <Name of territoire), Date_Chasse: <DD-MM-YYY>)
     *                  Chasse_Date_Delete(Territoire_Name: <Name of territoire), Date_Chasse: <DD-MM-YYY>)
     *       
     *      Arguments : TypeTerritoire   --> "T"          Delete on "territories_id"
     *                                   --> Nothing      Delete on "Nomenclature"
     *                  Territoire       --> <territories_id> OR <Nomenclature>
     *                  Date_Chasse      --> (format DD-MM-AAAA)
     *
     *      Return    : True             --> successful delete
     *                  False            --> unsuccessful delete
     * 
     *                  Possible return codes :
     *                      -2 Territoire does not exist
     *                      -4 invalid date   
     *                      -5 MySql error
     *                      xx : integer -> number of records deleted
     */


    public static function Chasse_Date_Delete($Territoire_Name, $Chasse_Date, $TypeTerritoire = NULL)
    {

        $db_connection = PLF::__Open_DB();

        if ($db_connection == NULL) {

            $error_msg = PLF::Get_Error();
            return false;
        }


        // Build SQL statement

        $date_delimiter = "'";       // for MySql

        if (strtolower($GLOBALS['DB_MSAccess_or_MySql'] ?? '') == "msaccess") {
            $date_delimiter = "#";       // for MsAccess

        }

        $sql_Delete = "DELETE FROM $GLOBALS[tbl_Chasses] WHERE " .
            " Date_Chasse = " . $date_delimiter . PLF::__Convert_2_Sql_Date($Chasse_Date) . $date_delimiter . " AND ";


        if (strtolower($TypeTerritoire ?? '') == "t") {
            $sql_Delete .= " Territories_id = '";
        } else {
            $sql_Delete .= " DA_Numero = '";
        }

        $sql_Delete .= $Territoire_Name . "'";




        // Execute SQL statement

        try {
            $sql_result = $db_connection->query($sql_Delete);
        } catch (Exception $e) {
            echo ("Error : " . $e->getMessage() . "SQL Command : ");
            echo "$sql_Delete\n\n";
            return false;
        }

        return true;

        plf::__Close_DB($db_connection);
    }



    /**
     * 
     *    Create the JSON file for a specific territory
     * 
     *      Output    :  Variable containing JSON data
     *
     *      Calling   : Territoire_JSON(Territoire_Name: <Name of territoire>), TypeTerritoire: "T")
     *                  Territoire_JSON(Territoire_Name: <Name of territoire>)
     *       
     *      Arguments : TypeTerritoire   --> "T"          Select on "territories_id"
     *                                   --> Nothing      Select on "Nomenclature"
     *                  Territoire       --> <territories_id> OR <Nomenclature>
     *
     *      Return    : String
     *                  Possible return codes :
     *                      -2 Territoire does not exist
     *                      -5 MySql error
     */

    public static function Territoire_JSON($Territoire_Name, $TypeTerritoire = NULL)
    {

        $Territory_Data = [];

        // Build SQL statement

        $sql_cmd = "SELECT geometry, DA_Numero, Territories_id, Territories_name FROM $GLOBALS[tbl_Territoires] ";
        $sql_cmd .= " WHERE ";

        if (strtolower($TypeTerritoire ?? '') == "t") {
            $sql_cmd .= " Territories_id = '";
        } else {
            $sql_cmd .= " DA_Numero = '";
        }

        $sql_cmd .= $Territoire_Name . "'";



        // Execute SQL statement


        if (strtolower($GLOBALS['DB_MSAccess_or_MySql']) == "msaccess") {
            $Territory_Data = PLF::__Read_Geometry_MSAccess(sql_cmd: $sql_cmd);
        } else {
            $Territory_Data = PLF::__Read_Geometry_MySql(sql_cmd: $sql_cmd);
        }



        $headers = "[\r\n\t{\r\n\t\t\"type\" : \"FeatureCollection\"," .
            "\r\n\t\t\"name\" : \"NewFeatureType\"," .
            "\r\n\t\t\"features\" : [\r\n\t\t\t{\r\n\t\t\t\t\"type\" : \"Feature\",\r\n\t\t\t\t\"geometry\" : ";

        $footer = "\r\n\t\t\t\t}\r\n\t\t\t}\r\n\t\r\n\t\t]\r\n\t}\r\n]";

        $Geometry = $Territory_Data['geometry'];
        $Territories_id = $Territory_Data['Territories_id'];
        $Territories_name = $Territory_Data['Territories_name'];
        $DA_Numero = $Territory_Data['DA_Numero'];

        // convert some string characters to valid ones 

        $Geometry = preg_replace('/("type": "MultiPolygon")/', '\t$1', $Geometry);
        $Geometry = preg_replace('/("coordinates")/', '\t$1', $Geometry);

        $Geometry = preg_replace('/\\\n[\\\t]+(\d)/', "$1", $Geometry);
        $Geometry = preg_replace('/(\d)\\\n[\\\t]+\]/', "$1]", $Geometry);
        $Geometry = preg_replace('/\\\n}"/', "\r\n\t}" . '"', $Geometry);

        $Geometry = preg_replace('/\\\n/', "\r\n\t\t\t", $Geometry);

        $Geometry = preg_replace('/\\\t/', "\t", $Geometry);

        $Geometry = preg_replace('/^"/', "", $Geometry, 1);   // replace first quote by empty string
        $Geometry = preg_replace('/"$/', "", $Geometry, 1);   // replace last quote by empty string

        // $v_Out = preg_replace('/\x00/', "", $v_Out);

        $Nomenclature = ",\r\n\t\t\t\t\"properties\": {\r\n\t\t\t\t\t\"Nomenclature\": \"" . $DA_Numero . "\", \r\n";
        $Territories_id = "\t\t\t\t\t\"Territories_id\": \"" . $Territories_id . "\", \r\n";
        $Territories_name = "\t\t\t\t\t\"Territories_name\": \"" . $Territories_name . "\" ";

        $Geometry = $headers . $Geometry . $Nomenclature .  $Territories_id . $Territories_name . $footer;
        // $Geometry = $headers . $Geometry;




        return $Geometry;
    }



    /**
     *  Internal Database functions
     * 
     */

    private static function __Open_DB()
    {


        switch (strtolower($GLOBALS['DB_MSAccess_or_MySql'])) {



            case strtolower("MSAccess"):
                try {

                    $db_conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=" . $GLOBALS['db_file_name'] . ";Uid=; Pwd=;array(PDO::MYSQL_ATTR_MAX_BUFFER_SIZE=>1024*1024*50)");
                    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    self::$error =  'Error opening MsAccess database' . PHP_EOL;
                    self::$error .= $e->getMessage() . PHP_EOL;
                    return NULL;
                }

                break;





            case strtolower("MySql"):

                try {

                    $db_conn = new mysqli($GLOBALS['MySql_Server'], $GLOBALS['MySql_Login'], $GLOBALS['MySql_Password'], $GLOBALS['MySql_DB']);
                } catch (Exception $e) {
                    self::$error =  'Error opening MySql database' . PHP_EOL;
                    self::$error .= $e->getMessage() . PHP_EOL;
                    return NULL;
                }


                break;
        }





        return $db_conn;
    }


    private static function __Close_DB($db_conn)
    {
        unset($db_conn);
    }


    public static function Get_Error()
    {

        return self::$error;
    }


    /**
     * 
     *  because the value of geometry can be very long, the way it is read is different for MySql and for MsAccess
     * 
     */



    private static function __Read_Geometry_MySql($sql_cmd)
    {
        /**
         * 
         *  Connect to the database and set settings
         *  
         */


        $Territory_Data = [];

        $db_connection = PLF::__Open_DB();

        if ($db_connection == NULL) {

            $error_msg = PLF::Get_Error();
            return NULL;
        }



        // Process SQL records

        try {

            foreach ($db_connection->query($sql_cmd) as $record) {

                $Territory_Data['geometry'] = $record['geometry'];
                $Territory_Data['DA_Numero'] = $record['DA_Numero'];
                $Territory_Data['Territories_id'] = $record['Territories_id'];
                $Territory_Data['Territories_name'] = $record['Territories_name'];

            }
        } catch (Exception $e) {

            self::$error =  'Error SELECT ' . PHP_EOL;
            self::$error .= $e->getMessage() . PHP_EOL;
            self::$error .= $sql_cmd;
            return NULL;
        }


        // Close Database

        PLF::__Close_DB($db_connection);

        return $Territory_Data;
    }




    /**
     * 
     *  because the value of geometry can be very long, the way it is read is different for MySql and for MsAccess
     * 
     */

    private static function __Read_Geometry_MSAccess($sql_cmd)
    {


        $Territory_Data = [];


        /**
         * 
         *  Connect to the database and set settings
         *  
         */


        $db = odbc_connect("Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$GLOBALS[db_file_name]", "", "");



        // Process SQL records

        $result = odbc_exec($db, $sql_cmd);
        odbc_longreadlen($result, 300000);      // !!!!!!!! this is the maximum record length. 



        while (odbc_fetch_row($result) == true) {

            $Territory_Data['geometry'] = odbc_result($result, 1);
            $Territory_Data['DA_Numero'] = odbc_result($result, 2);
            $Territory_Data['Territories_id'] = odbc_result($result, 3);
            $Territory_Data['Territories_name'] = odbc_result($result, 4);
            

        }


        // Close Database

        PLF::__Close_DB($db);



        // return values

        return $Territory_Data;
    }


    /**
     * 
     *  Other internal functions
     * 
     */


    // Convert date in format DD-MM-YYYY to MM-DD-YYYY for SQL statements

    private static function __Convert_2_Sql_Date($Date_DD_MM_YYYY)
    {

        $date_Part = explode("-", $Date_DD_MM_YYYY);

        $d = $date_Part[0];
        $m = $date_Part[1];
        $yyyy = $date_Part[2];

        if (strlen($yyyy) == 2) {
            $yyyy = "20" . $yyyy;
        }

        return "$yyyy-$m-$d";
    }



    private static function __Get_Corresponding_Territoire($db_connection, $Territoire_Name, $TypeTerritoire)
    {

        $sql_cmd = "SELECT Territories_id, DA_Numero from $GLOBALS[tbl_Territoires] WHERE ";
        if (strtolower($TypeTerritoire ?? '') ==  "t") {
            $sql_cmd .= " Territories_id = '" . $Territoire_Name . "' ";
        } else {
            $sql_cmd .= " DA_Numero = '" . $Territoire_Name . "' ";
        }

        $Territories_id = "";
        $DA_Numero = "";

        try {

            foreach ($db_connection->query($sql_cmd) as $record) {

                $Territories_id = $record["Territories_id"];
                $DA_Numero = $record["DA_Numero"];
            }
        } catch (Exception $e) {

            self::$error =  'Error SELECT ' . PHP_EOL;
            self::$error .= $e->getMessage() . PHP_EOL;
            self::$error .= $sql_cmd;
            return NULL;
        }

        if ($Territories_id == "") {
            self::$error =  'No corresponding territories_id/DA_Numero found for territoire = $Territoire_Name ' . PHP_EOL;
            return NULL;
        }

        return [$Territories_id, $DA_Numero];
    }
}

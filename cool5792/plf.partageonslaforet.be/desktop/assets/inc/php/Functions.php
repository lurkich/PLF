<?php

require_once __DIR__ . "/Parameters.php";


class PLF
{

    // private static $error;
    private static $RC = 0;
    private static $RC_Msg = "";
    private static $List_Array = [];

    /**################################################################################
     * 
     *  List de tous les codes erreurs possibles dans l'ensemble des fonctions.
     *
     *       xx : entier >= 0 reprenant le nombre d'enregistrements retournés ou supprimés
     * 
     *################################################################################*/

    private static $Return_Codes = array(

        -1 => "Aucun record trouvé.",
        -2 => "Le territoire (SAISON/TERRITOIRE) n'existe pas.",
        -3 => "Plusieurs enregistrements trouvés pour le territoire (SAISON/TERRITOIRE).",
        -4 => "La date est invalide. Doit être au format JJ-MM-AAAA",
        -5 => "Erreur MySql",
        -6 => "Commande SQL invalide",
        -7 => "Erreur insert",
        -8 => "pas de correspondance entre territoire et nomnclature et vice versa",
        -9 => "La combinaison date chasse / territoire (SAISON/TERRITOIRE) n'existe pas",
        -10 => "La combinaison date chasse / territoire (SAISON/TERRITOIRE) existe déjà",
        -11 => "Le canton n'existe pas",
        -12 => "Le conseil cynégétique n'existe pas",
        -13 => "La base de données MySql n'est pas accessible.",
        -14 => "pas de chasse (SAISON/TERRITOIRE) pour cette date.",
        -15 => "pas de dates pour cette chasse (SAISON/TERRITOIRE)",
        -16 => "Aucun cantons trouvés",
        -17 => "pas de territoire (SAISON/TERRITOIRE) pour ce canton",
        -18 => "pas de territoire (SAISON/TERRITOIRE) pour ce conseil cynégétique",
        -19 => "Le territoire (SAISON/TERRITOIRE) n'existe pas",
        -20 => "Aucun itinéraire trouvé",
        -21 => "L'itinéraire n'existe pas",
        -999 => "Autres erreurs"

    );


    /**
     *    **    **    ******   **        **        **
     *    ****  **    **        **      ****      **
     *    ** ** **    *****      **    **  **    **
     *    **  ****    **          **  **    **  **
     *    **   ***    **           ****      ****
     *    **    **    ******        **        **
     */




    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne la liste des territoires basés sur "N_LOT"
     * 
     *      Input     : Database "plf_spw_territoires"
     *     
     *      Appel     : Get_Territoire_List()
     * 
     *      Arguments : néant
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre de territoires 
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel (voir tableau)
     *                      Array[2] : Array indexé qui contient chacun une associate array
     *                                      TRI SUR "Nomenclature"
     *                                      DISTINCT (s'il y a plusieurs territoire avec le même id, seul le premier est sélectionné.)
     *                                 Structure - Array[<index>] = ["Territories_id   = <Territories_id>, 
     *                                                               "Territories_name = <Territories_Name>]
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/

    public static function Get_Territoire_List(string $Saison = null): array | false
    {


        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];

        if (empty($Saison)) {
            $Saison = self::__Compute_Saison();
        }

        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array);;
        }


        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT DISTINCT KEYG, SAISON, N_LOT 
                    FROM $GLOBALS[spw_tbl_territoires] 
                    WHERE SAISON = $Saison  
                    ORDER BY SAISON, N_LOT";

        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -19;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }


        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }


        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            array_push(self::$List_Array, [
                "KEYG" => $value["KEYG"],
                "DA_Numero" => $value["N_LOT"],
                "DA_Nom" => "N/A",
                "DA_Saison" => $value["SAISON"],
                "Territories_id" => "obsolete",
                "Territories_Name" => "obsolete",
            ]);

            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }



    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne toutes les informations concernant un territoire "N_LOT"
     * 
     *      Input     : Database "view_spw_territoires"
     *     
     *      Appel     : Get_Territoire_Info(<numéro de territoire>)
     * 
     *      Arguments : Numéro de territoire = N_LOT
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre d'information pour le territoire sélectionné 
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Associative array qui contient toutes les informations du territoire ("N_LOT")
     *                                      sans objet
     *                                      DISTINCT (s'il y a plusieurs territoire avec le même numero, seul le premier est sélectionné.)
     *                                 Structure - Array[clé] = valeur
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/



    public static function Get_Territoire_Info(string $Territoire_Name, string $Saison = null): array | false
    {


        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];

        if (empty($Saison)) {
            $Saison = self::__Compute_Saison();
        }

        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array);;
        }

        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT DISTINCT KEYG,
                                    SAISON,
                                    N_LOT,
                                    CODESERVICE,
                                    CANTONNEMENT,
                                    FIRST_CANTON,
                                    tel_canton,
                                    direction_canton,
                                    email_canton,
                                    attache_canton,
                                    CP_canton,
                                    localite_canton,
                                    rue_canton,
                                    numero_canton,
                                    latitude_canton,
                                    longitude_canton,
                                    CODE_UGC,
                                    NOM_UGC,
                                    DESCRIPTION_UGC,
                                    VALIDE_UGC,
                                    TITULAIRE_ADH_UGC,
                                    President_UGC,
                                    Secretaire_UGC,
                                    email_UGC,
                                    CP_UGC,
                                    localite_UGC,
                                    rue_UGC,
                                    numero_UGC,
                                    latitude_UGC,
                                    longitude_UGC,
                                    site_internet_UGC,
                                    logo_UGC,
                                    DATE_MAJ
                    FROM $GLOBALS[spw_view_territoires] 
                    WHERE N_LOT = $Territoire_Name 
                    AND SAISON = $Saison
                    ORDER BY SAISON, N_LOT";












        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();


        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -19;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);
            }
        }



        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {


            array_push(self::$List_Array, [
                "KEYG" => $value["KEYG"],
                "DA_Numero" => $value["N_LOT"],
                "DA_Saison" => $value["SAISON"],
                "Territories_id" => "obsolete",
                "Territories_Name" => "obsolete",
                "CODESERVICE" => $value["CODESERVICE"],
                "num_canton" => $value["CANTONNEMENT"],
                "nom_canton" => $value["FIRST_CANTON"],
                "Code_CC" => $value["code_UGC"],
                "Nom_CC" => $value["nom_UGC"],
                "Description_CC" => $value["description_UGC"],
                "VALIDE_UGC" => $value["valide_UGC"],
                "TITULAIRE_ADH_UGC" => $value["TITULAIRE_ADH_UGC"],
                "DA_Nom" => "N/A",
                "TITULAIRE_" => "N/A",
                "NOM_TITULA" => "N/A",
                "PRENOM_TIT" => "N/A",
                "TITULAIRE1" => "N/A",
                "COMMENTAIR" => "N/A",
                "DATE_MAJ" => $value["DATE_MAJ"],
                "ESRI_OID" => "Pas nécessaire",
                "tel_canton" => $value["tel_CANTON"],
                "direction_canton" => $value["direction_CANTON"],
                "email_canton" => $value["email_CANTON"],
                "attache_canton" => $value["attache_CANTON"],
                "CP_canton" => $value["CP_CANTON"],
                "localite_canton" => $value["localite_CANTON"],
                "rue_canton" => $value["rue_CANTON"],
                "numero_canton" => $value["numero_CANTON"],
                "latitude_canton" => $value["latitude_CANTON"],
                "longitude_canton" => $value["longitude_CANTON"],
                "President_CC" => $value["president_UGC"],
                "Secretaire_CC" => $value["secretaire_UGC"],
                "email_CC" => $value["email_UGC"],
                "CP_CC" => $value["cp_UGC"],
                "localite_CC" => $value["localite_UGC"],
                "rue_CC" => $value["rue_UGC"],
                "numero_CC" => $value["numero_UGC"],
                "latitude_CC" => $value["latitude_UGC"],
                "longitude_CC" => $value["longitude_UGC"],
                "site_internet_CC" => $value["site_internet_UGC"],
                "logo_CC" => $value["logo_UGC"],
                "num_triage" => "N/A",
                "nom_triage" => "N/A",
                "nom_Prepose" => "N/A",
                "gsm_Prepose" => "N/A",
            ]);




            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }


    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne la liste des territoires par date de chasse
     * 
     *      Input     : Database "PLF_spw_chasses_fermeture"
     *     
     *      Appel     : Get_Chasse_By_Date(Chasse_Date: <Date Chasse>)
     * 
     *      Arguments : Date_Chasse    = date de la chasse (format JJ-MM-AAAA et doit être valide)     * 
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre de territoires
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Array indexé qui contient un array avec le numéro de territoire et la saison
     *                                      TRI sur saison et numéro de territoire
     *                                 Structure - Array[index] = Array[<Numero du territoire>,<SAISON>[]
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/


    public static function Get_Chasse_By_Date(string $Chasse_Date, string $Saison = null): array | false
    {


        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];

        if (empty($Saison)) {
            $Saison = self::__Compute_Saison();
        }

        // check date validity. Format DD-MM-YYYY et date is valid

        $Errors_Values = self::__Check_If_Date_Is_Valid($Chasse_Date);

        if (!empty($Errors_Values)) {

            self::$RC = -4;
            self::$RC_Msg = $Errors_Values;

            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }



        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array);;
        }



        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $date_Chasse_Sql = PLF::__Convert_2_Sql_Date(Date_DD_MM_YYYY: $Chasse_Date);

        $sql_cmd = "SELECT KEYG,
                           SAISON,
                           N_LOT
                    FROM $GLOBALS[spw_chasses] 
                    WHERE DATE_CHASSE = '$date_Chasse_Sql' AND SAISON = $Saison
                    ORDER BY SAISON, N_LOT";

        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -14;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }

        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            array_push(self::$List_Array, [
                "KEYG" => $value["KEYG"], 
                "DA_Saison" => $value["SAISON"],
                "DA_Numero" => $value["N_LOT"],
                ]);




            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }


    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne la liste des territoires par date de chasse
     * 
     *      Input     : Database "PLF_spw_chasses_fermeture"
     *     
     *      Appel     : Get_Chasse_By_Date(Chasse_Date: <Date Chasse>)
     * 
     *      Arguments : Date_Chasse    = date de la chasse (format JJ-MM-AAAA et doit être valide)     * 
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre de territoires
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Array indexé qui contient un array avec le numéro de territoire et la saison
     *                                      TRI sur saison et numéro de territoire
     *                                 Structure - Array[index] = Array[<Numero du territoire>,<SAISON>[]
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/


    public static function Get_Chasse_By_Territoire(string $Territoire_Name, string $Saison = null): array | false
    {


        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];

        if (empty($Saison)) {
            $Saison = self::__Compute_Saison();
        }

        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array);;
        }



        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);


        $sql_cmd = "SELECT DATE_CHASSE
                     FROM $GLOBALS[spw_chasses] 
                     WHERE N_LOT = $Territoire_Name
                     AND SAISON = $Saison
                     ORDER BY DATE_CHASSE";

        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -15;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }




        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            $sqlDate = new DateTime($value["DATE_CHASSE"]);
            array_push(self::$List_Array, $sqlDate->format('d-m-Y'));

            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }


    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne la liste des cantons
     * 
     *      Input     : Database "PLF_spw_cantonnements"
     *     
     *      Appel     : SPW_Get_Canton_List()
     * 
     *      Arguments : Néant     * 
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre de cantons
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Associative array qui contient chacun une associate array
     *                                      TRI SUR "Num_Canton"
     *                                      DISTINCT (s'il y a plusieurs cantons avec le même numéro, seul le premier est sélectionné.)
     *                                 Structure - Array[<num_canton>] = <infos du canton>
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/

    public static function Get_Canton_List(): array | false

    {
        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];


        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array);;
        }


        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT DISTINCT CAN, 
                                    FIRST_CANTON,
                                    tel,
                                    direction,
                                    email,
                                    attache,
                                    CP,
                                    localite,
                                    rue,
                                    numero,
                                    latitude,
                                    longitude
                    FROM $GLOBALS[spw_view_cantonnements] 
                    ORDER BY CAN";


        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -16;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }



        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            self::$List_Array[$value["CAN"]] = [
                            "nom" => $value["FIRST_CANTON"],
                            "tel" => $value["tel"],
                            "direction" => $value["direction"],
                            "email" => $value["email"],
                            "attache" => $value["attache"],
                            "CP" => $value["CP"],
                            "localite" => $value["localite"],
                            "rue" => $value["rue"],
                            "numero" => $value["numero"],
                            "latitude" => $value["latitude"],
                            "longitude" => $value["longitude"]
            ];


            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }


    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne la liste des territoires par numéro de canton
     * 
     *      Input     : Database "view_spw_territoires"
     *     
     *      Appel     : Get_Territoire_By_Canton(Num_Canton: <numéro de canton>)
     * 
     *      Arguments : Num_Canton     = <Numéro du canton>
     *                  
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre de territoires
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Array indexé qui contient un array avec le numéro du territoires
     *                                      TRI sur "numéro de territoire"
     *                                      DISTINCT : n'affiche qu'une seule occurence territories
     *                                 Structure - Array[index] = Array[<numéro de territoire>]
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/


    public static function Get_Territoire_By_Canton(string $Num_Canton, string $Saison = null): array | false
    {

        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];

        if (empty($Saison)) {
            $Saison = self::__Compute_Saison();
        }

        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array
            );;
        }



        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT DISTINCT KEYG, SAISON, N_LOT 
                    FROM $GLOBALS[spw_view_territoires] 
                    WHERE CANTONNEMENT = '$Num_Canton'
                    AND SAISON = $Saison
                    ORDER BY SAISON, N_LOT";


        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -17;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }




        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            array_push(self::$List_Array, [
                "KEYG" => $value["KEYG"],
                "DA_Numero" => $value["N_LOT"],
                "DA_Saison" => $value["SAISON"],


            ]);


            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }


    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne la liste des conseils cynégétiques
     * 
     *      Input     : Database "plf_spw_cc"
     *     
     *      Appel     : SPW_Get_CC_List()
     * 
     *      Arguments : Néant     * 
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre de cantons
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Associative array qui contient chacun une associate array
     *                                      TRI SUR "Code_CC"
     *                                      DISTINCT (s'il y a plusieurs cantons avec le même code_cc, seul le premier est sélectionné.)
     *                                 Structure - Array[<Code_CC>] = ["nom_CC"]      = <nom_CC>, 
     *                                                                ["president"]   = <president>,
     *                                                                ["secreataire"] = <secretaire> 
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/


    public static function Get_CC_List(): array | false
    {

        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];


        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(
                self::$RC, self::$RC_Msg, self::$List_Array
            );;
        }

        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT DISTINCT ugc,
                                    nomugc,
                                    president,
                                    secretaire,
                                    email,
                                    cp,
                                    localite,
                                    rue,
                                    numero,
                                    latitude,
                                    longitude,
                                    site_internet,
                                    logo,
                                    description 
                    FROM $GLOBALS[spw_view_cc] 
                    ORDER BY ugc";


        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -17;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }





        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            self::$List_Array[$value["ugc"]] = [
                "nom" => $value["nomugc"],
                "description" => $value["description"],
                "president" => $value["president"],
                "secretaire" => $value["secretaire"],
                "email" => $value["email"],
                "CP" => $value["CP"],
                "localite" => $value["localite"],
                "rue" => $value["rue"],
                "numero" => $value["numero"],
                "site_internet" => $value["site_internet"],
                "logo" => $value["logo"],
                "latitude" => $value["latitude"],
                "longitude" => $value["longitude"],
            ];


            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }




    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne la liste des territoires par conseil cynégétique
     * 
     *      Input     : Database "view_spw_territoires"
     *     
     *      Appel     : SPW_Get_Territoire_By_CC(Num_Canton: <numéro de canton>)
     * 
     *      Arguments : Code_CC     = <code du conseil cynégétique>
     *                  
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre de territoires
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Array indexé qui contient un array avec le numéro du territoires
     *                                      TRI sur "numéro de territoire"
     *                                      DISTINCT : n'affiche qu'une seule occurence territories
     *                                 Structure - Array[index] = Array[<numéro de territoire>]
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/


    public static function Get_Territoire_By_CC(string $Code_CC, string $Saison = null): array | false
    {

        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];

        if (empty($Saison)) {
            $Saison = self::__Compute_Saison();
        }

        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"], $_SERVER["MySql_Login"], $_SERVER["MySql_Password"]);

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(
                self::$RC, self::$RC_Msg, self::$List_Array
            );;
        }



        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT DISTINCT KEYG, SAISON, N_LOT 
                     FROM $GLOBALS[spw_view_territoires] 
                     WHERE SAISON = $Saison
                     AND CODE_UGC = '$Code_CC'
                     ORDER BY N_LOT";


        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -18;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }




        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            array_push(self::$List_Array, [
                "KEYG" => $value["KEYG"],
                "DA_Numero" => $value["N_LOT"],
                "DA_Saison" => $value["SAISON"],
            ]);


            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array
        );
    }

    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Crée un fichier json pour un territoire donné
     * 
     *      Input     : plf_swp_territoires
     *     
     *      Appel     : SPW_Territoire_JSON(<numéro de territoire>)
     * 
     *      Arguments : numéro de territoire 
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre de cantons
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Array indexé qui contient le SHAPE du territoire
     *                                 Structure - Array[0] = SHAPE 
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/


    public static function Territoire_JSON(string $N_LOT, string $Saison = null) : array | false
    {

        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];

        if (empty($Saison)) {
            $Saison = self::__Compute_Saison();
        }


        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(
                self::$RC, self::$RC_Msg, self::$List_Array
            );;
        }



        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT DISTINCT SHAPE,
                                    N_LOT
                    FROM $GLOBALS[spw_tbl_territoires] 
                    WHERE SAISON = $Saison 
                    AND N_LOT = '$N_LOT'";


        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -2;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }





        // process the data and return the result

        self::$RC = 0;

        $value = $results[0];

        $Geometry = $value['SHAPE'];
        $Territories_name = "N/A";
        $DA_Numero = $value['N_LOT'];



    //     $headers = '
    // {
    // "type" : "FeatureCollection",
    // "name" : "NewFeatureType",                    
    //     "features" : [
    //         {
    //           "type" : "Feature",
    //     ';


        $headers = '
            {
                "type" : "Feature",';


        $Geometry = '      "geometry" : ' . $Geometry;
        $Geometry .= ",";


        $properties = '
              "properties": {
                  "Numero Lot": "<N_LOT>", 
                  "Nom": "<TERRITOIRE_NAME>"
              }
            }';

        $properties = preg_replace("/<N_LOT>/", $N_LOT, $properties);
        $properties = preg_replace("/<TERRITOIRE_NAME>/", "N/A", $properties);
    

        $footer = "";
        // $footer = '
        //     }
        //   ]
        // }
        //     ';


        $Geometry = $headers . $Geometry . $properties .  $footer;

        return array(self::$RC, self::$RC_Msg, $Geometry);

    }





    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne la liste des itineraires
     * 
     *      Input     : Database "plf_cgt_itineraires"
     *     
     *      Appel     : Get_Itineraires_List()
     * 
     *      Arguments : néant
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre d'itinéraires 
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel (voir tableau)
     *                      Array[2] : Array indexé qui contient chacun une associate array
     *                                      TRI SUR "nom"
     *                                 Structure - Array[<index>] = ["Itineraire_id   = <Itineraire_id>, 
     *                                                               "Itineraire_nom = <Nom>]
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/

    public static function Get_Itineraires_List(): array
    {


        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];


        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array);;
        }


        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT itineraire_id, nom  
                    FROM $GLOBALS[cgt_itineraires]  
                    ORDER BY nom";

        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -20;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }


        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }


        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            array_push(self::$List_Array, [
                "itineraire_id" => $value["itineraire_id"],
                "nom" => $value["nom"],
            ]);

            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }

 
    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne toutes les informations concernant un itineraire
     * 
     *      Input     : Database "tbl_cgt_itineraires"
     *     
     *      Appel     : Get_Itineraire_Info(<itineraire_id>)
     * 
     *      Arguments : Itineraire_id
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre d'information pour le territoire sélectionné 
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel
     *                      Array[2] : Associative array qui contient toutes les informations de l'itineraire
     *                                 Structure - Array[clé] = valeur
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/



    public static function Get_Itineraire_Infos(int $itineraire_id): array
    {


        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];

        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array);;
        }

        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT DISTINCT itineraire_id,
                                    nom,
                                    organisme,
                                    localite,
                                    urlweb,
                                    idreco,
                                    distance,
                                    typecirc,
                                    signaletique,
                                    hdifmin,
                                    hdifmax,
                                    gpx_url
                    FROM $GLOBALS[cgt_itineraires] 
                    WHERE itineraire_id = $itineraire_id"; 

        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();


        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -21;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }

        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);
            }
        }



        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

           
            array_push(self::$List_Array, [
                "itineraire_id" => $value["itineraire_id"],
                "nom" => $value["nom"],
                "organisme" => $value["organisme"],
                "localite" => $value["localite"],
                "urlweb" => $value["urlweb"],
                "idreco" => $value["idreco"],
                "distance" => floatval($value["distance"]),
                "typecirc" => $value["typecirc"],
                "signaletique" => $value["signaletique"],
                "hdifmin" => $value["hdifmin"],
                "hdifmax" => $value["hdifmax"],
                "gpx_url" => $value["gpx_url"],
                 ]);




            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array);
    }


    /**-------------------------------------------------------------------------------------------------------------------------------------------
     * 
     *    Retourne Date et status du job cron
     * 
     *      Input     : Database "plf_infos"
     *     
     *      Appel     : Get_LastRunTime()
     * 
     *      Arguments : N/A
     * 
     *      Output    : Array contenant 3 éléments
     *                      Array[0] : Code retour.
     *                                  xx : entier >= 0 contenant le nombre d'éléments 
     *                                  autres : voir le tableau
     *                      Array[1] : Message d'erreur éventuel (voir tableau)
     *                      Array[2] : Array indexé qui contient chacun une associate array
     *                                      TRI SUR "nom"
     *                                 Structure - Array[<nom du cron>] = ["date d'execution   = <date>, 
     *                                                                     "résultat = <résultat>]
     * 
     *-------------------------------------------------------------------------------------------------------------------------------------------*/

    public static function Get_LastRunTime(): array
    {


        self::$RC = 0;
        self::$RC_Msg = "";
        self::$List_Array = [];


        // Make a new database connection and test if connection is OK

        $database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"], $_SERVER["MySql_Login"], $_SERVER["MySql_Password"]);

        $db_conn = $database->getConnection();

        if ($db_conn == false) {

            self::$RC = -13;
            self::$RC_Msg = $database->Get_Error_Message();

            return array(self::$RC, self::$RC_Msg, self::$List_Array);;
        }


        // Build SQL statement and pass it to the database and prccess the statement.

        $gateway = new Functions_Gateway($database);

        $sql_cmd = "SELECT Infos_Name, Infos_Date, Infos_Value  
                     FROM $GLOBALS[plf_infos]  
                     ORDER BY Infos_Name";

        $gateway->set_Sql_Statement($sql_cmd);

        $results = $gateway->DB_Query();

        // Check if everything went OK

        if (count($results) == 0) {
            self::$RC = -99;
            self::$RC_Msg = self::$Return_Codes[self::$RC];
            return array(self::$RC, self::$RC_Msg, self::$List_Array);
        }


        if ($results[0] == "error") {

            switch ($results[1]) {

                case 1054:                 // invalid column name     
                case 1064:                 // SQL syntax error
                    self::$RC = -6;
                    self::$RC_Msg = $results[2];
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);

                default:                    // other errors
                    self::$RC = -999;
                    self::$RC_Msg = $database->Get_Error_Message();
                    return array(self::$RC, self::$RC_Msg, self::$List_Array);;
            }
        }


        // process the data and return the result

        self::$RC = 0;

        foreach ($results as $result => $value) {

            self::$List_Array[$value["Infos_Name"]] = array(
                "Infos_Date" => $value["Infos_Date"],
                "Infos_Value" => $value["Infos_Value"]
            );

            self::$RC++;      // the number of records = last $value (index number) + 1

        }


        return array(self::$RC, self::$RC_Msg, self::$List_Array
        );
    }
 
 
     private static function __Compute_Saison() : string 
    {

        $current_year = (int) date("Y");
        $current_month = (int) date("m");

        if ( $current_month >= 1 and $current_month <= 3) {
            $current_year--;
        }

        return $current_year;


    }
 
 

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



    // Check if the date has a valid format and is valid

    private static function __Check_If_Date_Is_Valid($Date)
    {

        $Date_Format = 'd-m-Y';

        $Error_Message = "";

        $date_from_format = DateTimeImmutable::createFromFormat($Date_Format, $Date);

        if ($date_from_format == false) {

            $Error_Message .= "Le format de la date n'est pas correct.";
            return $Error_Message;
        } else {
            $Last_Errors = DateTimeImmutable::getLastErrors();

            if (($Last_Errors["warning_count"] == 0) and
                ($Last_Errors["error_count"] == 0)
            ) {

                $Error_Message = "";
                return $Error_Message;
            } else {

                $Error_Message .= 'La date est invalide. - ';

                foreach ($Last_Errors["warnings"] as $key => $msg) {

                    $Error_Message .= "$msg - ";
                }

                foreach ($Last_Errors["errors"] as $key => $msg) {

                    $Error_Message .= "$msg - ";
                }

                return $Error_Message;
            }
        }
    }


}

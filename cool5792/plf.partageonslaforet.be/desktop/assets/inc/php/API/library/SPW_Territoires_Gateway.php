<?php

use PhpOffice\PhpSpreadsheet\Helper\Handler;

class SPW_Territoires_Gateway
{


    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();

    }



    public function New_Territoire(array $data) {


        $sql = "INSERT INTO " . $GLOBALS["spw_tbl_territoires_1"] . " (" .
                    " OBJECTID," .
                    " KEYG," .
                    " SAISON," .
                    " N_LOT," .
                    " NUGC," .
                    " CODESERVICE," .
                    " TITULAIRE_ADH_UGC," . 
                    " DATE_MAJ," .
                    " SHAPE" .
                "  ) VALUES (" .
                    " :OBJECTID," .
                    " :KEYG," .
                    " :SAISON," .
                    " :N_LOT," .
                    " :NUGC," .
                    " :CODESERVICE," .
                    " :TITULAIRE_ADH_UGC," .
                    " :DATE_MAJ," . 
                    " :SHAPE)";


        try {

            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindValue(":OBJECTID", $data["OBJECTID"], PDO::PARAM_INT);
            $stmt->bindValue(":KEYG", $data["KEYG"], PDO::PARAM_STR);
            $stmt->bindValue(":SAISON", $data["SAISON"], PDO::PARAM_INT);
            $stmt->bindValue(":N_LOT", $data["N_LOT"], PDO::PARAM_STR);

            $data["SHAPE"] = preg_replace('/\n\s+/', ' ', $data["SHAPE"]);
            $stmt->bindValue(":SHAPE", $data["SHAPE"] ?? "", PDO::PARAM_LOB);
            $stmt->bindValue(":NUGC", $data["NUGC"], PDO::PARAM_INT);
            $stmt->bindValue(":CODESERVICE", $data["CODESERVICE"], PDO::PARAM_STR);
            $stmt->bindValue(":TITULAIRE_ADH_UGC", $data["TITULAIRE_ADH_UGC"], PDO::PARAM_STR);
            $stmt->bindValue(":DATE_MAJ", $data["DATE_MAJ"], PDO::PARAM_STR);



            $stmt->execute();
            SPW_Territoires_Controller::__Increment_Total_Territoires();
            array_push(errorHandler::$Run_Information, ["Info", "new territoire : KEYG = " . $data["KEYG"] . PHP_EOL]);
            return $this->conn->lastInsertId();

        } catch (pdoException $e) {

                $SQL_Error = $e->errorInfo[1];

                switch ($SQL_Error) {
                    case 1062:
                        SPW_Territoires_Controller::__Increment_Duplicate_Territoires();
                        array_push(errorHandler::$Run_Information, ["Warning", "Duplicate record for territoire : KEYG = " . $data["KEYG"]  . PHP_EOL]);
                        break;
                    default:
                        throw new pdoDBException(0, $e, "SQL Error :" . $this->rebuildSql($sql,$data));

                }
            } catch (Exception $e) {

            }

    }



 
    public function drop_DB_table(string $tablename): bool {

        $sql = "DROP TABLE IF EXISTS $tablename";

        $stmt = $this->conn->prepare($sql);

        $RC = $stmt->execute();

        if ($RC) {
            return json_encode("Table successfully deleted.");
        }

        return json_encode("Error deleting table. " . $stmt->errorInfo());
    
    }


    public function Create_DB_Table_Territoires(string $tablename): bool 
    {


        $sql = "CREATE TABLE $tablename (
                    `OBJECTID` INT NULL DEFAULT NULL,
                    `SAISON` SMALLINT NOT NULL,
                    `KEYG` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `N_LOT` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `CODESERVICE` VARCHAR(9) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                    `NUGC` SMALLINT NULL DEFAULT NULL,
                    `TITULAIRE_ADH_UGC` VARCHAR(1) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                    `DATE_MAJ` DATE NULL DEFAULT NULL,
                    `SHAPE` MEDIUMBLOB NULL DEFAULT NULL,
                    PRIMARY KEY (`N_LOT`, `SAISON`) USING BTREE,
                    UNIQUE INDEX `uk_Saison_N_lot` (`SAISON`, `N_LOT`) USING BTREE)
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB;";
            
        try {

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

        } catch (pdoException $e) {

            $SQL_Error = $e->errorInfo[1];

            switch ($SQL_Error) {

                default:
                    throw new pdoDBException(0, $e, "SQL Error :" . $sql);

            }
        } catch (Exception $e) {

        }    


        

        return true;

    }









    private function rebuildSql($string,$data) {
        $indexed=$data==array_values($data);
        foreach($data as $k=>$v) {
            if(is_string($v)) $v="'$v'";
            if(is_null($v)) $v = "''";
            if($indexed) $string=preg_replace('/\?/',$v,$string,1);
            else $string=str_replace(":$k",$v,$string);
        }
        return $string;
    }

}
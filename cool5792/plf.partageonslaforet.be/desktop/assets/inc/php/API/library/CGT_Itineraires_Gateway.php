<?php

use PhpOffice\PhpSpreadsheet\Helper\Handler;

class CGT_Itineraires_Gateway
{


    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();

    }



    public function New_Itineraire(array $data) {


        $sql = "INSERT INTO " . $GLOBALS["cgt_itineraires"] . " (" .
                    " nom," .
                    " organisme," .
                    " localite," .
                    " urlweb," .
                    " idreco," .
                    " distance," .
                    " typecirc," .
                    " signaletique," . 
                    " hdifmin," .
                    " hdifmax," .
                    " gpx_url" .
                "  ) VALUES (" .
                    " :nom," .
                    " :organisme," .
                    " :localite," .
                    " :urlweb," .
                    " :idreco," .
                    " :distance," .
                    " :typecirc," .
                    " :signaletique," .
                    " :hdifmin," . 
                    " :hdifmax," . 
                    " :gpx_url)";


        try {


                    /**
         * 
         * Replace some invalid database interpretation characters in fields with valid ones
         */


            $stmt = $this->conn->prepare($sql);
            
            $stmt->bindValue(":nom", $data["nom"], PDO::PARAM_STR);
            $stmt->bindValue(":organisme", empty($data["organisme"]) ? "" : $data["organisme"], PDO::PARAM_STR);
            $stmt->bindValue(":localite", empty($data["localite"]) ? "" : $data["localite"], PDO::PARAM_STR);
            $stmt->bindValue(":urlweb", empty($data["urlweb"]) ? "" : $data["urlweb"], PDO::PARAM_STR);
            $stmt->bindValue(":idreco", $data["idreco"], PDO::PARAM_STR);
            $stmt->bindValue(":distance",  empty($data["distance"]) ? 0 : $data["distance"], PDO::PARAM_INT);
            $stmt->bindValue(":typecirc", empty($data["typecirc"]) ? "" : $data["typecirc"], PDO::PARAM_STR);
            $stmt->bindValue(":signaletique", empty($data["signal"]) ? "" : $data["signal"], PDO::PARAM_STR);
            $stmt->bindValue(":hdifmin", empty($data["hdifmin"]) ? 0 : $data["hdifmin"], PDO::PARAM_INT);
            $stmt->bindValue(":hdifmax", empty($data["hdifmax"]) ? 0 : $data["hdifmax"], PDO::PARAM_INT);
            $stmt->bindValue(":gpx_url", $data["gpx_url"] ?? "", PDO::PARAM_STR);


            $stmt->execute();

            CGT_Itineraires_Controller::__Increment_Total_Itineraires();
            return $this->conn->lastInsertId();

        } catch (pdoException $e) {

                $SQL_Error = $e->errorInfo[1];

                switch ($SQL_Error) {
                    case 1062:
                        CGT_Itineraires_Controller::__Increment_Duplicate_Itineraires();
                        array_push(errorHandler::$Run_Information, ["Warning", "Duplicate record for itineraire = " . $data["nom"]  . PHP_EOL]);
                        break;
                    default:
                        throw new pdoDBException(0, $e, "SQL Error : " . $e->getMessage() . " --- " . $this->rebuildSql($sql,$data));

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


    public function Create_DB_Table_Itineraires(string $tablename): bool 
    {

        // `SHAPE` MEDIUMBLOB DEFAULT NULL,
        $sql = "CREATE TABLE $tablename (
                    `itineraire_id` INT NOT NULL AUTO_INCREMENT,
                    `nom` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `organisme` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `localite` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `urlweb` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `idreco` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `distance` DECIMAL(5, 1) DEFAULT NULL,
                    `typecirc` VARCHAR(50) DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                    `signaletique` VARCHAR(50) DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                    `hdifmin` SMALLINT DEFAULT NULL,
                    `hdifmax` SMALLINT DEFAULT NULL,
                    `gpx_url` VARCHAR(200) DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                    INDEX itineraire_id (itineraire_id),
                    PRIMARY KEY (`itineraire_id`) USING BTREE,
                    UNIQUE INDEX `uk_nom` (`nom`) USING BTREE)
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
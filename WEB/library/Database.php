<?php

class Database 
{

    private string $_error_message;


    // Construct the class

    public function __construct(private $host,
                                private $name,
                                private $user,
                                private $password) {
    
        $this->_error_message = "";
    
    }



    // create the connection to the database

    public function getConnection(): PDO | false
    {

        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

        try {

            $connection = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_EMULATE_PREPARES => false,
                pdo::ATTR_STRINGIFY_FETCHES => false
            ]);
    
        } catch (PDOException $e) {


            switch ($e->getCode()) {
                case 2002:                      // Database is unreachable
                    throw new pdoDBException(0, $e, "Unable to access database : " . $e->getMessage(), );
                
                default:
                    throw new pdoDBException(0, $e, "unexpected error : " . $e->getMessage(), );

                }

        } catch (Exception $e) {

        }

        return $connection;


    }




    // retrieve the last error message.


    public function Get_Error_Message()
    {

        return $this->_error_message;
    }


}
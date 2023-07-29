<?php

class ErrorHandler {

    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }


    public static function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline): bool 
        {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);

        }

}


class pdoDBException extends PDOException {

    private int $_code;
    private string $_msg;

    public function __construct(int $SQLerrorCode, PDOException $e, string $customString) {

        $this->_code = 0;
        $this->_msg = "";

        if(strstr($e->getMessage(), 'SQLSTATE[')) {
            preg_match('/SQLSTATE\[(\w+)\]: (.+): (\d+) (.*)/', $e->getMessage(), $reg_matches);
        }

        $SQLerrorCode = $reg_matches[3];
        
        switch ($SQLerrorCode) {
            case 1062:
                $this->_code = $SQLerrorCode;
                $this->_msg =  "Duplicate record for KEYG : " . $customString;
                break;


            case 42000:
                $this->_code = $reg_matches[3];
                $this->_msg =  $reg_matches[0] . "SQL Statment : " . $customString;
                $this->_msg = preg_replace("/\r\n/", "", $this->_msg);
                $this->_msg = preg_replace("/\s+/", " ", $this->_msg);            
                break;

            default:            
                $this->_code = $reg_matches[3];
                $this->_msg =  $reg_matches[0] . "SQL Statment : " . $customString;
                $this->_msg = preg_replace("/\r\n/", "", $this->_msg);
                $this->_msg = preg_replace("/\s+/", " ", $this->_msg);            
                break;
        }


        parent::__construct($this->_msg , (int) $this->_code, $e);

        }


    }
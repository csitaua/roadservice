<?php

class Database{
 
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;
  
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
 	}
}
?>
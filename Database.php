<?php

class Database
{
    private $host='localhost';
    private $db_name='social_media_app';
    private $username='root';
    private $password='';

    public $connection;

    public function getConnection(){
 
        $this->connection = null;        

        try{
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);

        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
            exit();
        }
 
        return $this->connection;
    }
}





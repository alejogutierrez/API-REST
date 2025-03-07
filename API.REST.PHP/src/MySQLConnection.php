<?php

namespace App;

use PDO;
use PDOException;

class MySQLConnection{
    private static $instance;
    private $host = 'localhost:8889';
    private $user = 'test';
    private $password = 'test';
    private $database = 'code_pills';

    private final function __construct()
    {      
    }
    
    static function getInstance(): MySQLConnection
    {
        if(!self::$instance) {
          self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO{
      //$hostDB = "mysql:host=".$this->host.";dbname=".$this->database.";";
        try{
            $connection = new PDO($hostDB,$this->user,$this->password);
            $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch(PDOException $e){
            die("ERROR: ".$e->getMessage());
        }
    }
}

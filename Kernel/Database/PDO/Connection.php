<?php

class Connection {

    /**
     * The underlying PDO connection.
     *
     * @var \PDO
     */
    protected $connection;

    public function __construct() {

        try {

            $database_engine = require('./config/database.php');
    
            $driver = $database_engine['connections']['mysql']['driver'];
        
            $database_host = $database_engine['connections']['mysql']['host'];
        
            $database_username = $database_engine['connections']['mysql']['username'];
        
            $database_password = $database_engine['connections']['mysql']['password'];
        
            $database_name = $database_engine['connections']['mysql']['database'];
    
            $this->connection = new PDO("$driver:host=$database_host;dbname=$database_name", $database_username, $database_password);
        }
        
        catch (PDOException $e) {
    
            echo '<p style="color: #FF0000; font-size: 18px;">Error when connecting to database: ' . $e->getMessage() . '</p>';
    
            return 500;
        }
    }

    /**
     * @return PDO Instance 
     */
    public static function connection() {
        
        return (new self)->connection;
    }
}

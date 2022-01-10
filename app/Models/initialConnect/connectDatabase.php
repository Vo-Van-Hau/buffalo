<?php 

    try {

        $database_engine = require('./config/database.php');

        $servername = $database_engine['connections']['mysql']['driver'];
    
        $database_host = $database_engine['connections']['mysql']['host'];
    
        $database_username = $database_engine['connections']['mysql']['username'];
    
        $database_password = $database_engine['connections']['mysql']['password'];
    
        $database_name = $database_engine['connections']['mysql']['database'];
    
        $conn = new PDO("mysql:host=$database_host;dbname=$database_name", $database_username, $database_password);

        return $conn;
    }
    
    catch (PDOException $err) {

        echo "Connection failed: " . $e->getMessage();
    }
    
?>
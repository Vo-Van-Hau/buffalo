<?php 

    try {

      

        $database_engine = require('./config/database.php');

        $servername = $database_engine['connections']['mysql']['driver'];
    
        $database_host = $database_engine['connections']['mysql']['host'];
    
        $database_username = $database_engine['connections']['mysql']['username'];
    
        $database_password = $database_engine['connections']['mysql']['password'];
    
        $database_name = $database_engine['connections']['mysql']['database'];

        $err = false;

        if(!isset($database_name) || !$database_name) {

            echo '<p style="color: #FF0000; font-size: 18px;">Database name is empty...!</p>';

            $err = true;
        }

        if(!isset($database_username) || !$database_username) {

            echo '<p style="color: #FF0000; font-size: 18px;">Database user_name is empty...!</p>';

            $err = true;
        }

        if($err) {

            return false;
        }

        $conn = new PDO("mysql:host=$database_host;dbname=$database_name", $database_username, $database_password);

        return $conn;
    }
    
    catch (PDOException $e) {

        echo '<p style="color: #FF0000; font-size: 18px;">Error when connecting to database: ' . $e->getMessage() . '</p>';

        return 500;
    }
    
?>
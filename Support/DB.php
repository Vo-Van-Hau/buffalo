<?php

class DB {

    /**
     * @param array $add_Block
     * @param string $table_Name
     * @return Object
     */
    public static function __addBlockRunner(array $add_Block, string $table_Name) {

        global $db;

        return $db->addBlockRunner($add_Block, $table_Name);
    }

    /**
     * @param string $compareKey
     * @param string $syntaxKey
     * @param string $compareValue
     * @return string SQL
     */
    public static function __whereData(string $compareKey, string $syntaxKey, string $compareValue) {

        global $db;

        return $db->whereData($compareKey, $syntaxKey, $compareValue);
    }


    /**
     * @param string $table_Name
     * @param array $proField
     * @param string|boolean $whereData
     * @param string|boolean $joinXS
     * @param boolean $mro
     * @return mixed Array || Object
     */
    public static function __selectData(string $table_Name, $proField = false, $whereData = false, $joinXS = false, $mro = true) {

        global $db;

        return $db->selectData($table_Name, $proField, $whereData, $joinXS, $mro);
    }
}

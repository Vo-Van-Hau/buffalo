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
     * @param array $cl 
     * @return string SQL
     */ 
    public static function __whereDataMultiCondition(array $cl) {

        global $db;

        return $db->whereDataMultiCondition($cl);
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

    /**
     * @param array $update_Block
     * @param string $table_Name
     * @param string $whereData
     * @return void()
     */
    public static function __updateData(array $update_Block, string $table_Name, string $whereData) {

        global $db;

        return $db->updateData($update_Block, $table_Name, $whereDatao);
    }

    /**
     * @param string $table_Name
     * @param string $whereData
     * @return boolean
     */
    public static function __deleteData(string $table_Name, string $whereData) {

        global $db;

        return $db->deleteData($table_Name, $whereData);
    }

    /**
     * @param string $a
     * @param string $b
     * @param string $c
     * @param string $d
     * @param string $e
     * @param string $f
     * @return string SQL
     */
    public static function __innerJoinZ($a, $b, $c, $d, $e, $f = fale) {

        global $db;

        return $db->innerJoinZ($a, $b, $c, $d, $e, $f);
    }
}

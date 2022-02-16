<?php 

    class Database {

        /**
         * string
         */
        private static $conn;

        /**
         * string
         */
        private $table;

        /**
         * array
         */
        protected $where;

        function __construct() {

            /**
             *  Kernel/Database/PDO/Connection
             */
            self::$conn = Connection::connection();

            $this->table = null;

            $this->where = array();
        }

        /**
         * @param $syntaxKey
         */
        public function whereData($compareKey, $syntaxKey, $compareValue) {

            if(isset($compareKey) && isset($syntaxKey) && isset($compareValue)) {

                if(is_string($compareValue)) {
    
                    $compareValue = "'{$compareValue}'";
                }

                return "WHERE {$compareKey} $syntaxKey $compareValue";
            }

            else {

                return "";
            }
        }

        /**
         *  @param array $compareKeycl: is a sub-array of array -> condition for query data
         */
        public function whereDataMultiCondition($cl) {

            try {

                $temp_sql = "WHERE ";

                if(is_array($cl)) {
    
                    for ($c = 0; $c < count($cl); $c++) { 
    
                        $tq = "";

                        if(is_string($cl[$c][2])) {
    
                            $cl[$c][2] = "'{$cl[$c][2]}'";
                        }

                        /**
                         * --------------##cl[$c] is sub-array
                         * <$cl[$c][3]> is comparing-operator 
                         */
                        if(isset($cl[$c][3])) {
    
                            $tq .= "{$cl[$c][0]} {$cl[$c][1]} {$cl[$c][2]} {$cl[$c][3]} "; 
                        } 
                        else {
    
                            $tq .= "{$cl[$c][0]} {$cl[$c][1]} {$cl[$c][2]}"; 
                        }         
    
                        $temp_sql .= $tq;
                    }
    
                    return $temp_sql;
                }
                else {
    
                    return false;
                }
            }
            catch (Exception $err) {

                return false;
            }
        }

        public function updateData($update_Block, $table_Name, $whereData) {

            try {

                $sql = "UPDATE {$table_Name} SET ";
    
                $numItems = count($update_Block);
                $i = 0;
        
                 foreach($update_Block as $BlockKey => $BlockValue) {

                    if(!is_null($BlockValue)){
                        
                        if(is_string($BlockValue)) {
    
                            $BlockValue = "'$BlockValue'";
                           
                        }
       
                        if($numItems == ++$i) {
       
                            $BlockItem = "$BlockKey = $BlockValue ";
       
                        }else {
       
                            $BlockItem = "$BlockKey = $BlockValue, ";
       
                        }
       
                        $sql .= $BlockItem;
                    }
                 }
    
                $sql .= $whereData;
    
                $stmt = self::$conn->prepare($sql);
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
                return $stmt->execute();
            }

            catch(PDOException $err) {

                $es = $err->getMessage();

                return false;
            } 
        }

        public function deleteData($table_Name, $whereData) {

            try {

                if(isset($table_Name) && isset($whereData)) {


                    $sql = "DELETE FROM {$table_Name} " . $whereData;
    
                    $stmt = self::$conn->prepare($sql);
    
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
                    $stmt->execute(); 

                    return true;
                }
            }
            catch(PDOException $err) {

                $es = $err->getMessage();

                return false;
            }
        }

        /**
         * Task: Insert & return last unique ID of Record
         */
        public function addBlockRunner($add_Block, $table_Name) {

            try {

                $keyBlockGobal = "";
                $valueBlockGobal = "";
    
                $numItems = count($add_Block);
                $i = 0;
        
                foreach($add_Block as $BlockKey => $BlockValue) {
    
                    if(is_string($BlockValue)) {
                        $BlockValue = "'$BlockValue'";
                    }
    
                    if($numItems == ++$i) {
    
                        $keyBlockGobal .= $BlockKey;
                        $valueBlockGobal .= $BlockValue;
    
                    }else {
    
                        $keyBlockGobal .= $BlockKey . ", ";
                        $valueBlockGobal .= $BlockValue . ", ";
    
                    }
    
                }
    
                $sql = "INSERT INTO {$table_Name}({$keyBlockGobal}) VALUES ({$valueBlockGobal})";

                self::$conn->exec($sql);
                
                return self::$conn->lastInsertId();
            }

            catch (Exception $e) {

                return false;
            }
        }

        /**
         * $proField: is a array of field names in table of database
         * $joinXS: is a array of join statement
         * $mro: is boolean for geting One (FALSE) or get Many (TRUE) record
         */
        public function selectData($table_Name, $proField = false, $whereData = false, $joinXS = false, $mro = true) {

            try {

                $sql = "SELECT ";
    
                if($proField && is_array($proField) && count($proField) != 0) {
    
                    $m = "";
    
                    $q = 0;
                    foreach($proField as $proFKey => $proFieldValue) {
    
                        $m .= "$proFieldValue, ";
    
                    }
    
                    // PHP substr_replace() function to remove the last character from a string in PHP.
                    $m = substr_replace($m, "", -2);
    
                    $sql .= $m;
    
                }  
                else {
    
                    $sql .= "*";
                }
    
                $sql .= " FROM " . $table_Name;
    
                if($joinXS && is_array($joinXS) == 1) {
    
                    foreach($joinXS as $jXSI) {
                        $sql .= "{$jXSI}";
                    }
                }
    
                if($whereData) {

                    $sql .= " {$whereData}";
                }

                return $sql;
    
                $stmt =  self::$conn->prepare($sql);
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
                $stmt->execute();
    
                if($mro) {
                    
                    return $stmt->fetchAll();
                } 
    
                return $stmt->fetch();
            }
            catch (PDOException $err) {

                $es = $err->getMessage();

                return false;
            }
        }

        public function innerJoinZ($a, $b, $c, $d, $e, $f = false) {

            // $a: table A
            // $b: key_a
            // $c: key_c
            // $d: table B
            // $e: key_b

            try {

                $ft = "";

                if($f) {
                    
                    switch($f) {
                        case "rightJoin":
                            $ft = "RIGHT JOIN";
                            break;
                        case "leftJoin":
                            $ft = "LEFT JOIN";
                            break;
                        case "innerJoin":
                            $ft = "INNER JOIN";
                            break;
                        case "fullOuterJoin":
                            $ft = "FULL OUTER JOIN";
                            break;
                        default: $ft = "INNER JOIN";
                    }
                } 

                else {

                    $ft = "INNER JOIN";
                }
               
                $x = " {$ft} {$d} ON {$a}.{$b} {$c} {$d}.{$e}";
    
                return $x;
            }
            catch (Exception $err) {

                return false;
            }
        }

        //--------------------------------------New version--------------------------------------************************

        /**
         * 
         * @param string $table
         * @return $this
         */
        public function table(string $table = null) {

            if(is_null($table) || !is_string($table)) return false;

            $this->table = $table;

            $this->where = array();
            
            return $this;
        }

        /**
         * 
         */
        public function get() {

            if($this->table) {

                return $this->selectData($this->table, false, false, false, true);
            }

            return false;
        }

        /**
         * 
         * @return $this
         */
        public function where($col = null, $value = null) {

            if(is_null($col) || is_null($value)) return false;

            $subWhere = [$col, "=", $value];

            if(count($this->where) > 0) {

                foreach($this->where as $where) {

                    $where[3] = "AND";

                    var_dump($where);
                }   
            }

            array_push($this->where, $subWhere);

            return $this;
        }

        public function first() {

            // return $this->where;
            // return $this->selectData($this->table, false, $this->whereDataMultiCondition($this->where), false, true);
        }
    }
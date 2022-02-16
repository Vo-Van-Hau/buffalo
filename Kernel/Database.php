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

        /**
         * array
         */
        protected $orderBy;

        function __construct() {

            /**
             *  Kernel/Database/PDO/Connection
             */
            self::$conn = Connection::connection();

            $this->table = null;

            $this->where = array();

            $this->orderBy = array();
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
         * @param array $compareKeycl: is a sub-array of array -> condition for query data
         * @return string
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

        /**
         * @param array $update_Block
         * @param string $table
         * @param string $whereData
         * @return int
         */
        public function updateData(array $update_Block, $table_Name, $whereData) {

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
    
                // Prepare statement
                $stmt = self::$conn->prepare($sql);
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
                // execute the query
                $stmt->execute();

                return $stmt->rowCount();
            }

            catch(PDOException $err) {

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
         * Task: retrieve the results of the query using the get method
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

            $subWhere = [$col, "=", $value, "AND"];

            array_push($this->where, $subWhere);

            /**
             * 
             */
            if(count($this->where) > 1) {

                unset($this->where[count($this->where) - 1][3]);
            }

            return $this;
        }

        /**
         * Add an "order by" clause to the query.
         *
         * @param  string  $direction
         * @return $this
         *
         */
        public function orderBy($column, $direction = 'asc') {

            array_push($this->orderBy, [
                $column, 
                $direction
            ]);

            return $this;
        }

        /**
         * Task: If you just need to retrieve a single row from a database table, you may use the DB facade's first method
         */
        public function first() {

            return $this->selectData($this->table, false, $this->whereDataMultiCondition($this->where), false, false);
        }

        /**
         * Task: If you don't need an entire row, you may extract a single value from a record using the value method. 
         * This method will return the value of the column directly
         * @param string $col
         * @return array|Object
         */
        public function value($col = null) {

            if(is_null($col)) return false;
            
            return $this->selectData($this->table, [$col], $this->whereDataMultiCondition($this->where), false, false);
        }

        /**
         * Task: In addition to inserting records into the database, the query builder can also update existing records using the update method. 
         * The update method, like the insert method, accepts an array of column and value pairs indicating the columns to be updated. 
         * The update method returns the number of affected rows. You may constrain the update query using where clauses
         * 
         * @param  array  $values
         * 
         * @return int
         */
        public function update(array $values) {

            if(!is_array($values)) return false;

            return $this->updateData($values, $this->table, $this->whereDataMultiCondition($this->where));
        }
        
        // ----------------------------------------------------------------Aggregates----------------------------------------------------------------

        /**
         * Retrieve the "count" result of the query.
         *
         * @param  string $columns
         * @return int
         */
        public function count($columns = '*') {

            $sql = "SELECT COUNT({$columns}) FROM {$this->table}";

            // Prepare statement
            $stmt = self::$conn->prepare($sql);
    
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            // execute the query
            $stmt->execute();

            $staffrow = $stmt->fetch(PDO::FETCH_NUM);
            
            return intval($staffrow[0]);
        }

        /**
         * Retrieve the maximum value of a given column.
         *
         * @param string $column
         * @return mixed
         */
        public function max($column = null) {

            if(is_null($column) || is_null($this->table)) return false;

            $sql = "SELECT MAX({$column}) FROM {$this->table}";

            // Prepare statement
            $stmt = self::$conn->prepare($sql);
    
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            // execute the query
            $stmt->execute();

            $maximum = $stmt->fetch(PDO::FETCH_NUM);

            return intval($maximum[0]);
        }

        /**
         * Retrieve the minimum value of a given column.
         *
         * @param string $column
         * @return mixed
         */
        public function min($column = null) {

            if(is_null($column) || is_null($this->table)) return false;

            $sql = "SELECT MIN({$column}) FROM {$this->table}";

            // Prepare statement
            $stmt = self::$conn->prepare($sql);
    
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            // execute the query
            $stmt->execute();

            $minimum = $stmt->fetch(PDO::FETCH_NUM);

            return intval($minimum[0]);
        }
    }
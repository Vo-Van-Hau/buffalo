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
         * The where constraints for the query.
         *
         * @var array
         */
        public $where = [];

        /**
         * The orderings for the query.
         *
         * @var array
         */
        protected $orderBy = [];

        /**
         * The columns that should be returned.
         *
         * @var array
         */
        public $columns = [];

        /**
         * The table joins for the query.
         *
         * @var array
         */
        public $joins = [];

        /**
         * All of the available clause operators.
         *
         * @var string[]
         */
        public $operators = [
            '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
            'like', 'like binary', 'not like', 'ilike',
            '&', '|', '^', '<<', '>>',
            'rlike', 'not rlike', 'regexp', 'not regexp',
            '~', '~*', '!~', '!~*', 'similar to',
            'not similar to', 'not ilike', '~~*', '!~~*',
        ];

        function __construct() {

            /**
             *  Kernel/Database/PDO/Connection
             */
            self::$conn = Connection::connection();

            $this->table = null;
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
        public function whereDataMultiCondition($cl = null) {

            try {

                if(is_array($cl) && !is_null($cl)) {

                    if(count($cl) <= 0) return "";

                    $temp_sql = "WHERE ";
    
                    for ($c = 0; $c < count($cl); $c++) { 
    
                        $tq = "";

                        if(is_string($cl[$c][2])) {
    
                            $cl[$c][2] = "'{$cl[$c][2]}'";
                        }

                        /**
                         * --------------##cl[$c] is sub-array
                         * <$cl[$c][3]> is comparing-operator 
                         */
                        if(isset($cl[$c][3]) && $c != (count($cl) - 1)) {

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

        /**
         * Delete records from the database.
         * @param string $table_Name
         * @param string $whereData
         * @return boolean
         */
        public function deleteData($table_Name = null, $whereData = null) {

            try {

                if(is_null($table_Name)) return false; 

                $sql = "DELETE FROM {$table_Name} ";

                $sql .= !is_null($whereData) ? $whereData : "";

                $stmt = self::$conn->prepare($sql);
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);

                $stmt->execute(); 

                return true;
            }
            catch(PDOException $err) {

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
                    }
                    else {
    
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
         * $mro: is boolean for geting One (FALSE) or get Many (TRUE) record
         */
        public function selectData($whereData = false, $mro = true) {

            try {

                if(!isset($this->table)) return "";
    
                $sql = "SELECT " . $this->parseSelect($this->columns) . " FROM " . $this->table;
  
                /**
                 *  -----------INNER JOIN 
                 */
                // New version
                if($this->joins && is_array($this->joins) && count($this->joins) > 0) {
    
                    foreach($this->joins as $join) {

                        $sql .= " $join ";
                    }

                    $sql = trim($sql);
                }
    
                /**
                 *  -----------WHERE
                 */
                if($whereData) {

                    $sql .= " {$whereData}";
                }

                /**
                 *  -----------ORDER BY
                 */
                if(count($this->orderBy) != 0) {

                    $orderByStmt = $this->parseOrderBy($this->orderBy);

                    $sql .= " " . $orderByStmt;
                }
  
                $stmt =  self::$conn->prepare($sql);
    
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
                $stmt->execute();
    
                if($mro) {

                    $result = $stmt->fetchAll();
                    
                    return $result;
                } 
    
                return $stmt->fetch();
            }
            catch (PDOException $err) {

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

        /*-------------------------------------------Parsing Data-----------------------------------------------------------*/

        /**
         * Task: convert array-OrderBy to string-OrderBy
         * @param array $values
         * @return string
         */
        public function parseOrderBy(array $values) {

            if(!isset($values) && !is_array($values)) return false;

            $sql = "";

            $c = 0;

            foreach ($values as  $value) {

                /**
                 * $value is a sub-array in array
                 */
                $d = ", ";

                $sql .= intval($c) == (count($values) - 1) ? $value[0] . " " . $value[1] : $value[0] . " " . $value[1] . $d;

                $c++;
            }

            return "ORDER BY " . $sql;
        }

        /**
         * Task: convert array-Columns to string-Select-Columns
         * @param array $columns
         * @return string
         */
        public function parseSelect(array $columns) {

            if(!isset($columns) && !is_array($columns)) return "";

            if(count($columns) == 0) return " * ";

            $sql = " ";

            $c = 0;

            foreach ($columns as  $column) {

                $d = ", ";

                $sql .= intval($c) == (count($columns) - 1) ? $column . " " : $column . $d;

                $c++;
            }

            return $sql;
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

            $this->joins = array();
            
            return $this;
        }

        /**
         * Task: retrieve the results of the query using the get method
         */
        public function get() {

            if($this->table) {
    
                return $this->selectData($this->whereDataMultiCondition($this->where), true);
            }

            return false;
        }

        /**
         * Set the columns to be selected.
         *
         * @param  array|mixed  $columns
         * @return $this
         */
        public function select($columns = ['*']) {

            $columns = is_array($columns) ? $columns : func_get_args();

            foreach ($columns as $as => $column) {

                $this->columns[] = $column;
            }

            return $this;
        }

        /**
         * The query builder also provides an insert method that may be used to insert records into the database table. 
         * The insert method accepts an array of column names and values
         *
         * @param  array  $values
         * @return bool
         */
        public function insert(array $values) {

            if (empty($values) || !is_array($values) || !isset($this->table)) return false;
            
            $status = $this->addBlockRunner($values, $this->table);

            if(isset($status)) {

                return true;
            }

            return false;
        }

        /**
         * Insert a new record and get the value of the primary key.
         * If the table has an auto-incrementing id, use the insertGetId method to insert a record and then retrieve the ID
         *
         * @param array $values
         * @param string|null $sequence
         * @return int
         */
        public function insertGetId(array $values, $sequence = null) {

            if (empty($values) || !is_array($values) || !isset($this->table)) return false;
            
            $status = $this->addBlockRunner($values, $this->table);

            if(isset($status)) {

                return intval($status);
            }

            return 0;
        }

        /**
         * Task: Add a basic where clause to the query.
         * 
         * @param string $col
         * @param string $value
         * @return $this
         */
        public function where($col = null, $value = null) {

            if(is_null($col) || is_null($value)) return false;

            $subWhere = [$col, "=", $value, "AND"];

            array_push($this->where, $subWhere);

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

            $direction = strtolower($direction);

            if(!in_array($direction, ['asc', 'desc'], true)) return false;

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

            return $this->selectData($this->whereDataMultiCondition($this->where), false);
        }

        /**
         * Task: If you don't need an entire row, you may extract a single value from a record using the value method. 
         * This method will return the value of the column directly
         * @param string $col
         * @return array|Object
         */
        public function value($col = null) {

            if(is_null($col)) return false;

            $this->select($col);
            
            return $this->selectData($this->whereDataMultiCondition($this->where), false);
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

        /**
         * The query builder's delete method may be used to delete records from the table. 
         * The delete method returns the number of affected rows. 
         * You may constrain delete statements by adding "where" clauses before calling the delete method
         *
         * @param  mixed  $id
         * @return int
         */
        public function delete($id = null) {

            $where = null;

            if(count($this->where) != 0) {

                $where = $this->whereDataMultiCondition($this->where);
            }

            $status = $this->deleteData($this->table, $where);

            return $status ? 1 : 0;
        }

        /**
         * Add a join clause to the query.
         *
         * @param  string  $table
         * @param  \Closure|string  $first
         * @param  string|null  $operator
         * @param  string|null  $second
         * @param  string  $type
         * @param  bool  $where
         * @return $this
         */
        public function join($table, $first, $operator = null, $second = null, $type = 'INNER JOIN', $where = false) {

            if(is_null($operator)) {

                $operator = "="; 
            }

            if(is_array($this->joins)) {

                $joinSQL = "{$type} {$table} ON {$first} {$operator} {$second}";

                array_push($this->joins, $joinSQL);
    
                return $this;
            }
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
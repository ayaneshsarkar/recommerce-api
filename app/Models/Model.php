<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Models;

    use App\Core\Application;

    /**
     * Class Model
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    abstract class Model {

        public \PDO $db;
        public string $query = '';
        public string $selectQuery = '*';
        public array $executeArray = [];

        public function __construct()
        {
            // Database Init
            $this->db = Application::$DB->pdo;
        }

        abstract function primaryKey();
        abstract function tableName();

        /**
         * function select
         *
         * @param string $selectQuery
         * @param string $tableName
         * @return Model
         */
        public function select(string $selectQuery = '', string $tableName = '')
        {
            if($selectQuery !== '') $this->selectQuery = $selectQuery;
            if(!$tableName) $tableName = $this->tableName();
            
            $this->query = "SELECT " . $this->selectQuery . " FROM $tableName";

            return $this;
        }

        /**
         * function join
         *
         * @param string $foreignTable
         * @param string $foreignKey
         * @param string $tableKey
         *
         * @return Model
         */
        public function join($foreignTable, $foreignKey, $tableKey = '', $tableName = '')
        {
            if(!$tableKey) $tableKey = $this->primaryKey();
            if(!$tableName) $tableName = $this->tableName();

            $this->query .= " JOIN $foreignTable ON $tableName.$tableKey = $foreignTable.$foreignKey";

            return $this;
        }

        /**
         * function where
         *
         * @param string $key
         * @param mixed $value
         * @param string $tableName
         *
         * @return Model
         */
        public function where(string $key, $value, $tableName = '')
        {
            if(!$tableName) $tableName = $this->tableName();

            $this->query .= " WHERE $tableName.$key = :$key";
            $this->executeArray[$key] = $value;
            return $this;
        }

        public function andWhere(string $key, $value, $tableName = '')
        {
            if(!$tableName) $tableName = $this->tableName();

            $this->query .= " AND $tableName.$key = :$key";
            $this->executeArray[$key] = $value;
            return $this;
        }

        /**
         * function orderBy
         *
         * @param string $column
         * @param boolean $sort
         * @param string $tableName
         *
         * @return Model
         */
        public function orderBy(string $column, $sort = false, $tableName = '')
        {
            if($tableName === '') $tableName = $this->tableName();
            if($sort === true) {
                $this->query .= " ORDER BY $tableName.$column DESC";
            } else {
                $this->query .= " ORDER BY $tableName.$column";
            }

            return $this;
        }

        /**
         * function getFirst
         *
         * @return object|bool
         */
        public function getFirst()
        {
            $this->query .= " LIMIT 1";
            $statement = $this->db->prepare($this->query);
            $statement->execute($this->executeArray);

            return $statement->fetch();
        }

        /**
         * function getAll
         *
         * @return object|bool
         */
        public function getAll()
        {
            if(!empty($this->executeArray)) {
                $statement = $this->db->prepare($this->query);
                $statement->execute($this->executeArr);
                return $statement->fetchAll();
            } else {
                return $this->db->query($this->query)->fetchAll();
            }
        }

        /**
         * function insert
         *
         * @param array $data
         * @param string $table
         * @return string
         */
        public function insert(array $data, string $table = ''): string
        {
            if($table === '') {
                $table = $this->tableName();
            }

            $queryKeys = array_map(fn($key) => "$key", array_keys($data));
            $queryKeys = implode(', ', $queryKeys);

            $queryValues = array_map(fn($key) => ":$key", array_keys($data));
            $queryValues = implode(', ', $queryValues);

            $query = "INSERT INTO " . "$table" . "($queryKeys) VALUES($queryValues)";

            $statement = $this->db->prepare($query);

            $executeArr = [];

            foreach($data as $key => $value) {
                $executeArr[$key] = $value;
            }

            $statement->execute($executeArr);
    
            return $this->db->lastInsertId();
        }
    }
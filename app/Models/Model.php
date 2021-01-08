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

        public function __construct()
        {
            // Database Init
            $this->db = Application::$DB->pdo;
        }

        abstract function primaryKey();
        abstract function tableName();

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
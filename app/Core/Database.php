<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Core;

    /**
     * Class Database
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */

    use PDO;

    class Database {

        public \PDO $pdo;
        public string $dbHost;
        public string $dbUser;
        public string $dbPass;
        public string $dbName;
        public string $pdoDSN;
        public string $dbType;
        public static $DB = NULL;
        
        public function __construct()
        {
            $this->dbHost = $_ENV['DB_HOST'];
            $this->dbUser = $_ENV['DB_USER'];
            $this->dbPass = $_ENV['DB_PASS'];
            $this->dbName = $_ENV['DB_NAME'];
            $this->dbType = $_ENV['DB_TYPE'];

            if(strtolower($this->dbType === 'postgres')) {
                $this->pdoDSN = "pgsql:host=$this->dbHost;port=5432;dbname=$this->dbName";
            } else if(strtolower($this->dbType) === 'mysql') {
                $this->pdoDSN = "mysql:host=$this->dbHost;port=5432;dbname=$this->dbName";
            }

            $this->pdo = new PDO($this->pdoDSN, $this->dbUser, $this->dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            self::$DB = $this;
        }

        public function first($table, $placeholder, $value)
        {
            $query = "SELECT * FROM $table WHERE $placeholder = :$placeholder";
            $statement = $this->pdo->prepare($query);
            $statement->execute([ $placeholder => $value ]);

            return $statement->fetch();
        }

        public function set($table, $placeholder, $value, array $data): bool
        {
            $setArr = array_map(fn($key) => "$key = :$key", array_keys($data));
            $setArr = implode('', $setArr);
            $query = "UPDATE $table SET $setArr WHERE $placeholder = :$placeholder";

            $executeArr = [];
            $executeArr[$placeholder] = $value;

            foreach($data as $key => $value) {
                $executeArr[$key] = $value;
            }

            return $this->pdo->prepare($query)->execute($executeArr);
        }

    }
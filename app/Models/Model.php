<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Models;

    use App\Core\Database;
    use PDO;

    /**
     * Class Model
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Model {

        public Database $Database;
        public \PDO $db;

        public function __construct()
        {
            // Database Init
            $this->Database = new Database();
            $this->db = $this->Database->pdo;
        }

    }
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
    class Model {

        public \PDO $db;

        public function __construct()
        {
            // Database Init
            $this->db = Application::$DB->pdo;
        }

    }
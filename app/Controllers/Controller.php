<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Controllers;

    use App\Core\Database;
    use PDO;
    use App\Models\Book;

    /**
     * Class Controller
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Controller {

        public Database $Database;
        public \PDO $db;

        // Model Declares
        public Book $book;

        public function __construct()
        {
            // Database Init
            $this->Database = new Database();
            $this->db = $this->Database->pdo;

            // Models
            $this->book = new Book();
        }

    }
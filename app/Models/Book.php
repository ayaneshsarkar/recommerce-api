<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Models;

    use App\Core\Database;

/**
     * Class Book
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Book extends Model {

        public function get()
        {
            $query = "SELECT books.*, categories.name AS category FROM books
                    JOIN categories ON books.category_id = categories.id 
                    ORDER BY books.created_at DESC";
            $statement = $this->db->query($query);
            $books = $statement->fetchAll();

            return $books;
        }

    }
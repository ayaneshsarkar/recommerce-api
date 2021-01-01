<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Models;
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
            $books = $this->db->query($query)->fetchAll();
            return $books;
        }

        public function first(int $id)
        {
            if($id) {
                $query = "SELECT books.*, categories.name AS category FROM books
                    JOIN categories ON books.category_id = categories.id
                    WHERE books.id = :id
                    ORDER BY books.created_at DESC";
                $statement = $this->db->prepare($query);
                $statement->execute([ 'id' => $id ]);
                $book = $statement->fetch();

                return $book;
            } else {
                return NULL;
            }
            
        }

    }
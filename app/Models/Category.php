<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Models;

    use App\Models\Model;

    /**
     * Class Category
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Category extends Model {

        public function get()
        {
            $query = "SELECT * FROM categories ORDER BY created_at DESC";
            $statement = $this->db->query($query);
            $categories = $statement->fetchAll();

            return $categories;
        }

        public function first(?int $id)
        {
            if($id) {
                $query = "SELECT * FROM categories WHERE id = :id ORDER BY created_at DESC";
                $statement = $this->db->prepare($query);
                $statement->execute(['id' => $id]);
                $category = $statement->fetch();

                return $category;
            } else {
                return NULL;
            }
        }

    }
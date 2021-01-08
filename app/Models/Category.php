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

        public function primaryKey()
        {
            return 'id';
        }

        public function tableName()
        {
            return 'categories';
        }

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

        public function create(object $data)
        {
            $query = "INSERT INTO categories(name) VALUES(:name)";
            $statement = $this->db->prepare($query)->execute([ 'name' => $data->name ]);
        }

        public function update(object $data)
        {
            $query = "UPDATE categories SET name = :name WHERE id = :id";
            $this->db->prepare($query)->execute([
                'id' => $data->id,
                'name' => $data->name 
            ]);
        }

        public function delete(?int $id)
        {
            if($id) {
                $query = "DELETE FROM categories WHERE id = :id";
                $this->db->prepare($query)->execute([ 'id' => $id ]);
            }
        }

    }
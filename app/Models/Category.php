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
            return $this->select()->orderBy('created_at', true)->getAll();
        }

        public function first(?int $id)
        {
            if($id) {
                return $this->select()->where('id', $id)->orderBy('created_at', true)
                        ->getFirst();
            } else {
                return NULL;
            }
        }

        public function create(object $data)
        {
            return $this->insert([ 'name' => $data->name ]);
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
                $this->deleteOneOrMany('id', $id);
            }
        }

    }
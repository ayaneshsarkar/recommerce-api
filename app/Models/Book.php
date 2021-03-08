<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Models;

    use App\Helpers\Helper;
    /**
     * Class Book
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Book extends Model {

        public function primaryKey()
        {
            return 'id';
        }

        public function tableName()
        {
            return 'books';
        }

        public function get()
        {
            return $this->select('books.*, categories.name AS category, book_types.type')
                    ->join('categories', 'id', 'category_id')
                    ->join('book_types', 'id', 'type_id')
                    ->orderBy('created_at', true)
                    ->getAll();
        }

        public function first(?int $id, ?string $slug = null)
        {
            if($id) {
                return $this->select('books.*, categories.name AS category, book_types.type')
                    ->join('categories', 'id', 'category_id')
                    ->join('book_types', 'id', 'type_id')
                    ->where('id', $id)
                    ->orderBy('created_at', true)
                    ->getFirst();
            } elseif($slug) {
                return $this->select('books.*, categories.name AS category, book_types.type')
                    ->join('categories', 'id', 'category_id')
                    ->join('book_types', 'id', 'type_id')
                    ->where('book_code', $slug)
                    ->orderBy('created_at', true)
                    ->getFirst();
            } else {
                return NULL;
            }
            
        }

        public function create(object $data)
        {
            $bookArr = [
                'category_id' => $data->category_id,
                'title' => $data->title,
                'description'  => $data->description,
                'author' => $data->author,
                'bookurl' => $data->bookurl,
                'type_id' => $data->type_id,
                'price' => $data->price,
                'publish_date' => date('Y-m-d H:i:s', strtotime($data->publish_date)),
                'book_code' => Helper::randomString(100)
            ];

            return $this->insert($bookArr);
        }

        public function update(object $data)
        {
            $updateArr = [
                'category_id' => $data->category_id,
                'title' => $data->title,
                'description'  => $data->description,
                'author' => $data->author,
                'bookurl' => $data->bookurl,
                'type_id' => $data->type_id,
                'price' => $data->price,
                'publish_date' => date('Y-m-d H:i:s', strtotime($data->publish_date))
            ];

            return $this->updateOne($updateArr, $data->id);
        }

        public function delete(?int $id)
        {
            if($id) {
                $query = "DELETE FROM books WHERE id = :id";
                $statement = $this->db->prepare($query);
                $statement->execute([ 'id' => $id ]);
            }
        }

    }
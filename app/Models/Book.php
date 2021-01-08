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
            return $this->select('books.*, categories.name AS category')
                    ->join('categories', 'id', 'category_id')
                    ->orderBy('created_at', true)
                    ->getAll();
        }

        public function first(?int $id)
        {
            if($id) {
                return $this->select('books.*, categories.name AS category')
                    ->join('categories', 'id', 'category_id')
                    ->where('id', $id)
                    ->orderBy('created_at', true)
                    ->getFirst();
            } else {
                return NULL;
            }
            
        }

        public function create(object $data)
        {
            $query = "INSERT INTO 
                    books(category_id, title, description, author, bookurl, 
                    hardcover_price, paperback_price, online_price, publish_date)
                    
                    VALUES(:category_id, :title, :description, :author, :bookurl, 
                    :hardcover_price, :paperback_price, :online_price, :publish_date)";
            $statement = $this->db->prepare($query);

            $executeArr = [
                'category_id' => $data->category_id,
                'title' => $data->title,
                'description'  => $data->description,
                'author' => $data->author,
                'bookurl' => $data->bookurl,
                'hardcover_price' => 
                empty($data->hardcover_price) ? NULL : $data->hardcover_price,

                'paperback_price' => 
                empty($data->paperback_price) ? NULL : $data->paperback_price,

                'online_price' => 
                empty($data->online_price) ? NULL : $data->online_price,

                'publish_date' => date('Y-m-d H:i:s', strtotime($data->publish_date))
            ];

            $statement->execute($executeArr);
        }

        public function update(object $data)
        {
            $query = "UPDATE books 
                SET category_id = :category_id, title = :title, description = :description, 
                    author = :author, bookurl = :bookurl, hardcover_price = :hardcover_price, paperback_price = :paperback_price, online_price = :online_price, 
                    publish_date = :publish_date
                WHERE id = :id";
            
            $statement = $this->db->prepare($query);

            $executeArr = [
                'id' => $data->id,
                'category_id' => $data->category_id,
                'title' => $data->title,
                'description'  => $data->description,
                'author' => $data->author,
                'bookurl' => $data->bookurl,
                'hardcover_price' => 
                empty($data->hardcover_price) ? NULL : $data->hardcover_price,

                'paperback_price' => 
                empty($data->paperback_price) ? NULL : $data->paperback_price,

                'online_price' => 
                empty($data->online_price) ? NULL : $data->online_price,

                'publish_date' => date('Y-m-d H:i:s', strtotime($data->publish_date))
            ];

            $statement->execute($executeArr);
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
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
            return $this->select('books.*, categories.name AS category, 
                        book_prices.hardcover_price, book_prices.paperback_price, 
                        book_prices.online_price')
                    ->join('categories', 'id', 'category_id')
                    ->join('book_prices', 'book_id', 'id')
                    ->orderBy('created_at', true)
                    ->getAll();
        }

        public function first(?int $id)
        {
            if($id) {
                return $this->select('books.*, categories.name AS category, book_prices.hardcover_price, book_prices.paperback_price, 
                book_prices.online_price')
                    ->join('categories', 'id', 'category_id')
                    ->join('book_prices', 'book_id', 'id')
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
                    books(category_id, title, description, author, bookurl, publish_date)
                    VALUES(:category_id, :title, :description, :author, :bookurl, 
                    :publish_date)";
            $statement = $this->db->prepare($query);

            $executeArr = [
                'category_id' => $data->category_id,
                'title' => $data->title,
                'description'  => $data->description,
                'author' => $data->author,
                'bookurl' => $data->bookurl,
                'publish_date' => date('Y-m-d H:i:s', strtotime($data->publish_date))
            ];

            $statement->execute($executeArr);

            $pricesArr = [
                'book_id' => $this->db->lastInsertId(),

                'hardcover_price' => 
                empty($data->hardcover_price) ? NULL : $data->hardcover_price,

                'paperback_price' => 
                empty($data->paperback_price) ? NULL : $data->paperback_price,

                'online_price' => 
                empty($data->online_price) ? NULL : $data->online_price,
            ];

            $this->insert($pricesArr, 'book_prices');

        }

        public function update(object $data)
        {
            $updateArr = [
                'category_id' => $data->category_id,
                'title' => $data->title,
                'description'  => $data->description,
                'author' => $data->author,
                'bookurl' => $data->bookurl,
                'publish_date' => date('Y-m-d H:i:s', strtotime($data->publish_date))
            ];

            $pricesArr = [
                'hardcover_price' => 
                empty($data->hardcover_price) ? NULL : $data->hardcover_price,

                'paperback_price' => 
                empty($data->paperback_price) ? NULL : $data->paperback_price,

                'online_price' => 
                empty($data->online_price) ? NULL : $data->online_price,
            ];

            $this->updateOne($updateArr, $data->id);
            $this->updateOne($pricesArr, $data->id, 'book_prices', 'book_id');

            return TRUE;
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
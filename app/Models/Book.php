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

        protected function insertBookPrice(int $bookId, object $data): string
        {
            $pricesArr = [
                'book_id' => $bookId,

                'hardcover_price' => 
                empty($data->hardcover_price) ? NULL : $data->hardcover_price,

                'paperback_price' => 
                empty($data->paperback_price) ? NULL : $data->paperback_price,

                'online_price' => 
                empty($data->online_price) ? NULL : $data->online_price,
            ];

            return $this->insert($pricesArr, 'book_prices');
        }

        protected function updateBookPrice(object $data): bool
        {
            $pricesArr = [
                'hardcover_price' => 
                empty($data->hardcover_price) ? NULL : $data->hardcover_price,

                'paperback_price' => 
                empty($data->paperback_price) ? NULL : $data->paperback_price,

                'online_price' => 
                empty($data->online_price) ? NULL : $data->online_price
            ];

            return $this->updateOne($pricesArr, $data->id, 'book_prices', 'book_id');
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
            $bookArr = [
                'category_id' => $data->category_id,
                'title' => $data->title,
                'description'  => $data->description,
                'author' => $data->author,
                'bookurl' => $data->bookurl,
                'publish_date' => date('Y-m-d H:i:s', strtotime($data->publish_date))
            ];

            $bookId = $this->insert($bookArr);
            return $this->insertBookPrice((int)$bookId, $data);
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

            $this->updateOne($updateArr, $data->id);
            return $this->updateBookPrice($data);
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
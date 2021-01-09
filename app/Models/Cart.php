<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 08/01/2021
     */

    namespace App\Models;

    use App\Models\Model;
    use App\Core\Application;
    use App\Helpers\Helper;

    /**
     * Class Cart
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Models
     */
    class Cart extends Model {

        public function primaryKey()
        {
            return 'id';
        }

        public function tableName()
        {
            return 'carts';
        }

        public function checkCartByUser(?int $id)
        {
            return $this->select()->where('user_id', $id)->getFirst();
        }

        public function checkCartByBook(?int $bookId)
        {
            return $this->select('*', 'cart_items')
                        ->join('carts', 'id', 'cart_id', 'cart_items')
                        ->where('book_id', $bookId, 'cart_items')
                        ->andWhere('user_id', Application::$APP->user->id)
                        ->getFirst();
        }

        public function store()
        {
            return $this->insert([
                'user_id' => Application::$APP->user->id,
                'session_token' => Helper::randomString(12),
                'first_name' => Application::$APP->user->first_name,
                'last_name' => Application::$APP->user->last_name,
                'email' => Application::$APP->user->email,
                'address' => Application::$APP->user->address,
                'city' => Application::$APP->user->city,
                'state' => Application::$APP->user->state,
                'country' => Application::$APP->user->country,
                'date_of_birth' => Application::$APP->user->date_of_birth
            ]);
        }

        public function storeItems($id, object $data, object $book)
        {
            $insertArr = [
                'cart_id' => (int)$id,
                'book_id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'author' => $book->author,
                'bookurl' => $book->bookurl,
                'hardcover_price' => 
                $data->hardcover_price ? $book->hardcover_price * ($data->quantity ?? 1) : 0,
                'paperback_price' => 
                $data->paperback_price ? $book->paperback_price * ($data->quantity ?? 1) : 0,
                'online_price' => 
                $data->online_price ? $book->online_price * ($data->quantity ?? 1) : 0,
                'discount' => $data->discount ?? 0,
                'publish_date' => $book->publish_date,
                'category' => $book->category,
                'quantity' => $data->quantity ?? 1
            ];

            return $this->insert($insertArr, 'cart_items');
        }

        public function updateItems(object $cartData, object $data, object $book): bool
        {
            $quantity = $cartData->quantity + ($data->quantity ?? 1);
            echo $cartData->id; exit;

            $updateData = [
                'title' => $book->title,
                'description' => $book->description,
                'author' => $book->author,
                'bookurl' => $book->bookurl,
                'hardcover_price' => 
                $data->hardcover_price ? $book->hardcover_price * $quantity : 0,
                'paperback_price' => 
                $data->paperback_price ? $book->paperback_price * $quantity : 0,
                'online_price' => 
                $data->online_price ? $book->online_price * $quantity : 0,
                'discount' => $data->discount ?? 0,
                'publish_date' => $book->publish_date,
                'category' => $book->category,
                'quantity' => $quantity
            ];

            return $this->updateOne($updateData, $cartData->id, 'cart_items');
        }

    }
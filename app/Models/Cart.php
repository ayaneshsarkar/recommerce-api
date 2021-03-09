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

        public function cartTotal()
        {
            $total = 0;

            $items = $this->select('*', 'cart_items')
                    ->join('carts', 'id', 'cart_id', 'cart_items')
                    ->where('user_id', Application::$APP->user->id)
                    ->getAll();

            if(!empty($items)) {
                foreach($items as $item) {
                    $total += ($item->price * $item->quantity);
                }
            }

            return $total;
        }

        public function allCarts(int $id)
        {
            $selectArray = [
                'carts.*',
                'cart_items.id as cartitemid',
                'cart_items.book_id as cartbookid',
                'cart_items.title',
                'cart_items.description',
                'cart_items.author',
                'cart_items.bookurl',
                'cart_items.book_code',
                'cart_items.discount',
                'cart_items.publish_date',
                'cart_items.category',
                'cart_items.quantity',
                'cart_items.discount',
                'cart_items.price',
                'cart_items.type',
                'cart_items.created_at as cartItemCreated',
                'cart_items.updated_at as cartItemUpdated'
            ];

            return $this->select(implode(', ', $selectArray), 'carts')
                    ->join('cart_items', 'cart_id', 'id')
                    ->where('user_id', $id)
                    ->getAll();
        }

        public function singleCart(int $id, int $cartItemId)
        {
            $selectArray = [
                'carts.*',
                'cart_items.id as cartitemid',
                'cart_items.book_id as cartbookid',
                'cart_items.title',
                'cart_items.description',
                'cart_items.author',
                'cart_items.bookurl',
                'cart_items.book_code',
                'cart_items.discount',
                'cart_items.publish_date',
                'cart_items.category',
                'cart_items.quantity',
                'cart_items.discount',
                'cart_items.price',
                'cart_items.type',
                'cart_items.created_at as cartItemCreated',
                'cart_items.updated_at as cartItemUpdated'
            ];

            return $this->select(implode(', ', $selectArray), 'carts')
                    ->join('cart_items', 'cart_id', 'id')
                    ->where('user_id', $id)
                    ->andWhere('id', $cartItemId, 'cart_items')
                    ->getFirst();
        }

        public function checkCartByCartId(int $id)
        {
            return $this->select('*', 'cart_items')
                    ->where('cart_id', $id, 'cart_items')
                    ->orderBy('created_at', true, 'cart_items')
                    ->getFirst();
        }

        public function checkCartByUser(?int $id)
        {
            return $this->select()->where('user_id', $id)->orderBy('created_at')->getFirst();
        }

        public function checkCartByBook(?int $bookId)
        {
            return $this->select('cart_items.*', 'cart_items')
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
                'address' => Application::$APP->user->address ?? NULL,
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
                'description' => $book->description ?? NULL,
                'author' => $book->author,
                'bookurl' => $book->bookurl,
                'discount' => $data->discount ?? 0,
                'publish_date' => $book->publish_date,
                'category' => $book->category,
                'quantity' => $data->quantity ?? 1,
                'price' => (int)$book->price,
                'type' => $book->type,
                'book_code' => $book->book_code
            ];

            return $this->insert($insertArr, 'cart_items');
        }

        public function updateItems(object $cartData, object $data, object $book): bool
        {
            $quantity = $cartData->quantity + ($data->quantity ?? 1);
            
            $updateData = [
                'title' => $book->title,
                'description' => $book->description ?? NULL,
                'author' => $book->author,
                'bookurl' => $book->bookurl,
                'discount' => $data->discount ?? 0,
                'publish_date' => $book->publish_date,
                'category' => $book->category,
                'quantity' => $quantity,
                'price' => $book->price,
                'type' => $book->type,
                'book_code' => $book->book_code
            ];

            return $this->updateOne($updateData, $cartData->id, 'cart_items');
        }

        public function deleteItem(int $id)
        {
            return $this->deleteOneOrMany('id', $id, 'cart_items');
        }

        public function deleteItems(int $id)
        {
            return $this->deleteOneOrMany('cart_id', $id, 'cart_items');
        }

        public function deleteCart(int $id)
        {
            return $this->deleteOneOrMany('id', $id);
        }

    }
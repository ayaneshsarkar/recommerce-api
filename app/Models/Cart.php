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

        protected function insertItemPrices(int $itemId, object $data, object $book): string
        {
            $priceArr = [
                'cart_item_id' => $itemId,
                'hardcover_price' => 
                $data->hardcover_price ? $book->hardcover_price * ($data->quantity ?? 1) : NULL,
                'paperback_price' => 
                $data->paperback_price ? $book->paperback_price * ($data->quantity ?? 1) : NULL,
                'online_price' => 
                $data->online_price ? $book->online_price * ($data->quantity ?? 1) : NULL
            ];

            return $this->insert($priceArr, 'cart_prices');
        }

        protected function updateItemPrices(object $itemData, object $data, object $book): bool
        {
            $quantity = $itemData->quantity + ($data->quantity ?? 1);
            
            $priceArr = [
                'cart_item_id' => $itemData->id,
                'hardcover_price' => 
                $data->hardcover_price ? $book->hardcover_price * $quantity : 0,
                'paperback_price' => 
                $data->paperback_price ? $book->paperback_price * $quantity : 0,
                'online_price' => 
                $data->online_price ? $book->online_price * $quantity : 0
            ];

            return $this->updateOne($priceArr, $itemData->id, 'cart_prices', 'cart_item_id');
        }

        public function allCarts(int $id)
        {
            $selectArray = [
                'carts.*',
                'cart_items.id as cartItemId',
                'cart_items.title',
                'cart_items.description',
                'cart_items.author',
                'cart_items.bookurl',
                'cart_items.discount',
                'cart_items.publish_date',
                'cart_items.category',
                'cart_items.quantity',
                'cart_items.discount',
                'cart_prices.hardcover_price',
                'cart_prices.paperback_price',
                'cart_prices.online_price',
                'cart_items.created_at as cartItemCreated',
                'cart_items.updated_at as cartItemUpdated'
            ];

            return $this->select(implode(', ', $selectArray), 'carts')
                    ->join('cart_items', 'cart_id', 'id')
                    ->join('cart_prices', 'cart_item_id', 'id', 'cart_items')
                    ->where('user_id', $id)
                    ->getAll();
        }

        public function checkCartByCartId(int $id)
        {
            return $this->select('*', 'cart_items')->where('cart_id', $id, 'cart_items')
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
                'discount' => $data->discount ?? 0,
                'publish_date' => $book->publish_date,
                'category' => $book->category,
                'quantity' => $data->quantity ?? 1
            ];

            $cartId = $this->insert($insertArr, 'cart_items');
            return $this->insertItemPrices((int)$cartId, $data, $book);
        }

        public function updateItems(object $cartData, object $data, object $book): bool
        {
            $quantity = $cartData->quantity + ($data->quantity ?? 1);
            
            $updateData = [
                'title' => $book->title,
                'description' => $book->description,
                'author' => $book->author,
                'bookurl' => $book->bookurl,
                'discount' => $data->discount ?? 0,
                'publish_date' => $book->publish_date,
                'category' => $book->category,
                'quantity' => $quantity
            ];

            $this->updateOne($updateData, $cartData->id, 'cart_items');

            return $this->updateItemPrices($cartData, $data, $book);
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
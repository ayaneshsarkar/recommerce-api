<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 10/03/2021
     */

    namespace App\Models;

    use App\Models\Model;
    use App\Core\Application;
    use App\Helpers\Helper;

    /**
     * Class Order
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Models
     */

    class Order extends Model {

        public function primaryKey()
        {
            return 'id';
        }

        public function tableName()
        {
            return 'orders';
        }

        public function createOrder(object $user, object $data, int $amount)
        {
            return $this->insert([
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'avatar' => $user->avatar ?? NULL,
                'address' => $user->address ?? NULL,
                'city' => $user->city,
                'state' => $user->country,
                'country' => $user->country,
                'date_of_birth' => $user->date_of_birth,
                'transaction_id' => $data->transactionId,
                'status' => $data->status,
                'invoice_id' => Helper::randomString(12),
                'paid_amount' => $amount
            ]);
        }

        public function createOrderItems(int $orderId, object $items)
        {
            foreach($items as $item) {
                $this->insert([
                    'order_id' => $orderId,
                    'title' => $item->title,
                    'description' => $item->description ?? NULL,
                    'author' => $item->author,
                    'bookurl' => $item->bookurl ?? NULL,
                    'publish_date' => $item->publish_date,
                    'category' => $item->category,
                    'quantity' => $item->quantity,
                    'type' => $item->type,
                    'price' => $item->price,
                    'book_code' => $item->book_code
                ], 'order_items');
            }
        }

    }
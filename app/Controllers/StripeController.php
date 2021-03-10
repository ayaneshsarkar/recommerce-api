<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 09/01/2121
     */
    namespace App\Controllers;

    use App\Controllers;
    use App\Core\Application;
    use App\Core\Request;
    use App\Core\Response;
    use App\Middlewares\AuthMiddleware;
    use App\Core\Validator;
    use App\Core\FileHandler;
    use Error;
    use Stripe\PaymentIntent;
    use Stripe\Stripe;

    /**
     * Class StripeController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Controllers
     */
    class StripeController extends Controller {

        public function setAllMiddlewares()
        {
            $this->registerMiddlewares(new AuthMiddleware([
                '/stripe', '/create-order', '/get-orders', '/get-order'
            ]));
        }

        public function payment(Request $request, Response $response)
        {
            Stripe::setApiKey($_ENV['STRIPE_API_KEY']);

            $amount = $this->cart->cartTotal() * 100;

            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'inr'
                ]);
    
                $output = [
                    'clientSecret' => $paymentIntent->client_secret,
                    'transactionId' => $paymentIntent->id
                ];

                return $response->json($output);
            } catch(Error $error) {
                return $response->json([ 'status' => FALSE, 'errors' => $error ]);
            }
        }

        public function storeOrder(Request $request, Response $response)
        {
            $data = $request->getBody();
            $amount = $this->cart->cartTotal();
            $userId = Application::$APP->user->id;

            Validator::isString($data->transactionId ?? NULL, 'transaction id', true);
            Validator::isString($data->status ?? NULL , 'status', true);

            $errors = Validator::validate();

            if(!empty($errors)) {
                return $response->json([ 'status' => TRUE, 'errors' => $errors ]);
            }

            $cartItems = $this->cart->allCarts(Application::$APP->user->id);
            $cartId = null;

            if(empty($cartItems)) {
                return $response->json([ 'status' => TRUE, 'errors' => 'Empty Cart!' ]);
            }

            FileHandler::makeDir('orders/' . $userId);

            // Store Order
            $orderId = $this->order->createOrder(Application::$APP->user, $data, $amount);

            FileHandler::makeDir('orders/'.$userId."/$orderId");
            FileHandler::makeDir('orders/'.$userId."/$orderId/avatars");
            FileHandler::makeDir('orders/'.$userId."/$orderId/bookimages");
            FileHandler::makeDir('orders/'.$userId."/$orderId/invoices");

            if(Application::$APP->user->avatar) {
                $imageName = Application::$APP->user->avatar;

                FileHandler::copyFile(
                    Application::$APP->user->avatar, 
                    'orders/' . $userId  . "/$orderId/$imageName"
                );
            }

            // Store Order Items
            foreach($cartItems as $item) {
                $cartId = $item->id;

                if($item->bookurl) {
                    $imageName = $item->bookurl;

                    FileHandler::copyFile(
                        $item->bookurl,
                        'orders/'.$userId."/$orderId/$imageName"
                    );
                }
            }

            $this->order->createOrderItems($orderId, $cartItems);
            
            // Clear Cart
            $this->cart->deleteCart($cartId);
            

            return $response->json([ 
                'status' => TRUE, 
                'errors' => NULL, 
                'orderId' => $orderId 
            ]);
        }

        public function getOrders(Request $request, Response $response)
        {
            $orders = $this->order->getOrders();

            return $response->json([ 'status' => TRUE, 'errors' => NULL, 'orders' => $orders ]);
        }

        public function getOrder(Request $request, Response $response)
        {
            $orderId = $request->getBody()->id;
            $order = $this->order->getOrder((int)$orderId);

            return $response->json([ 'status' => TRUE, 'errors' => NULL, 'order' => $order ]);
        }

    }
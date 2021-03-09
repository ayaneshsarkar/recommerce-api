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
            $this->registerMiddlewares(new AuthMiddleware(['/stripe']));
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

            Validator::isString($data->transactionId ?? NULL, 'transaction id', true);
            Validator::isString($data->status ?? NULL , 'status', true);

            $errors = Validator::validate();

            if(!empty($errors)) {
                return $response->json([ 'status' => TRUE, 'errors' => $errors ]);
            }

            $cartItems = $this->cart->allCarts(Application::$APP->user->id);

            if(empty($cartItems)) {
                return $response->json([ 'status' => TRUE, 'errors' => 'Empty Cart!' ]);
            }

            if(Application::$APP->user->avatar) {
                if(!is_dir(Application::$APP->user->id)) {
                    mkdir('orders/' . Application::$APP->user->id);

                    if(!is_dir('orders/' . Application::$APP->user->id . '/avatars')) {
                        mkdir('orders/' . Application::$APP->user->id . '/avatars');
                    }

                    if(!is_dir('orders/' . Application::$APP->user->id . '/bookimages')) {
                        mkdir('orders/' . Application::$APP->user->id . '/bookimages');
                    }

                    if(!is_dir('orders/' . Application::$APP->user->id . '/invoices')) {
                        mkdir('orders/' . Application::$APP->user->id . '/invoices');
                    }
                }

                $imageName = explode('/', Application::$APP->user->avatar);
                $imageName = $imageName[1];

                copy(
                    Application::$APP->user->avatar, 
                    'orders/' . Application::$APP->user->id  . "/avatars/$imageName"
                );
            }

            // Store Order
            $orderId = $this->order->createOrder(Application::$APP->user, $data, $amount);
            // Store Order Items
            foreach($cartItems as $item) {
                if($item->bookurl) {
                    $imageName = explode('/', Application::$APP->user->avatar);
                    $imageName = $imageName[1];
                    copy(
                        $item->bookurl,
                        'orders/' . Application::$APP->user->id . "/bookimages/$imageName"
                    );
                }
            }

            $this->order->createOrderItems($orderId, $cartItems);

            return $response->json([ 'status' => TRUE, 'errors' => NULL ]);
        }

    }
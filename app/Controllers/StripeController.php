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
    use Error;
    use Stripe\PaymentIntent;

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
            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => 100,
                    'currency' => 'inr'
                ]);
    
                $output = [
                    'clientSecret' => $paymentIntent->client_secret
                ];

                return $response->json($output);
            } catch(Error $error) {
                return $response->json([ 'status' => FALSE, 'errors' => $error ]);
            }
        }

    }
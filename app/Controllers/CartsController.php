<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 08/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\Request;
    use App\Core\Response;
    use App\Core\Validator;
    use App\Middlewares\AuthMiddleware;
    use App\Helpers\Helper;


    /**
     * Class CartsController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Controllers
     */
    class CartsController extends Controller {

        public function setAllMiddlewares()
        {
            $this->registerMiddlewares(new AuthMiddleware(['/cart']));
        }

        public function storeCart(Request $request, Response $response)
        {
            $data = $request->getBody();

            Validator::isInt($data->book_id ?? NULL, 'book', true);
            Validator::isOnlyInt($data->quantity ?? NULL, 'quantity');
            Validator::isDrop('hardcover_price|paperback_price|online_price', true, $data, 'prices');
            Validator::isOnlyInt($data->discount ?? NULL, 'discount');

            $errors = Validator::validate();

            if(empty($errors)) {
                $book = $this->book->first($data->book_id);
                $cartId = $this->cart->store();
                $this->cart->storeItems($cartId, $data, $book);

                return $response->json([ 'status' => TRUE, 'errors' => NULL ]); 
            } else {
                $response->json([ 'status' => FALSE, 'errors' => $errors ]);
            }
        }

        public function test()
        {
            header('Content-type: text/html');

            return Helper::randomString(10);
        }

    }
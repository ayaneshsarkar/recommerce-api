<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 08/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
use App\Core\Application;
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
            Validator::isDrop('hardcover_price|paperback_price|online_price', 1, $data, 'prices');
            Validator::isOnlyInt($data->discount ?? NULL, 'discount');

            $errors = Validator::validate();

            if(!empty($errors)) {
                return $response->json([ 'status' => FALSE, 'errors' => $errors ]);
            } else {
                $book = $this->book->first($data->book_id);

                if(empty($book)) {
                    return $response->json([ 'status' => FALSE, 'errors' => 'Invalid Book!' ]);
                }

                $checkCartBook = $this->cart->checkCartByBook($data->book_id);

                if(!empty($checkCartBook)) {
                    echo $response->json((array)$checkCartBook); exit;
                    
                    $this->cart->updateItems($checkCartBook, $data, $book);

                    return $response->json([
                        'status' => TRUE,
                        'errors' => NULL,
                        'message' => 'Update Successfull!'
                    ]); 
                }

                $cartId = $this->cart->store();
                $this->cart->storeItems($cartId, $data, $book);

                return $response->json([ 
                    'status' => TRUE,
                    'errors' => NULL,
                    'message' => 'Insert Successfull!'
                ]);
            }
        }

        public function test()
        {
            header('Content-type: text/html');

            return Helper::randomString(10);
        }

    }
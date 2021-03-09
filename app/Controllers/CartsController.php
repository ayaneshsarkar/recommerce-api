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


    /**
     * Class CartsController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Controllers
     */
    class CartsController extends Controller {

        public function setAllMiddlewares()
        {
            $this->registerMiddlewares(new AuthMiddleware([
                '/cart', '/delete-cart', '/clear-cart', '/carts', '/cart-total'
            ]));
        }

        public function getCarts(Request $request, Response $response)
        {
            return $response->json([
                'status' => TRUE,
                'errors' => NULL,
                'carts' => $this->cart->allCarts(Application::$APP->user->id)
            ]);
        }

        public function getCart(Request $request, Response $response)
        {
            $data = $request->getBody();
            $cartItemId = $data->cartitemid ?? NULL;

            if(!$cartItemId) $response->json(['status' => FALSE, 'errors' => 'No Info Given!']);

            return $response->json([
                'status' => TRUE,
                'errors' => NULL,
                'carts' => $this->cart->singleCart(Application::$APP->user->id, (int)$cartItemId)
            ]);
        }

        public function getTotal(Request $request, Response $response)
        {
            $cartTotal = $this->cart->cartTotal();
            
            return $response->json([
                'status' => TRUE,
                'errors' => NULL,
                'total' => $cartTotal
            ]);
        }

        public function storeCart(Request $request, Response $response)
        {
            $data = $request->getBody();

            Validator::isInt($data->book_id ?? NULL, 'book', true);
            Validator::isOnlyInt($data->quantity ?? NULL, 'quantity');
            Validator::isOnlyInt($data->discount ?? NULL, 'discount');

            $errors = Validator::validate();

            if(!empty($errors)) {
                return $response->json([ 'status' => FALSE, 'errors' => $errors ]);
            } else {
                $book = $this->book->first($data->book_id);

                if(empty($book)) {
                    return $response->json([ 'status' => FALSE, 'errors' => 'Invalid Book!' ]);
                }

                $checkCartUser = $this->cart->checkCartByUser(Application::$APP->user->id);
                $checkCartBook = $this->cart->checkCartByBook($data->book_id);

                if(!empty($checkCartBook)) {
                    $this->cart->updateItems($checkCartBook, $data, $book);

                    return $response->json([
                        'status' => TRUE,
                        'errors' => NULL,
                        'cartitemid' => $checkCartBook->id,
                        'message' => 'Update Successfull!'
                    ]);
                }

                if(empty($checkCartUser)) {
                    $cartId = $this->cart->store();
                } else {
                    $cartId = $checkCartUser->id;
                }

                $cartItemId = $this->cart->storeItems($cartId, $data, $book);

                return $response->json([
                    'status' => TRUE,
                    'errors' => NULL,
                    'cartitemid' => $cartItemId,
                    'message' => 'Insert Successfull!'
                ]);
            }
        }

        public function deleteCart(Request $request, Response $response)
        {
            $data = $request->getBody();

            Validator::isInt($data->book_id ?? NULL, 'book', true);

            $errors = Validator::validate();

            if(!empty($errors)) {
                return $response->json([ 'status' => FALSE, 'errors' => $errors ]);
            } else {
                $checkCartBook = $this->cart->checkCartByBook((int)$data->book_id);

                if(empty($checkCartBook)) {
                    return $response->json([
                        'staus' => FALSE,
                        'error' => 'Invalid Book!'
                    ]);
                }

                // Delete Cart
                $this->cart->deleteItem($checkCartBook->id);

                return $response->json([ 
                    'status' => TRUE, 
                    'errors' => NULL, 
                    'cartitemid' => $checkCartBook->id
                ]);
            }
        }

        public function clearCart(Request $request, Response $response)
        {
            $cart = $this->cart->checkCartByUser(Application::$APP->user->id);

            if(empty($cart)) {
                return $response->json([ 
                    'status' => FALSE, 
                    'errors' => "You don't have anything in the cart!"
                ]);
            }

            $books = $this->cart->checkCartByCartId($cart->id);

            if(!empty($books)) {
                $this->cart->deleteItems($cart->id);
            }

            $this->cart->deleteCart($cart->id);
        
            return $response->json([
                'status' => TRUE,
                'errors' => NULL,
                'message' => 'Cart cleared for ' . Application::$APP->user->first_name . '.'
            ]);
        }

    }
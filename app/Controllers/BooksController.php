<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\Request;
    use App\Core\Response;
    use App\Core\Validator;
    

    /**
     * Class HomeController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Controllers
     */
    class BooksController extends Controller {

        public function getBooks(Request $request, Response $response)
        {
            return $response->json($this->book->get());
        }

        public function getBook(Request $request, Response $response)
        {
            $id = $request->getBody()->id ?? NULL;
            return $response->json($this->book->first($id));
        }

        public function storeBook(Request $request, Response $response)
        {
            $data = $request->getBody();
            Validator::isInt($data->category_id ?? NULL, 'category', true);
            Validator::isString($data->title ?? NULL, 'title', true);
            Validator::isString($data->description ?? NULL, 'description', true);
            Validator::isString($data->author ?? NULL, 'author', true);
            Validator::isString($data->bookurl ?? NULL, 'bookURL', true);
            Validator::isOnlyInt($data->hardcover_price ?? NULL, 'hardcover price', false);
            Validator::isOnlyInt($data->paperback_price ?? NULL, 'paperback price', false);
            Validator::isOnlyInt($data->online_price ?? NULL, 'hardcover price', false);
            Validator::isString($data->publish_date ?? NULL, 'publish date', true);

            $errors = Validator::validate();
            
            if(empty($errors)) {
                $this->book->create($data);
                return $response->json([ 'status' => TRUE, 'errors' => NULL ]);
            } else {
                return $response->json([ 'status' => FALSE, 'errors' => $errors ]);
            }

        }

        public function updateBook(Request $request, Response $response)
        {
            $data = $request->getBody();

            Validator::isInt($data->id ?? NULL, 'id', true);
            Validator::isInt($data->category_id ?? NULL, 'category', true);
            Validator::isString($data->title ?? NULL, 'title', true);
            Validator::isString($data->description ?? NULL, 'description', true);
            Validator::isString($data->author ?? NULL, 'author', true);
            Validator::isString($data->bookurl ?? NULL, 'bookURL', true);
            Validator::isOnlyInt($data->hardcover_price ?? NULL, 'hardcover price', false);
            Validator::isOnlyInt($data->paperback_price ?? NULL, 'paperback price', false);
            Validator::isOnlyInt($data->online_price ?? NULL, 'hardcover price', false);
            Validator::isString($data->publish_date ?? NULL, 'publish date', true);

            $errors = Validator::validate();
            
            if(empty($errors)) {
                $this->book->update($data);
                return $response->json([ 'status' => TRUE, 'errors' => NULL ]);
            } else {
                return $response->json([ 'status' => FALSE, 'errors' => $errors ]);
            }

        }

        public function deleteBook(Request $request, Response $response)
        {
            $id = $request->getBody()->id ?? NULL;
            $this->book->delete($id);

            return $response->json([ 'status' => true, 'errors' => NULL ]);
        }

    }
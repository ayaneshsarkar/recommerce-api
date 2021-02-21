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
    use App\Middlewares\AdminMiddleware;
    use App\Core\FileHandler;
    

    /**
     * Class HomeController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Controllers
     */
    class BooksController extends Controller {

        public function setAllMiddlewares()
        {
            $this->registerMiddlewares(new AdminMiddleware([
                
            ]));
        }

        public function getBooks(Request $request, Response $response)
        {
            return $response->json($this->book->get());
        }

        public function getBook(Request $request, Response $response)
        {
            $id = $request->getBody()->id ?? NULL;
            return $response->json($this->book->first((int)$id));
        }

        public function storeBook(Request $request, Response $response)
        {
            $data = $request->getBody();
            
            Validator::isInt($data->category_id ?? NULL, 'category', true);
            Validator::isString($data->title ?? NULL, 'title', true);
            Validator::isString($data->description ?? NULL, 'description', false);
            Validator::isString($data->author ?? NULL, 'author', true);
            Validator::isInt($data->price ?? NULL, 'price', true);
            Validator::isInt($data->type_id ?? NULL, 'type', true);
            Validator::isString($data->publish_date ?? NULL, 'publish date', true);

            if($request->hasFile('bookurl')) {
                Validator::isImage($request->getFile('bookurl'), 'bookurl');
            } else {
                return 
                $response->json([ 'status' => FALSE, 'errors' => 'Bookurl is required.' ]);
            }

            $errors = Validator::validate();
            
            if(empty($errors)) {
                $data->bookurl = FileHandler::moveFile($request->getFile('bookurl'));
                $id = $this->book->create($data);
                return $response->json([ 'status' => TRUE, 'errors' => NULL, 'id' => $id ]);
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
            Validator::isString($data->description ?? NULL, 'description', false);
            Validator::isString($data->author ?? NULL, 'author', true);
            Validator::isString($data->bookurl ?? NULL, 'bookURL', true);
            Validator::isInt($data->type_id ?? NULL, 'type', true);
            Validator::isInt($data->price ?? NULL, 'price', true);
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
            Validator::isInt($id, 'id', true);

            $errors = Validator::validate();

            if(empty($errors)) {
                $book = $this->book->first($id);

                if(empty($book)) {
                    return $response->json([ 'status' => false, 'errors' => 'Invalid Id!' ]);
                }

                FileHandler::deleteFile($book->bookurl);
                $this->book->delete($id);
                return $response->json([ 'status' => true, 'errors' => NULL ]);
            } else {
                return $response->json([ 'status' => false, 'errors' => $errors ]);
            }
        }

    }
<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\Validator;
    

    /**
     * Class HomeController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */

    class BooksController extends Controller {

        public function getBooks()
        {
            return json_encode($this->book->get());
        }

        public function getBook()
        {
            $id = json_decode(file_get_contents('php://input'))->id ?? $_GET['id'] ?? NULL;
            return json_encode($this->book->first($id));
        }

        public function storeBook()
        {
            $data = json_decode(file_get_contents('php://input'));
            Validator::isInt($data->category_id, 'category', true);
            Validator::isString($data->title, 'title', true);
            Validator::isString($data->description, 'description', true);
            Validator::isString($data->author, 'author', true);
            Validator::isString($data->bookurl, 'bookURL', true);
            Validator::isOnlyInt($data->hardcover_price, 'hardcover price', false);
            Validator::isOnlyInt($data->paperback_price, 'paperback price', false);
            Validator::isOnlyInt($data->online_price, 'hardcover price', false);
            Validator::isString($data->publish_date, 'publish date', true);

            $errors = Validator::validate();
            
            if(empty($errors)) {
                $this->book->create($data);
                return json_encode( [ 'status' => TRUE, 'errors' => NULL ] );
            } else {
                return json_encode([ 'status' => FALSE, 'errors' => $errors ]);
            }

        }

        public function updateBook()
        {
            $data = json_decode(file_get_contents('php://input'));

            Validator::isInt($data->id, 'id', true);
            Validator::isInt($data->category_id, 'category', true);
            Validator::isString($data->title, 'title', true);
            Validator::isString($data->description, 'description', true);
            Validator::isString($data->author, 'author', true);
            Validator::isString($data->bookurl, 'bookURL', true);
            Validator::isOnlyInt($data->hardcover_price, 'hardcover price', false);
            Validator::isOnlyInt($data->paperback_price, 'paperback price', false);
            Validator::isOnlyInt($data->online_price, 'hardcover price', false);
            Validator::isString($data->publish_date, 'publish date', true);

            $errors = Validator::validate();
            
            if(empty($errors)) {
                $this->book->update($data);
                return json_encode( [ 'status' => TRUE, 'errors' => NULL ] );
            } else {
                return json_encode([ 'status' => FALSE, 'errors' => $errors ]);
            }

        }

        public function deleteBook()
        {
            $id = json_decode(file_get_contents('php://input'))->id ?? $_GET['id'] ?? NULL;
            $this->book->delete($id);

            return json_encode( [ 'status' => true, 'errors' => NULL ] );
        }

    }
<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    

    /**
     * Class HomeController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */

    class BooksController extends Controller {

        public function getBooks()
        {
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');

            return json_encode($this->book->get());
        }

        public function getBook()
        {
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');

            $id = json_decode(file_get_contents('php://input'))->id ?? $_GET['id'] ?? NULL;
            return json_encode($this->book->first($id));
        }

    }
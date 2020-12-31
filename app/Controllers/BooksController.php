<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Controllers;

    use App\Core\Route;
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

    }
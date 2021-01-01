<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;

    /**
     * Class CategoriesController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class CategoriesController extends Controller {

        public function getCategories()
        {
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');

            return json_encode($this->category->get());
        }

    }

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
            return json_encode($this->category->get());
        }

        public function getCategory()
        {
            $id = json_decode(file_get_contents('php://input'))->id ?? $_GET['id'] ?? NULL;
            return json_encode($this->category->first($id));
        }

    }

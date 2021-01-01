<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;

    /**
     * Class UsersController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class UsersController extends Controller {

        public function getUsers()
        {
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');

            return json_encode($this->user->get());
        }

    }
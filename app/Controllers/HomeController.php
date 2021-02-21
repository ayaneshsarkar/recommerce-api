<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 07/01/2021
     */
    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\Request;
    use App\Core\Response;
    use App\Core\Application;

/**
     * Class HomeController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Controllers
     */
    class HomeController extends Controller {
        public function setAllMiddlewares()
        {
            
        }

        public function home(Request $request, Response $response)
        {
            return $response->json([ 
                'status' => TRUE, 
                'errors' => NULL,
                'message' => 'Welcome To Home!'
            ]);
        }
    }
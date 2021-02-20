<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 20/02/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\Request;
    use App\Core\Response;

    /**
     * Class TypesController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */

    class TypesController extends Controller {

        public function setAllMiddlewares()
        {
           
        }

        public function getTypes(Request $request, Response $response)
        {
            return $response->json($this->type->get());
        }
    }

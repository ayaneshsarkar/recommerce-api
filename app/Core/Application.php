<?php

    /**
     * User: Ayanesh Sarkar
     * Date: 04/01/2021
     */

    namespace App\Core;

    use App\Core\Route;
    use App\Core\Request;
    use App\Core\Response;
    use App\Controllers\Controller;

    /**
     * Class Application
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Application {

        public Route $route;
        public Request $request;
        public Response $response;
        public static Application $APP;
        public static string $ROOT_DIR;
        public ?Controller $controller = null;

        public function __construct(string $ROOT_DIR)
        {
            $this->request = new Request();
            $this->response = new Response();
            self::$ROOT_DIR = $ROOT_DIR;
            self::$APP = $this;
            $this->route = new Route($this->request, $this->response);
        }

        public function run()
        {
            echo $this->route->resolve();
        }

    }
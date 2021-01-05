<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Core;

    use App\Core\Application;
    use App\Core\Request;
    use App\Core\Response;

    /**
     * Class Route
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Route {

        public Request $request;
        public Response $response;

        public array $getRoutes = [];
        public array $postRoutes = [];
        public array $deleteRoutes = [];
        public array $putRoutes = [];
        public string $APPPATH;

        public function __construct(Request $request, Response $response)
        {
            $this->request = $request;
            $this->response = $response;
            $this->APPPATH = dirname($_SERVER['DOCUMENT_ROOT']);
        }


        public function get($url, $callback)
        {
            $this->getRoutes[$url] = $callback;
        }

        public function post($url, $callback)
        {
            $this->postRoutes[$url] = $callback;
        }

        public function put($url, $callback)
        {
            $this->putRoutes[$url] = $callback;
        }
        
        public function delete($url, $callback)
        {
            $this->deleteRoutes[$url] = $callback;
        }

        public function resolve()
        {
            $method = $this->request->getMethod();
            $url = $this->request->getURL() ?? '/';

            if($url !== '/' && substr($url , -1) === '/') {
                $url = rtrim($url, '/');
            }

            if($method === 'GET') {
                $callback = $this->getRoutes[$url] ?? NULL;
            } else if($method === "DELETE") {
                $callback = $this->deleteRoutes[$url] ?? NULL;
            } else if($method === "PUT") {
                $callback = $this->putRoutes[$url] ?? NULL;
            } else {
                $callback = $this->postRoutes[$url] ?? NULL;
            }

            if(!$callback) {
                echo 'Page Not Found';
                exit;
            }

            if(is_array($callback)) {
                $controller = new $callback[0]();
                Application::$APP->controller = $controller;
                $callback[0] = $controller;
            }

            return call_user_func($callback, $this->request, $this->response);
        }

        /**
         * function view
         *
         * @param string $view
         * @param array $params
         *
         * @return string
         */
        public function view(string $view, array $params = []): string
        {
            foreach($params as $key => $value) {
                $$key = $value;
            }

            ob_start();
            include $this->APPPATH . "/resources/views/$view.php";
            return ob_get_clean();
        }

    }

        
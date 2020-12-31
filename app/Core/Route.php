<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Core;

    /**
     * Class Route
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Route {

        public array $getRoutes = [];
        public array $postRoutes = [];
        public string $APPPATH;

        public function __construct()
        {
            $this->APPPATH = dirname($_SERVER['DOCUMENT_ROOT']);
        }


        public function get($url, $callback)
        {
            $this->getRoutes[$url] = $callback;
        }

        public function postRoutes($url, $callback)
        {
            $this->postRoutes[$url] = $callback;
        }

        public function resolve()
        {
            $method = $_SERVER['REQUEST_METHOD'];
            $url = $_SERVER['PATH_INFO'] ?? '/';

            
            if($url !== '/' && substr($url , -1) === '/') {
                $url = rtrim($url, '/');
            }

            if($method === 'GET') {
                $callback = $this->getRoutes[$url] ?? NULL;
            } else {
                $callback = $this->postRoutes[$url] ?? NULL;
            }

            if(!$callback) {
                echo 'Page Not Found';
                exit;
            }

            if(is_array($callback)) {
                $callback[0] = new $callback[0]();
            }

            echo call_user_func($callback, $this);
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

        
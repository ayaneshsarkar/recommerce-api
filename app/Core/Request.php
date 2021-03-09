<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 04/01/2021
     */

    namespace App\Core;

    /**
     * Class Request
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Core
     */

    use App\Exceptions\ForbiddenException;
    class Request {

        public function getMethod()
        {
            return $_SERVER['REQUEST_METHOD'];
        }

        public function getURL()
        {
            $url = $_SERVER['REQUEST_URI'];
            $position = strpos($url, '?');
            if($position !== FALSE) {
                $url = substr($url, 0, $position);
            }

            return $url;
        }

        public function isGet()
        {
            return $this->getMethod() === 'GET';
        }

        public function isPost()
        {
            return $this->getMethod() === 'POST';
        }

        public function hasFile(string $name)
        {
            return $_FILES["$name"]["name"] ? true : false;
        }

        public function getFile(string $name) {
            return $_FILES["$name"];
        }

        public function getBody()
        {
            $data = [];

            if(!empty($_POST)) {
                $dataArr = $_POST;
            } else if(!empty($_GET)) {
                $dataArr = $_GET;
            } else {
                $dataArr = json_decode(file_get_contents('php://input'));
            }

            // if(\is_null($dataArr)) {
            //     $data = [];
            //     return (object)$data;
            // }
            
            foreach($dataArr as $key => $value) {
                if(is_int($value)) {
                    $data[$key] = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                } else if(!is_array($value)) {
                    $data[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
            
            return (object)$data;
        }

    }
<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 04/01/2021
     */

    namespace App\Core;

    /**
     * Class Response
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Core
     */
    Class Response {

        public function setStatusCode(int $code)
        {
            http_response_code($code);
        }

        public function redirect(string $url)
        {
            header("Location: $url");
        }

        public function json($data)
        {
            return json_encode($data);
        }

    }
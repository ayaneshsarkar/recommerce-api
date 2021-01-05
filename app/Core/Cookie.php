<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 05/01/2021
     */

    namespace App\Core;

    use \DateTime;

/**
     * Class Cookie
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Core
     */
    class Cookie {

        /**
         * function set
         *
         * @param array $data
         * @param \DateTime $exp
         *
         * @return void
         */
        public static function set($key, $value, $exp)
        {
            setcookie($key, $value, $exp);
        }

        /**
         * function get
         *
         * @param string $index
         *
         * @return void
         */
        public static function get(string $index)
        {
            // foreach($_COOKIE as $key => $value) {
            //     if($key === $index && $value !== 'deleted') {
            //         return $_COOKIE[$key];
            //     }
            // }

            return $_COOKIE[$index] ?? NULL;
        }

        /**
         * function remove
         *
         * @param string $key
         *
         * @return void
         */
        public static function remove(string $key)
        {
            return setcookie($key, '', time() - 1);
        }

    }
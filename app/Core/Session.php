<?php 

    /**
     * User: Ayanesh Sarkar
     * Data: 05/01/2021
     */
    namespace App\Core;

    /**
     * Class Session
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Core
     */
    class Session {

        /**
         * function set
         *
         * @param string $key
         * @param [type] $value
         *
         * @return void
         */
        public static function set(string $key, $value)
        {
            $_SESSION[$key] = $value;
        }

        /**
         * function get
         *
         * @param string $key
         *
         * @return void
         */
        public static function get(string $key)
        {
            return $_SESSION[$key] ?? NULL;
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
            unset($_SESSION[$key]);
        }

    }
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

        public function __construct()
        {
            if(!isset($_SESSION)) {
                session_start();
            }
        }

        /**
         * function set
         *
         * @param string $key
         * @param [type] $value
         *
         * @return void
         */
        public function set(string $key, $value)
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
        public function get(string $key)
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
        public function remove(string $key)
        {
            unset($_SESSION[$key]);
        }

    }
<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Core;

    /**
     * Class Validator
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Validator {

        public static array $errors = [];

        /**
         * function isString
         *
         * @param string|null $value
         * @param string|null $key
         * @param boolean $req
         * 
         */
        public static function isString($value, ?string $key, bool $req)
        {
            $key = strtolower($key);

            if($req && empty($value)) {
                self::$errors[$key] = "The $key is required.";
            } 
            
            if(!empty($value) && 
            (!filter_var($value, FILTER_SANITIZE_STRING) || !is_string($value))) {
                self::$errors[$key] = "The $key has to be a string.";
            }
        }

        /**
         * function isEmail
         *
         * @param string|null $value
         * @param string|null $key
         * @param boolean $req
         * 
         */
        public static function isEmail($value, ?string $key, bool $req)
        {
            $key = strtolower($key);

            if($req && empty($value)) {
                self::$errors[$key] = "The $key is required.";
            } 
            
            if(!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                self::$errors[$key] = "The $key has to be a valid email.";
            }
        }

        /**
         * function isInt
         *
         * @param int|null $value
         * @param string|null $key
         * @param boolean $req
         * 
         */
        public static function isInt($value, $key, bool $req)
        {
            $key = strtolower($key);

            if($req && empty($value)) {
                self::$errors[$key] = "The $key is required.";
            } else if(!filter_var($value, FILTER_VALIDATE_INT)) {
                self::$errors[$key] = "The $key has to be a number.";
            }

        }

        /**
         * Undocumented isOnlyInt
         *
         * @param [type] $value
         * @param [type] $key
         * @param boolean $req
         *         
        */
        public static function isOnlyInt($value, $key, bool $req = false)
        {
            $key = strtolower($key);

            if(!filter_var($value, FILTER_VALIDATE_INT) && !empty($value)) {
                self::$errors[$key] = "The $key has to be a number.";
            }
        }

        /**
         * function validate
         *
         * @return array|null
         */
        public static function validate(): ?array
        {
            if(!empty(self::$errors)) {
                return self::$errors;
            } else{
                return NULL;
            }
        }

    }
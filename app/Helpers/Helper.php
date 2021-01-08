<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 08/01/2021
     */
    namespace App\Helpers;

    /**
     * Class Helper
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Helpers
     */
    class Helper {

        /**
         * function $randomString 
         * is often used to generate random and unique alphanumeric strings.
         *
         * @param integer $length
         *
         * @return string|null
         */
        public static function randomString(int $length): ?string
        {
            $strResult = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

            return substr(str_shuffle($strResult), 0, $length);
        }

    }
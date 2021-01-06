<?php 

    /**
     * Name: Ayanesh Sarkar
     * Date: 06/01/2021
     */
    namespace App\Exceptions;

    use \Exception;
    
    /**
     * Class ForbiddenException
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Exceptions
     */
    class ForbiddenException extends \Exception {

        protected $message = "You don't have permission to access this page!";
        protected $code = 403;

    }
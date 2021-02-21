<?php 

    /**
     * Name: Ayanesh Sarkar
     * Date: 21/02/2021
     */
    namespace App\Exceptions;

    use \Exception;
    
    /**
     * Class ForbiddenException
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Exceptions
     */
    class FileException extends \Exception {

        protected $message = "There was an error uploading the File!";
        protected $code = 404;

    }
<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 05/01/2021
     */

    namespace App\Middlewares;

    /**
     * Class Middleware
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Middlewares;
     */
    abstract class Middleware {

        abstract function execute();

    }
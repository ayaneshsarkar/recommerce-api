<?php 

    /**
     * Name: Ayanesh Sarkar
     * Date: 06/01/2021
     */
    namespace App\Middlewares;

    use App\Core\Application;
    use App\Middlewares\Middleware;
    use App\Exceptions\ForbiddenException;

    /**
     * Class AuthMiddleware
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Middlewares
     */
    class AuthMiddleware extends Middleware {

        protected array $actions = [];

        public function __construct(array $actions = [])
        {
            $this->actions = $actions;
        }

        public function execute()
        {
            if(Application::isGuest() === TRUE) {
                if(in_array(Application::$APP->controller->action, $this->actions)) {
                    throw new ForbiddenException();
                }
            }
        }

    }
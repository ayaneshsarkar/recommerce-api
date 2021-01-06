<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 06/01/2021
     */
    namespace App\Middlewares;

    use App\Middlewares\Middleware;
    use App\Core\Application;
    use App\Exceptions\ForbiddenException;

    /**
     * Class AdminMiddleware
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Middlewares
     */
    class AdminMiddleware extends Middleware {

        protected array $actions = [];

        public function __construct(array $actions = [])
        {
            $this->actions = $actions;
        }

        public function execute()
        {
            if(Application::isGuest()) {
                if(in_array(Application::$APP->controller->action, $this->actions)
                && Application::$APP->user->type !== 'admin') {
                    throw new ForbiddenException();
                }
            }
        }

    }
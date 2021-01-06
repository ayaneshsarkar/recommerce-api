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
            if(Application::$APP->controller->action === Application::$APP->request->getURL()) {
                if(in_array(Application::$APP->controller->action, $this->actions)) {
                    if(!Application::isGuest() && Application::$APP->user->type !== 'admin') {
                        throw new ForbiddenException();
                    } else if (Application::isGuest()) {
                        throw new ForbiddenException();
                    }
                }
            }
        }

    }
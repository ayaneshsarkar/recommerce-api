<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 06/01/2021
     */

    namespace App\Middlewares;

    use App\Core\Application;
    use App\Middlewares\Middleware;
    use App\Exceptions\ForbiddenException;
    use Firebase\JWT\JWT;

/**
     * Class TokenMiddleware
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Middlewares
     */
    class TokenMiddleware extends Middleware {

        public function execute()
        {
            $accessToken = Application::$APP->accessToken;
            $refreshToken = Application::$APP->refreshToken;

            if(!is_null($accessToken) && !is_null($refreshToken)) {
                try {
                    $user = JWT::decode($accessToken, $_ENV['SECRET_KEY'], ['HS256']);
                    $dbUser = Application::$DB->first('users', 'id', $user->user_id);

                    if(!empty($dbUser)) {
                        unset($dbUser->password);
                        unset($dbUser->token);

                        Application::$APP->user = $dbUser;
                    } else {
                        // throw new ForbiddenException();
                    }

                } catch(\Exception $e) {

                    try {
                        $user = JWT::decode($refreshToken, $_ENV['REFRESH_KEY'], ['HS256']);
                        $dbUser = Application::$DB->first('users', 'id', $user->user_id);
                        
                        if(!empty($dbUser) && $dbUser->token === $refreshToken) {
                            unset($dbUser->password);
                            unset($dbUser->token);

                            $payload = [
                                "iss" => $_ENV['BASE_URL'],
                                "aud" => $_ENV['BASE_URL'],
                                "iat" => time(),
                                "nbf" => time(),
                                "exp" => time() + 120,
                                "user_id" => $dbUser->id
                            ];

                            Application::$APP->accessToken = JWT::encode(
                                $payload, 
                                $_ENV['SECRET_KEY']
                            );

                            Application::$APP->user = $dbUser;

                        } else {
                            // throw new ForbiddenException();
                        }
                    } catch(\Exception $e) {
                        // throw new ForbiddenException();
                    }

                }
            }
        }

    }
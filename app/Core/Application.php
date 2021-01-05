<?php

    /**
     * User: Ayanesh Sarkar
     * Date: 04/01/2021
     */

    namespace App\Core;

    use App\Core\Route;
    use App\Core\Request;
    use App\Core\Response;
    use App\Core\Database;
    use App\Core\Session;
    use App\Controllers\Controller;
    use Firebase\JWT\JWT;

    /**
     * Class Application
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class Application {

        public Route $route;
        public Request $request;
        public Response $response;
        public static Application $APP;
        public static string $ROOT_DIR;
        public ?Controller $controller = null;
        public static ?object $user = null;
        public static ?Database $DB = null;
        public static ?string $accessToken = null;

        public function __construct(string $ROOT_DIR)
        {
            $this->request = new Request();
            $this->response = new Response();
            self::$ROOT_DIR = $ROOT_DIR;
            self::$APP = $this;
            $this->route = new Route($this->request, $this->response);
            self::$DB = new Database();
            
            //$this->validate();

            var_dump($_SESSION); echo "\n";
        }

        public static function isGuest(): bool
        {
            return !self::$APP->user;
        }

        public static function login(object $user, array $payload)
        {
            $accessToken = JWT::encode($payload, $_ENV['SECRET_KEY']);
            unset($payload['exp']);

            $refreshToken = JWt::encode($payload, $_ENV['REFRESH_KEY']);

            if(!empty(Session::get('access_token'))) {
                Session::remove('access_token');
            }

            if(!empty(Session::get('refresh_token'))) {
                Session::remove('refresh_token');
            }

            Session::set('access_token', $accessToken);
            Session::set('refresh_token', $refreshToken);

            self::$DB->set('users', 'id', $user->id, [ 'token' => $refreshToken ]);

            self::$user = $user;
        }

        public static function logout()
        {
            $accessToken = Session::get('access_token');
            $refreshToken = Session::get('refresh_token');

            if(!empty($accessToken)) {
                Session::remove('access_token');
            }

            if(!empty($refreshToken)) {
                Session::remove('refresh_token');
            }
        }

        public function validate()
        {
            $accessToken = Session::get('access_token');
            $refreshToken = Session::get('refresh_token');

            if(!empty($refreshToken)) {
                $userData = JWT::decode($refreshToken, $_ENV['REFRESH_KEY'], ['HS256']);
                $userId = $userData->user_id;

                echo $this->response->json((array)$userData);

                self::$user = self::$DB->first('users', 'id', $userId);

                if(empty($accessToken)) {
                    $payload = [
                        "iss" => $_ENV['BASE_URL'],
                        "aud" => $_ENV['BASE_URL'],
                        "iat" => time(),
                        "nbf" => time(),
                        "exp" => time() + (3 * 60),
                        "user_id" => $userId
                    ];

                    $accessToken = JWT::encode($payload, $_ENV['SECRET_KEY']);
                    Session::set('access_token', $accessToken);
                }
            }

            self::$accessToken = $accessToken ?? NULL;
        }

        public function validateForMiddleware()
        {
            $refreshToken = Cookie::get('refresh_token');

            if(!empty($refreshToken) && !empty(self::$user) 
            && self::$user->token !== $refreshToken) {
                // Do Something
            }
        }

        public function run()
        {
            echo $this->route->resolve();
        }

    }
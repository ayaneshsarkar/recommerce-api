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
    use App\Controllers\Controller;
    use App\Exceptions\ForbiddenException;
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
        public ?object $user = null;
        public ?Controller $controller = null;
        public static ?Database $DB = null;
        public ?string $accessToken = null;
        public ?string $refreshToken = null;

        public function __construct(string $ROOT_DIR)
        {
            $this->request = new Request();
            $this->response = new Response();
            self::$ROOT_DIR = $ROOT_DIR;
            self::$APP = $this;
            $this->route = new Route($this->request, $this->response);
            self::$DB = new Database();

            $this->getAuthHeader();
        }

        public static function isGuest(): bool
        {
            if(is_null(self::$APP->user)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public static function login(object $user, array $payload): object
        {
            $accessToken = JWT::encode($payload, $_ENV['SECRET_KEY']);
            unset($payload['exp']);

            $refreshToken = JWt::encode($payload, $_ENV['REFRESH_KEY']);

            self::$DB->set('users', 'id', $user->id, [ 'token' => $refreshToken ]);

            unset($user->password);
            unset($user->token);

            self::$APP->user = $user;

            return (object)['accessToken' => $accessToken, 'refreshToken' => $refreshToken];
        }

        public static function logout(): bool
        {
            self::$APP->accessToken = null;
            self::$APP->refreshToken = null;

            self::$DB->set('users', 'id', self::$APP->user->id, ['token' => NULL]);
            self::$APP->user = NULL;

            return TRUE;
        }

        public function getAuthHeader()
        {
            $headers = getallheaders();
            
            foreach($headers as $key => $value) {
                if(strtolower($key) === 'authorization') {
                    $value = explode(' ', $value);
                    self::$APP->accessToken = $value[1] ?? null;
                    self::$APP->refreshToken = $value[2] ?? null;
                }
            }
        }

        public function run()
        {
            try {
                echo $this->route->resolve();
            } catch(\Exception $e) {
                echo $this->response->json([
                    'status' => FALSE,
                    'errors' => $e->getCode() . ' - ' . $e->getMessage()
                ]);
            }
        }

    }
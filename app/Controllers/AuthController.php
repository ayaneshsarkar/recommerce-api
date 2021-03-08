<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 05/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\Application;
    use App\Core\Request;
    use App\Core\Response;
    use App\Core\Validator;
    use App\Middlewares\AuthMiddleware;
    use App\Middlewares\FreeAuthMiddleware;

/**
     * Class AuthController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Controllers
     */
    class AuthController extends Controller {

        public function setAllMiddlewares()
        {
            $this->registerMiddlewares(new FreeAuthMiddleware(['/login']));
            $this->registerMiddlewares(new AuthMiddleware(['/logout']));
        }

        public function login(Request $request, Response $response)
        {
            $data = $request->getBody();

            if(empty($data)) {
                return $response->json([ 'status' => FALSE, 'error' => 'Invalid Data!' ]);
            }

            Validator::isEmail($data->email ?? NULL, 'email', TRUE);
            Validator::isString($data->password ?? NULL, 'password', TRUE);

            $errors = Validator::validate();

            if(empty($errors)) {
                $authProcess = $this->user->authenticate($data);
                $auth = $authProcess->auth;

                if($auth === TRUE) {
                    $user = $authProcess->user;

                    $payload = [
                        "iss" => $_ENV['BASE_URL'],
                        "aud" => $_ENV['BASE_URL'],
                        "iat" => time(),
                        "nbf" => time(),
                        "exp" => time() + 120,
                        "user_id" => $user->id
                    ];

                    $tokens = Application::login($user, $payload);

                    return $response->json([
                        'status' => TRUE,
                        'errors' => NULL,
                        'message' => 'Logged In!',
                        'access_token' => $tokens->accessToken,
                        'refresh_token' => $tokens->refreshToken,
                        'user' => Application::$APP->user
                    ]);
                } else {
                    return $response->json([
                        'status' => FALSE,
                        'errors' => 'Invalid Credentials!',
                        'user' => null
                    ]);
                }
            } else {
                return $response->json([ 
                    'status' => FALSE, 
                    'errors' => $errors, 
                    'user' => null 
                ]);
            }


        }

        public function logout(Request $request, Response $response)
        {
            if(is_null(Application::$APP->user)) {
                return $response->json([ 'status' => FALSE, 'error' => 'User is not logged in!' ]);
            } else {
                $logout = Application::logout();

                if($logout === TRUE) {
                    return $response->json([ 
                        'status' => TRUE, 
                        'errors' => NULL,
                        'message' => 'Successfully Logged Out!' 
                    ]);
                }
            }
        }

        public function verifyUser(Request $request, Response $response) {
            $user = Application::$APP->user;

            if(!$user) {
                return $response->json([ 'loggedIn' => false, 'user' => null ]);
            }

            return $response->json([ 'loggedIn' => true, 'user' => Application::$APP->user ]);
        }

    }
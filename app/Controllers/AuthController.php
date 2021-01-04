<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 05/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\Request;
    use App\Core\Response;
    use App\Core\Validator;

    /**
     * Class AuthController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App\Controllers
     */
    class AuthController extends Controller {

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
                $auth = $this->user->authenticate($data);

                if($auth === TRUE) {
                    return $response->json([
                        'status' => TRUE,
                        'errors' => NULL,
                        'message' => 'Logged In!'
                    ]);
                } else {
                    return $response->json([
                        'status' => FALSE,
                        'errors' => 'Invalid Credentials'
                    ]);
                }
            } else {
                return $response->json([ 'status' => FALSE, 'errors' => $errors ]);
            }


        }

    }
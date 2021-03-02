<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\FileHandler;
    use App\Core\Request;
    use App\Core\Response;
    use App\Core\Validator;
    use App\Middlewares\AdminMiddleware;
    use App\Middlewares\FreeAuthMiddleware;
    use App\Middlewares\AuthMiddleware;

    /**
     * Class UsersController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class UsersController extends Controller {

        public function setAllMiddlewares()
        {
            $this->registerMiddlewares(new FreeAuthMiddleware(['/register-user']));
            $this->registerMiddlewares(new AuthMiddleware(['/edit-user']));
            $this->registerMiddlewares(new AdminMiddleware([
                '/get-user'
            ]));
        }

        public function getUsers()
        {
            return json_encode($this->user->get());
        }

        public function getUser()
        {
            $id = json_decode(file_get_contents('php://input'))->id ?? $_GET['id'] ?? NULL;
            return json_encode($this->user->first($id));
        }

        public function register(Request $request, Response $response)
        {
            $data = $request->getBody();

            Validator::isString($data->first_name ?? NULL, 'first name', true);
            Validator::isString($data->last_name ?? NULL, 'last name', true);
            Validator::isEmail($data->email ?? NULL, 'email', true);
            Validator::isString($data->password ?? NULL, 'password', true);
            Validator::isString($data->address ?? NULL, 'address', false);
            Validator::isString($data->city ?? NULL, 'city', true);
            Validator::isString($data->state ?? NULL, 'state', true);
            Validator::isString($data->country ?? NULL, 'country', true);
            Validator::isString($data->date_of_birth ?? NULL, 'date of birth', true);
            Validator::isString($data->token ?? NULL, 'token', false);
            Validator::isString($data->type ?? NULL, 'type', false);

            if($request->hasFile('avatar')) {
                Validator::isImage($request->getFile('avatar'), 'avatar');
            }

            $errors = Validator::validate();

            if(empty($errors)) {
                $checkMail = $this->user->checkMail($data->email);

                if(!empty($checkMail)) {
                    return $response->json([ 
                        'status' => FALSE, 
                        'errors' => 'User Already Exists.' 
                    ]);
                } else {
                    if($request->hasFile('avatar')) {
                        $file = $request->getFile('avatar');
                        $data->avatar = FileHandler::moveFile($file, 'avatars');
                    }

                    $this->user->create($data);
                    return $response->json([ 'status' => TRUE, 'errors' => NULL ]);
                }
            } else {
                return $response->json([ 'status' => FALSE, 'errors' => $errors ]);
            }
        }

        public function updateUser()
        {
            $data = json_decode(file_get_contents('php://input'));

            Validator::isInt($data->id, 'id', true);
            Validator::isString($data->first_name, 'first name', true);
            Validator::isString($data->last_name, 'last name', true);
            Validator::isEmail($data->email, 'email', true);
            Validator::isString($data->avatar, 'avatar', true);
            Validator::isString($data->address, 'address', true);
            Validator::isString($data->city, 'city', true);
            Validator::isString($data->state, 'state', true);
            Validator::isString($data->country, 'country', true);
            Validator::isString($data->date_of_birth, 'date of birth', true);
            Validator::isString($data->token, 'token', false);
            Validator::isString($data->type, 'type', false);

            $errors = Validator::validate();

            if(empty($errors)) {
                $checkMail = $this->user->checkMail($data->email);

                if(!empty($checkMail) && $checkMail->id === $data->id) {
                    $this->user->update($data);
                    return json_encode([ 'status' => TRUE, 'errors' => NULL ]);
                } else {
                    return json_encode([ 'status' => FALSE, 'errors' => 'Invalid Email!' ]);
                }

            } else {
                return json_encode([ 'status' => FALSE, 'errors' => $errors ]);
            }
        }

        public function deleteUser(Request $request, Response $response)
        {
            $data = $request->getBody();
            $id = $data->id ?? NULL;

            if(!empty($this->user->first($id))) {
                $this->user->delete($id);
                return json_encode([ 'status' => TRUE, 'errors' => NULL ]);
            } else {
                json_encode([ 'status' => FALSE, 'errors' => 'Invalid Operation!' ]);
            }
        }
    }
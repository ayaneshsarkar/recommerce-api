<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
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
                '/get-user', '/get-users', '/delete-user'
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

        public function register()
        {
            $data = json_decode(file_get_contents('php://input'));

            Validator::isString($data->first_name, 'first name', true);
            Validator::isString($data->last_name, 'last name', true);
            Validator::isEmail($data->email, 'email', true);
            Validator::isString($data->password, 'password', true);
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

                if(!empty($checkMail)) {
                    return json_encode(
                        [ 'status' => FALSE, 'errors' => 'User Already Exists.' ]
                    );
                } else {
                    $this->user->create($data);
                    return json_encode([ 'status' => TRUE, 'errors' => NULL ]);
                }
            } else {
                return json_encode([ 'status' => FALSE, 'errors' => $errors ]);
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

        public function deleteUser()
        {
            $id = json_decode(\file_get_contents('php://input'))->id ?? $_GET['id'] ?? NULL;

            if(!empty($this->user->first($id))) {
                $this->user->delete($id);
                return json_encode([ 'status' => TRUE, 'errors' => NULL ]);
            } else {
                json_encode([ 'status' => FALSE, 'errors' => 'Invalid Operation!' ]);
            }
        }
    }
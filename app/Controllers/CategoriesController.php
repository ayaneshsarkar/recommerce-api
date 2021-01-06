<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 01/01/2021
     */

    namespace App\Controllers;

    use App\Controllers\Controller;
    use App\Core\Validator;
    use App\Middlewares\AdminMiddleware;

    /**
     * Class CategoriesController
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    class CategoriesController extends Controller {

        public function setAllMiddlewares()
        {
            $this->registerMiddlewares(new AdminMiddleware([
                '/create-category', '/update-category', '/delete-category'
            ]));
        }

        public function getCategories()
        {
            return json_encode($this->category->get());
        }

        public function getCategory()
        {
            $id = json_decode(file_get_contents('php://input'))->id ?? $_GET['id'] ?? NULL;
            return json_encode($this->category->first($id));
        }

        public function storeCategory()
        {
            $data = json_decode(file_get_contents('php://input'));

            Validator::isString($data->name, 'name', true);

            $errors = Validator::validate();

            if(empty($errors)) {
                $this->category->create($data);
                return json_encode([ 'status' => TRUE, 'errors' => NULL ]);
            } else {
                return json_encode([ 'status' => FALSE, 'errors' => $errors ]);
            }
        }

        public function updateCategory()
        {
            $data = json_decode(file_get_contents('php://input'));

            Validator::isInt($data->id, 'id', true);
            Validator::isString($data->name, 'name', true);

            $errors = Validator::validate();

            if(empty($errors)) {
                $this->category->update($data);
                return json_encode([ 'status' => TRUE, 'errors' => NULL ]);
            } else {
                return json_encode([ 'status' => FALSE, 'errors' => $errors ]);
            }
        }

        public function deleteCategory()
        {
            $id = json_decode(file_get_contents('php://input'))->id ?? $_GET['id'] ?? NULL;
            $this->category->delete($id);

            return json_encode([ 'status' => TRUE, 'errors' => NULL ]);
        }

    }

<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    require_once __DIR__ . '/../vendor/autoload.php';

    use Dotenv\Dotenv;
    use App\Core\Application;
    use App\Controllers\BooksController;
    use App\Controllers\CategoriesController;
    use App\Controllers\UsersController;
    use App\Controllers\AuthController;

    $env = Dotenv::createImmutable(dirname(__DIR__));
    $env->load();

    $app = new Application(dirname($_SERVER['DOCUMENT_ROOT']));
    $route = $app->route;

    // Books
    $route->get('/get-books', [BooksController::class, 'getBooks']);
    $route->get('/get-book', [BooksController::class, 'getBook']);
    $route->post('/get-book', [BooksController::class, 'getBook']);
    $route->post('/create-book', [BooksController::class, 'storeBook']);
    $route->put('/edit-book', [BooksController::class, 'updateBook']);
    $route->get('/delete-book', [BooksController::class, 'deleteBook']);
    $route->delete('/delete-book', [BooksController::class, 'deleteBook']);

    // Categories
    $route->get('/get-categories', [CategoriesController::class, 'getCategories']);
    $route->get('/get-category', [CategoriesController::class, 'getCategory']);
    $route->post('/get-category', [CategoriesController::class, 'getCategory']);
    $route->post('/create-category', [CategoriesController::class, 'storeCategory']);
    $route->put('/edit-category', [CategoriesController::class, 'updateCategory']);
    $route->get('/delete-category', [CategoriesController::class, 'deleteCategory']);
    $route->delete('/delete-category', [CategoriesController::class, 'deleteCategory']);
    
    // Users
    $route->get('/get-users', [UsersController::class, 'getUsers']);
    $route->get('/get-user', [UsersController::class, 'getUser']);
    $route->post('/get-user', [UsersController::class, 'getUser']);
    $route->post('/register-user', [UsersController::class, 'register']);
    $route->put('/edit-user', [UsersController::class, 'updateUser']);
    $route->get('/delete-user', [UsersController::class, 'deleteUser']);
    $route->delete('/delete-user', [UsersController::class, 'deleteUser']);

    // Auth
    $route->post('/login', [AuthController::class, 'login']);




    
    $app->run();
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
    use App\Controllers\HomeController;
    use App\Controllers\CartsController;
    use App\Controllers\StripeController;
    use App\Controllers\TypesController;

    $env = Dotenv::createImmutable(dirname(__DIR__));
    $env->load();

    $app = new Application(dirname($_SERVER['DOCUMENT_ROOT']));
    $route = $app->route;

    
    // Home
    $route->get('/', [HomeController::class, 'home']);

    // Books
    $route->get('/get-books', [BooksController::class, 'getBooks']);
    $route->get('/get-book', [BooksController::class, 'getBook']);
    $route->post('/get-book', [BooksController::class, 'getBook']);
    $route->post('/create-book', [BooksController::class, 'storeBook']);
    $route->put('/edit-book', [BooksController::class, 'updateBook']);
    $route->post('/edit-book', [BooksController::class, 'updateBook']);
    $route->get('/delete-book', [BooksController::class, 'deleteBook']);
    $route->delete('/delete-book', [BooksController::class, 'deleteBook']);

    // Book Types
    $route->get('/get-types', [TypesController::class, 'getTypes']);

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
    $route->delete('/logout', [AuthController::class, 'logout']);
    $route->post('/verify-user', [AuthController::class, 'verifyUser']);

    // Carts
    $route->get('/carts', [CartsController::class, 'getCarts']);
    $route->post('/cart', [CartsController::class, 'storeCart']);
    $route->delete('/delete-cart', [CartsController::class, 'deleteCart']);
    $route->delete('/clear-cart', [CartsController::class, 'clearCart']);

    // Stripe
    $route->get('/stripe', [StripeController::class, 'payment']);

    $app->run();
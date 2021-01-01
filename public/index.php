<?php 

    require_once __DIR__ . '/../vendor/autoload.php';

    use Dotenv\Dotenv;
    use App\Core\Route;
    use App\Controllers\BooksController;
    use App\Controllers\CategoriesController;
    use App\Controllers\UsersController;
    
    $env = Dotenv::createImmutable(dirname(__DIR__));
    $env->load();

    // dirname($_SERVER['DOCUMENT_ROOT'])
    $route = new Route();

    $route->get('/get-books', [BooksController::class, 'getBooks']);
    $route->get('/get-book', [BooksController::class, 'getBook']);
    $route->post('/get-book', [BooksController::class, 'getBook']);
    $route->get('/get-categories', [CategoriesController::class, 'getCategories']);
    $route->get('/get-users', [UsersController::class, 'getUsers']);

    $route->resolve();

<?php 

    require_once __DIR__ . '/../vendor/autoload.php';

    use App\Core\Route;
    use App\Controllers\BooksController;
    use Dotenv\Dotenv;
    
    $env = Dotenv::createImmutable(dirname(__DIR__));
    $env->load();

    // dirname($_SERVER['DOCUMENT_ROOT'])
    $route = new Route();

    $route->get('/get-books', [BooksController::class, 'getBooks']);

    $route->resolve();



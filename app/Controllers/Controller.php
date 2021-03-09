<?php 

    /**
     * User: Ayanesh Sarkar
     * Date: 31/12/2020
     */

    namespace App\Controllers;

    use App\Core\Application;
    use App\Models\Book;
    use App\Models\Category;
    use App\Models\User;
    use App\Models\Cart;
    use App\Models\Type;
    use App\Models\Order;
    use App\Middlewares\Middleware;
    use App\Middlewares\TokenMiddleware;
    use Stripe\Stripe;

    /**
     * Class Controller
     * @author Ayanesh Sarkar <ayaneshsarkar101@gmail.com>
     * @package App
     */
    abstract class Controller {

        public \PDO $db;

        // Middleware Process
        public string $action = '';
        public array $middlewares = [];

        // Model Declares
        public Book $book;
        public Category $category;
        public User $user;
        public Cart $cart;

        public function __construct()
        {
            // Database Init
            $this->db = Application::$DB->pdo;

            // Models
            $this->book = new Book();
            $this->category = new Category();
            $this->user = new User();
            $this->cart = new Cart();
            $this->type = new Type();
            $this->order = new Order();


            $this->registerMiddlewares(new TokenMiddleware());
            $this->setAllMiddlewares();

            Stripe::setApiKey($_ENV['STRIPE_API_KEY']);
        }

        abstract public function setAllMiddlewares();

        protected function registerMiddlewares(Middleware $middleware)
        {
            $this->middlewares[] = $middleware;
        }

        public function getMiddlewares(): array
        {
            return $this->middlewares;
        }

    }
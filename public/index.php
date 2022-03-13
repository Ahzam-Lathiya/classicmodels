<?php

require_once "../vendor/autoload.php";


use app\controllers\admin\SiteController;
use app\controllers\admin\LoginController;
use app\controllers\admin\OrdersController;
use app\controllers\admin\ProductLinesController;
use app\controllers\admin\ProductsController;
use app\controllers\admin\CustomersController;
use app\controllers\admin\ProfileController;
use app\controllers\admin\SecretController;

use app\controllers\site\SiteProductsController;

use app\models\Employees;

use app\core\Application;
use app\core\Container;
use Swoole\Coroutine;
use Swoole\HTTP\Server as HttpServer;
use Swoole\Runtime;

ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://localhost:6379');
ini_set('session.serialize_handler', 'php_serialize');

Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);

//load configuration from .env file
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$globalConfig = [
           'user_class' => Employees::class,
           'DB_CONFIG' => 
            [
              'dsn' => $_ENV['DB_DSN'],
              'user' => $_ENV['DB_USER'],
              'pass' => $_ENV['DB_PASS']
            ],
            'permit_chars' => $_ENV['permit_chars']
          ];

$server = new HttpServer('127.0.0.1', 8000, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);


$server->set([
    //'worker_num' => 4,      // The number of worker processes to start
    //'task_worker_num' => 4,  // The amount of task workers to start
    //'backlog' => 128,       // TCP backlog connection number
    'enable_coroutine' => true
]);


//$container = new Container();
//$container->setArray('globalConfig', $globalConfig);

//$app = $container->get(Application::class);
$app = new Application($globalConfig);

//incoming requests
$app->router->routes['GET']['/admin'] = [SiteController::class, 'home'];
$app->router->routes['GET']['/admin/about'] = [SiteController::class, 'about'];
$app->router->routes['GET']['/admin/sessions'] = [SiteController::class, 'allSessions'];

$app->router->routes['GET']['/admin/orders'] = [OrdersController::class, 'getOrders'];
$app->router->routes['GET']["/admin/orders/order"] = [OrdersController::class, 'getOrder'];
$app->router->routes['POST']['/admin/getStatus'] = [OrdersController::class, 'filterByStatus'];

$app->router->routes['GET']['/admin/products'] = [ProductsController::class, 'getProducts'];
$app->router->routes['GET']['/admin/products/getProductNames'] = [ProductsController::class, 'fetchAllNames'];
$app->router->routes['GET']["/admin/products/product"] = [ProductsController::class, 'getProduct'];
$app->router->routes['GET']["/admin/products/addProduct"] = [ProductsController::class, 'createProductForm'];
$app->router->routes['GET']["/admin/products/editProduct"] = [ProductsController::class, 'editProductForm'];
$app->router->routes['POST']["/admin/products/editProduct"] = [ProductsController::class, 'editProduct'];
$app->router->routes['POST']["/admin/products/addProduct"] = [ProductsController::class, 'createProduct'];
$app->router->routes['GET']['/admin/pusher'] = [ProductsController::class, 'serverPush'];

$app->router->routes['GET']["/admin/productLines/addProductLine"] = [ProductLinesController::class, 'productLineForm'];
$app->router->routes['POST']["/admin/productLines/createProductLine"] = [ProductLinesController::class, 'createProductLine'];
$app->router->routes['GET']['/admin/productLines'] = [ProductLinesController::class, 'getProductlines'];

$app->router->routes['GET']['/admin/customers'] = [CustomersController::class, 'getCustomers'];
$app->router->routes['GET']['/admin/customers/customer'] = [CustomersController::class, 'getCustomer'];

$app->router->routes['GET']['/admin/login'] = [LoginController::class, 'login'];
$app->router->routes['POST']['/admin/login'] = [LoginController::class, 'handleLoginform'];
$app->router->routes['GET']['/admin/logout'] = [LoginController::class, 'handleLogout'];

$app->router->routes['GET']['/admin/profile'] = [ProfileController::class, 'showProfile'];
$app->router->routes['POST']['/admin/editPass'] = [ProfileController::class, 'editPassword'];
$app->router->routes['GET']['/admin/addUser'] = [ProfileController::class, 'registerPage'];
$app->router->routes['POST']['/admin/addUser'] = [ProfileController::class, 'createUser'];

$app->router->routes['GET']['/admin/secret1'] = [SecretController::class, 'accessSecret1'];

$app->router->routes['GET']['/site/products'] = [SiteProductsController::class, 'getProducts'];

echo "This code runs once when the server starts" . "\n";

/*
$server->on("WorkerStart", function(Swoole\Server $server,int $workerId) {

    global $argv;

    if($workerId >= $server->setting['worker_num'])
    {
      swoole_set_process_name("php {$argv[0]} task worker");
      echo "php {$argv[0]} task worker with ID:$workerId" . PHP_EOL;
    }
    
    else
    {
      swoole_set_process_name("php {$argv[0]} event worker");
      echo "php {$argv[0]} task worker with ID:$workerId" . PHP_EOL;
    }
});
*/

$server->on('start', function (Swoole\Server $server)
{
  //only limited logic should be executed here, such as logging, recording and modifying the name of process, getting the process ID etc
    
    echo "Server started at http://127.0.0.1:8000\n";
});


$server->on('request', function(Swoole\Http\Request $request, Swoole\Http\Response $response) use ($app)
{

  $app->run($request, $response);

});

/*
$server->on("Shutdown", function(Swoole\Server $server, int $workerId) {

});


$server->on("WorkerStop", function(Swoole\Server $server, int $workerId) {

  echo "Ending the Worker: $workerId" . PHP_EOL;

});
*/
$server->start();

?>

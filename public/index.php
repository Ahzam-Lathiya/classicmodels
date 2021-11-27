<?php

require_once "../vendor/autoload.php";


use app\controllers\SiteController;
use app\controllers\LoginController;
use app\controllers\OrdersController;
use app\controllers\ProductLinesController;
use app\controllers\ProductsController;
use app\controllers\CustomersController;
use app\controllers\ProfileController;
use app\controllers\SecretController;
use app\models\Employees;

use app\core\Application;
//use app\core\ContextManager;
use Swoole\Coroutine;
use Swoole\HTTP\Server as HttpServer;

Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);

//load configuration from .env file
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
           'userClass' => Employees::class,
           'DB_CONFIG' => 
            [
              'dsn' => $_ENV['DB_DSN'],
              'user' => $_ENV['DB_USER'],
              'pass' => $_ENV['DB_PASS']
            ]
          ];

$server = new HttpServer('127.0.0.1', 8000, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);


$server->set([
    'worker_num' => 4,      // The number of worker processes to start
    //'task_worker_num' => 4,  // The amount of task workers to start
    //'backlog' => 128,       // TCP backlog connection number
    'enable_coroutine' => true
]);
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

$server->on('start', function (Swoole\Server $server) {
  //only limited logic should be executed here, such as logging, recording and modifying the name of process, getting the process ID etc
    echo "Server started at http://127.0.0.1:8000\n";
});


$server->on('request', function(Swoole\Http\Request $request, Swoole\Http\Response $response) use ($config)
{


//$config = ['userClass' => Employees::class];

$app = new Application($config, $request, $response);

//incoming requests
$app->router->routes['GET']['/'] = [SiteController::class, 'home'];
$app->router->routes['GET']['/about'] = [SiteController::class, 'about'];

$app->router->routes['GET']['/orders'] = [OrdersController::class, 'getOrders'];
$app->router->routes['GET']["/orders/order"] = [OrdersController::class, 'getOrder'];
$app->router->routes['POST']['/getStatus'] = [OrdersController::class, 'filterByStatus'];

$app->router->routes['GET']['/products'] = [ProductsController::class, 'getProducts'];
$app->router->routes['GET']['/products/getProductNames'] = [ProductsController::class, 'fetchAllNames'];
$app->router->routes['GET']["/products/product"] = [ProductsController::class, 'getProduct'];
$app->router->routes['GET']["/products/addProduct"] = [ProductsController::class, 'createProductForm'];
$app->router->routes['GET']["/products/editProduct"] = [ProductsController::class, 'editProductForm'];
$app->router->routes['POST']["/products/editProduct"] = [ProductsController::class, 'editProduct'];
$app->router->routes['POST']["/products/addProduct"] = [ProductsController::class, 'createProduct'];
$app->router->routes['GET']['/pusher'] = [ProductsController::class, 'serverPush'];

$app->router->routes['GET']["/productLines/addProductLine"] = [ProductLinesController::class, 'productLineForm'];
$app->router->routes['POST']["/productLines/createProductLine"] = [ProductLinesController::class, 'createProductLine'];
$app->router->routes['GET']['/productLines'] = [ProductLinesController::class, 'getProductlines'];

$app->router->routes['GET']['/customers'] = [CustomersController::class, 'getCustomers'];
$app->router->routes['GET']['/customers/customer'] = [CustomersController::class, 'getCustomer'];

$app->router->routes['GET']['/login'] = [LoginController::class, 'login'];
$app->router->routes['POST']['/login'] = [LoginController::class, 'handleLoginform'];
$app->router->routes['GET']['/logout'] = [LoginController::class, 'handleLogout'];

$app->router->routes['GET']['/profile'] = [ProfileController::class, 'showProfile'];
$app->router->routes['POST']['/editPass'] = [ProfileController::class, 'editPassword'];
$app->router->routes['GET']['/addUser'] = [ProfileController::class, 'registerPage'];
$app->router->routes['POST']['/addUser'] = [ProfileController::class, 'createUser'];

$app->router->routes['GET']['/secret1'] = [SecretController::class, 'accessSecret1'];

  $app->run();

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

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

$config = ['userClass' => Employees::class];

$app = new Application($config);

//incoming requests
$app->router->routes['GET']['/'] = array(SiteController::class, 'home');
$app->router->routes['GET']['/about'] = array(SiteController::class, 'about');

$app->router->routes['GET']['/orders'] = array(OrdersController::class, 'getOrders');
$app->router->routes['GET']["/orders/order"] = array(OrdersController::class, 'getOrder');
$app->router->routes['POST']['/getStatus'] = array(OrdersController::class, 'filterByStatus');

$app->router->routes['GET']['/products'] = array(ProductsController::class, 'getProducts');
$app->router->routes['GET']['/products/push'] = array(ProductsController::class, 'serverPush');
$app->router->routes['GET']['/products/getProductNames'] = array(ProductsController::class, 'fetchAllNames');
$app->router->routes['GET']["/products/product"] = array(ProductsController::class, 'getProduct');
$app->router->routes['GET']["/products/addProduct"] = array(ProductsController::class, 'createProductForm');
$app->router->routes['GET']["/products/editProduct"] = array(ProductsController::class, 'editProductForm');
$app->router->routes['POST']["/products/editProduct"] = array(ProductsController::class, 'editProduct');
$app->router->routes['POST']["/products/addProduct"] = array(ProductsController::class, 'createProduct');

$app->router->routes['GET']["/productLines/addProductLine"] = array(ProductLinesController::class, 'productLineForm');
$app->router->routes['POST']["/productLines/addProductLine"] = array(ProductLinesController::class, 'createProductLine');
$app->router->routes['GET']['/productLines'] = array(ProductLinesController::class, 'getProductlines');

$app->router->routes['GET']['/customers'] = array(CustomersController::class, 'getCustomers');
$app->router->routes['GET']['/customers/customer'] = array(CustomersController::class, 'getCustomer');

$app->router->routes['GET']['/login'] = array(LoginController::class, 'login');
$app->router->routes['POST']['/login'] = array(LoginController::class, 'handleLoginform');
$app->router->routes['GET']['/logout'] = array(LoginController::class, 'handleLogout');

$app->router->routes['GET']['/profile'] = array(ProfileController::class, 'showProfile');
$app->router->routes['POST']['/editPass'] = array(ProfileController::class, 'editPassword');
$app->router->routes['GET']['/addUser'] = array(ProfileController::class, 'registerPage');
$app->router->routes['POST']['/addUser'] = array(ProfileController::class, 'createUser');

$app->router->routes['GET']['/secret1'] = array(SecretController::class, 'accessSecret1');


$app->run();

?>

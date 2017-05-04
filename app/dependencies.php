<?php
use Cart\Basket\Basket;
use Cart\Models\Address;
use Cart\Models\Customer;
use Cart\Models\Order;
use Cart\Models\Payment;
use Cart\Models\Product;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Cart\Support\Storage\SessionStorage;
use Cart\Validation\Validator;
use Cart\Controllers\HomeController;
use Cart\Controllers\ProductController;
use Cart\Controllers\CartController;
use Cart\Controllers\OrderController;
use Cart\Controllers\BraintreeController;

// -----------------------------------------------------------------------------
// Init
// -----------------------------------------------------------------------------
// DIC configuration

$container = $app->getContainer();
$settings = $container->get('settings');

// Database
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection($settings['db']);

$capsule->setAsGlobal();
$capsule->bootEloquent();

//Braintree config
Braintree_Configuration::environment($settings['braintree']['environment']);
Braintree_Configuration::merchantId($settings['braintree']['merchantId']);
Braintree_Configuration::publicKey($settings['braintree']['publicKey']);
Braintree_Configuration::privateKey($settings['braintree']['privateKey']);


// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) use ($settings) {
    $view = new Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

	$view->getEnvironment()->addGlobal('basket', $c->get(Basket::class));

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

$container[Validator::class] = function ($c) {
    return new Validator;
};

$container[SessionStorage::class] = function ($c) {
    return new SessionStorage('cart');
};

$container[Address::class] = function ($c) {
    return new Address;
};

$container[Customer::class] = function ($c) {
    return new Customer;
};

$container[Order::class] = function ($c) {
    return new Order;
};

$container[Payment::class] = function ($c) {
    return new Payment;
};

$container[Product::class] = function ($c) {
    return new Product;
};


$container[Basket::class] = function ($c) {
	return new Basket(
		$c->get(SessionStorage::class),
		$c->get(Product::class)
	);
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) use ($settings) {
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Controllers factories
// -----------------------------------------------------------------------------

$container['HomeController'] = function($c) {
    $view = $c->get('view');
    $product = $c->get(Product::class);
    return new HomeController($view, $product);
};

$container['ProductController'] = function($c) {
    $view = $c->get('view');
    $router = $c->get('router');
    $product = $c->get(Product::class);
    return new ProductController($view, $router, $product);
};

$container['CartController'] = function($c) {
    $view = $c->get('view');
    $router = $c->get('router');
    $basket = $c->get(Basket::class);
    $product = $c->get(Product::class);
    return new CartController($view, $router, $basket, $product);
};

$container['OrderController'] = function($c) {
    $view = $c->get('view');
    $router = $c->get('router');
    $order = $c->get(Order::class);
    $basket = $c->get(Basket::class);
    $validator= $c->get(Validator::class);
    return new OrderController($view, $router, $order, $basket, $validator);
};

$container['BraintreeController'] = function($c) {
    return new BraintreeController();
};

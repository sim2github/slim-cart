<?php

$app->get('/', HomeController::class . ':index')->setName('home');

$app->get('/products/{slug}', ProductController::class . ':get')->setName('product.get');

$app->get('/cart', CartController::class . ':index')->setName('cart.index');
$app->get('/cart/add/{slug}/{quantity}', CartController::class .':add')->setName('cart.add');
$app->post('/cart/update/{slug}', CartController::class . ':update')->setName('cart.update');

$app->get('/order', OrderController::class . ':index')->setName('order.index');
$app->get('/order/{hash}', OrderController::class . ':show')->setName('order.show');
$app->post('/order', OrderController::class . ':create')->setName('order.create');

$app->get('/braintree/token', BraintreeController::class . ':token')->setName('braintree.token');
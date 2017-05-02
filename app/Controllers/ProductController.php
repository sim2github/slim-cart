<?php

namespace Cart\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use Cart\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductController{

	protected $view;
	protected $router;
	protected $product;

	public function __construct(Twig $view, Router $router, Product $product){
		$this->view = $view;
		$this->router = $router;
		$this->product = $product;
	}

	public function get(Request $request, Response $response, array $args){
		
		$product = $this->product->where('slug', $args['slug'])->first();
		

		if (!$product) {
			return $response->withRedirect($router->pathFor('home'));
		}

		return $this->view->render($response, 'products/product.twig', [
			'product' => $product
		]);
	}

}

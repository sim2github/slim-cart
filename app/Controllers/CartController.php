<?php

namespace Cart\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use Cart\Basket\Basket;
use Cart\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cart\Basket\Exceptions\QuantityExceededException;

class CartController{
	protected $view;
	protected $router;
	protected $basket;
	protected $product;
    private $settings;

    /**
     * CartController constructor.
     * @param Twig $view
     * @param Router $router
     * @param Basket $basket
     * @param Product $product
     * @param array $settings
     */
    public function __construct(
	    Twig $view,
        Router $router,
        Basket $basket,
        Product $product,
        array $settings = []
    ){
		$this->view = $view;
		$this->router = $router;
		$this->basket = $basket;
		$this->product = $product;
        $this->settings = $settings;
    }

	public function index(Request $request, Response $response, array $args){
		$this->basket->refresh();
		return $this->view->render(
		    $response,
            'cart/index.twig',
            [
                'settings' => $this->settings,

            ]
        );
	}

	public function add(Request $request, Response $response, array $args){
		$slug = filter_var($args['slug'], FILTER_SANITIZE_STRING);
		$quantity = filter_var($args['quantity'], FILTER_SANITIZE_NUMBER_INT);
		
		$product = $this->product->where('slug', $slug)->first();

		if (!$product) {
			return $response->withRedirect($this->router->pathFor('home'));
		}

		try {
			$this->basket->add($product, $quantity);
		} catch (QuantityExceededException $e) {
			
		}

		return $response->withRedirect($this->router->pathFor('cart.index'));
	}

	public function update(Request $request, Response $response, array $args){
		
		$slug = filter_var($args['slug'], FILTER_SANITIZE_STRING);

		$product = $this->product->where('slug', $slug)->first();

		if (!$product) {
			return $response->withRedirect($this->router->pathFor('home'));
		}

		try {
			$this->basket->update($product, $request->getParam('quantity'));
		} catch (QuantityExceededException $e) {

		}

		return $response->withRedirect($this->router->pathFor('cart.index'));
	}

}

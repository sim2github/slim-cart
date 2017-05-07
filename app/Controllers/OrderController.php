<?php

namespace Cart\Controllers;

use Slim\Router;
use Slim\Views\Twig;
use Cart\Basket\Basket;
use Cart\Models\Address;
use Cart\Models\Customer;
use Cart\Models\Order;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cart\Validation\Contracts\ValidatorInterface;
use Cart\Validation\Forms\OrderForm;
use Braintree_Transaction;

class OrderController{

	protected $view;
	protected $router;
	protected $basket;
	protected $order;
	protected $validator;
    protected $settings;

    /**
     * OrderController constructor.
     * @param Twig $view
     * @param Router $router
     * @param Order $order
     * @param Basket $basket
     * @param ValidatorInterface $validator
     * @param array $settings
     */
    public function __construct(Twig $view, Router $router, Order $order, Basket $basket, ValidatorInterface $validator, array $settings){
		$this->view = $view;
		$this->router = $router;
		$this->basket = $basket;
		$this->order = $order;
		$this->validator = $validator;
        $this->settings = $settings;
    }

	public function index(Request $request, Response $response, array $args){
		$this->basket->refresh();

		if(!$this->basket->subTotal()){
			return $response->withRedirect($this->router->pathFor('cart.index'));
		}
		
		return $this->view->render(
		    $response, 'order/index.twig',
            [
                'settings' => $this->settings
            ]
        );
	}

	public function show(Request $request, Response $response, array $args){
		$hash = filter_var($args['hash'], FILTER_SANITIZE_STRING);
		
		$order = $this->order->with(['address', 'products'])->where('hash', $hash)->first();

		if (!$order) {
			return $response->withRedirect($this->router->pathFor('home'));
		}

		return $this->view->render($response, 'order/show.twig', [
			'order' => $order,
            'settings' => $this->settings
		]);
	}

	public function create(Request $request, Response $response, array $args){
		$this->basket->refresh();

		if(!$this->basket->subTotal()){
			return $response->withRedirect($this->router->pathFor('cart.index'));
		}

		if(!$request->getParam('payment_method_nonce')){
			return $response->withRedirect($this->router->pathFor('order.index'));
		}

		$validation = $this->validator->validate($request, OrderForm::rules());

		if($validation->fails()){
			return $response->withRedirect($this->router->pathFor('order.index'));
		}

		$hash = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

		$customer = Customer::firstOrCreate([
			'email' => $request->getParam('email'),
			'name' => $request->getParam('name'),
		]);

		$address = Address::firstOrCreate([
			'address1' => $request->getParam('address1'),
			'address2' => $request->getParam('address2'),
			'city' => $request->getParam('city'),
			'postal_code' => $request->getParam('postal_code'),
		]);

		$order = $customer->orders()->create([
			'hash' => $hash,
			'paid' => false,
			'total' => ($this->basket->subTotal() + 5),
			'address_id' => $address->id,
		]);

		$orderProducts = $this->basket->all();

		$order->products()->saveMany(
			$orderProducts,
			$this->getQuantities($orderProducts)
		);

		$result = Braintree_Transaction::sale([
			'amount' => $this->basket->subTotal() + 5,
			'paymentMethodNonce' => $request->getParam('payment_method_nonce'),
			'options' => [
				'submitForSettlement' => True
			]
		]);

		$event =  new \Cart\Events\OrderWasCreated($order, $this->basket);

		if(!$result->success){
			$event->attach(new \Cart\Handlers\RecordFailedPayment);
			$event->dispatch();

			return $response->withRedirect($this->router->pathFor('order.index'));
		}

		$event->attach([
			new \Cart\Handlers\MarkOrderPaid,
			new \Cart\Handlers\RecordSuccessfulPayment($result->transaction->id),
			new \Cart\Handlers\UpdateStock,
			new \Cart\Handlers\EmptyBasket,
		]);

		$event->dispatch();

		return $response->withRedirect($this->router->pathFor('order.show', [
			'hash' => $hash,
		]));
	}

	protected function getQuantities($items){
		$quantities = [];

		foreach($items as $item){
			$quantities[] = ['quantity' => $item->quantity];
		}

		return $quantities;
	}

}

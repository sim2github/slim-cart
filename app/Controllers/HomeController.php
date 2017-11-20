<?php

namespace Cart\Controllers;

use Slim\Views\Twig;
use Cart\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    protected $view;
    protected $product;
    
    public function __construct(Twig $view, Product $product)
    {
        $this->view = $view;
        $this->product = $product;
    }

    public function index(Request $request, Response $response)
    {
        $products = $this->product->all();

        return $this->view->render($response, 'home.twig', [
            'products' => $products
        ]);
    }
}

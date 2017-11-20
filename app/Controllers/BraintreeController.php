<?php

namespace Cart\Controllers;

use Braintree_ClientToken;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BraintreeController
{
    public function token(Request $request, Response $response, array $args)
    {
        return $response->withJson([
            'token' => Braintree_ClientToken::generate(),
        ]);
    }
}

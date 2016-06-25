<?php
namespace Demo7;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller
{
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write('Hello DPC!');

        return $response;
    }
}

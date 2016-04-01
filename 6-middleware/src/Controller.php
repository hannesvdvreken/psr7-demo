<?php
namespace Demo6;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write("<h1>".(string) $request->getUri()."</h1>");

        return $response->withHeader('Content-Type', 'text/html');
    }
}

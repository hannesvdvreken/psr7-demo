<?php
namespace Demo6;

use Zend\Diactoros\Response;

class Controller
{
    public function index()
    {
        $response = new Response();

        $response->getBody()->write("<h1>You're in!</h1>");

        return $response->withHeader('Content-Type', 'text/html');
    }
}

<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

return function (ServerRequestInterface $request, ResponseInterface $response) use ($container) {
    /* @var League\Route\RouteCollection $router */
    $router = $container->get(League\Route\RouteCollection::class);

    return $router->dispatch($request, $response);
};

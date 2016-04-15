<?php

require '../vendor/autoload.php';

// Get ServerRequestInterface object.
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

// Boot application.
$controller = new Demo7\Controller();

$middleware1 = new \Psr7Middlewares\Middleware\BasicAuthentication(['phpuk' => 'newrelic']);
$router = [$controller, 'index'];

$builder = new \Relay\RelayBuilder();
$app = $builder->newInstance([
    $middleware1,
    // ... more middlewares
    $router,
]);

// Let application do the magic.
$response = $app($request, new \Zend\Diactoros\Response());

// Output Response.
(new Zend\Diactoros\Response\SapiEmitter())->emit($response);

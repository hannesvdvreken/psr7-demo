<?php

require '../vendor/autoload.php';

// Make ServerRequest object.
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

$handler = GuzzleHttp\HandlerStack::create();
$handler->push(Demo1\GithubAuth::create());

// Boot application.
$controller = new Demo2\Controller(new GuzzleHttp\Client(compact('handler')));

// Let application do the magic.
$response = $controller->index($request);

// Output Response.
(new Zend\Diactoros\Response\SapiEmitter())->emit($response);

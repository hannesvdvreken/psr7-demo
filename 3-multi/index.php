<?php

require '../vendor/autoload.php';

$handler = GuzzleHttp\HandlerStack::create();
$handler->push(Demo1\GithubAuth::create());

// Boot application.
$controller = new Demo3\Controller(new GuzzleHttp\Client(compact('handler')));

// Let application do the magic.
$response = $controller->index();

// Output Response.
(new Zend\Diactoros\Response\SapiEmitter())->emit($response);

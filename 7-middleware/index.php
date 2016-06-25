<?php

use mindplay\middleman\Dispatcher;
use mindplay\middleman\InteropResolver;

require '../vendor/autoload.php';

// Get ServerRequestInterface object.
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

// Boot application.
/* @var Interop\Container\ContainerInterface $container */
$container = require 'container.php';
$router = require 'router.php';

$middlewares = [
    Psr7Middlewares\Middleware\BasicAuthentication::class,
    // ... more middlewares
    $router,
];

$dispatcher = new Dispatcher($middlewares, new InteropResolver($container));

// Let application do the magic.
$response = $dispatcher->dispatch($request, new Zend\Diactoros\Response());

// Output Response.
(new Zend\Diactoros\Response\SapiEmitter())->emit($response);

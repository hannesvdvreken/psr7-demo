<?php

require '../vendor/autoload.php';

// Get URI object.
$uri = Zend\Diactoros\ServerRequestFactory::fromGlobals()->getUri();

// Setup DebugBar
$debugbar = new DebugBar\StandardDebugBar();
$debugbar->getJavascriptRenderer()->setBaseUrl($uri->withPort('8000')->withPath('/'));

// Setting up the stack off middlewares.
$handler = GuzzleHttp\HandlerStack::create();

$middleware = new GuzzleHttp\Profiling\Middleware(
    new GuzzleHttp\Profiling\Debugbar\Profiler(
        $debugbar->getCollector('time')
    )
);

$handler->unshift($middleware);
$handler->unshift(Demo1\GithubAuth::create());

// Boot application.
$controller = new Demo4\Controller(
    new GuzzleHttp\Client(compact('handler')),
    $debugbar->getJavascriptRenderer()
);

// Let application do the magic.
$response = $controller->index();

// Output Response.
(new Zend\Diactoros\Response\SapiEmitter())->emit($response);

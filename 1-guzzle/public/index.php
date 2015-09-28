<?php

require '../vendor/autoload.php';

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

// Modify the request object
$uri = $request->getUri();
$request = $request->withUri(
    $uri->withHost('api.github.com')
        ->withScheme('https')
        ->withPort(443)
);

//$request = $request->withHeader('Authorization', 'token '.getenv('GITHUB_TOKEN'));

// Execute the request
$response = (new GuzzleHttp\Client())->send($request);

// Remove these response headers, because guzzle will automatically decode the content.
$response = $response->withoutHeader('Transfer-Encoding')->withoutHeader('Content-Encoding');

(new Zend\Diactoros\Response\SapiEmitter())->emit($response);
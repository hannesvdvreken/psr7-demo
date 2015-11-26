<?php

use Psr\Http\Message\ResponseInterface;

require '../vendor/autoload.php';

// Create the request.
$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();

// Modify the request object.
$uri = $request->getUri();
$request = $request->withUri(
    $uri->withHost('api.github.com')
        ->withScheme('https')
        ->withPort(443)
);

// Guzzle client.
$client = new GuzzleHttp\Client();

// Execute the request.
$promise = $client->sendAsync($request)->then(function (ResponseInterface $response) {
    // Remove these response headers, because guzzle will automatically decode the content.
    $response = $response->withoutHeader('Transfer-Encoding')->withoutHeader('Content-Encoding');

    (new \Zend\Diactoros\Response\SapiEmitter())->emit($response);
});

$promise->wait();

<?php

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

require '../vendor/autoload.php';

// Symfony request object
$request = Request::createFromGlobals();
$factory = new Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory();

// PSR-7 request object
$request = $factory->createRequest($request);

// Modify the request object
$uri = $request->getUri();
$request = $request->withUri(
    $uri->withHost('api.github.com')
        ->withScheme('https')
        ->withPort(443)
);

// Guzzle client
$client = new GuzzleHttp\Client();

// Execute the request
$promise = $client->sendAsync($request)->then(function (ResponseInterface $response) {
    // Remove these response headers, because guzzle will automatically decode the content.
    $response = $response->withoutHeader('Transfer-Encoding')->withoutHeader('Content-Encoding');

    // Symfony response object to pass on the headers and content to the user agent.
    $factory = new Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory();
    $factory->createResponse($response)->send();
});

$promise->wait();

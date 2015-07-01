<?php

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

require '../vendor/autoload.php';

// Symfony request object
$request = SymfonyRequest::createFromGlobals();
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

$request = $request->withHeader('Authorization', 'token '.getenv('GITHUB_TOKEN'));

// Execute the request
$response = (new GuzzleHttp\Client())->send($request);

// Remove these response headers, because guzzle will automatically decode the content.
$response = $response->withoutHeader('Transfer-Encoding')->withoutHeader('Content-Encoding');

// Symfony response object to pass on the headers and content to the user agent.
$factory = new Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory();
$factory->createResponse($response)->send();

<?php

use Symfony\Component\HttpFoundation\Request;

require '../vendor/autoload.php';

// Symfony request object
$request = Request::createFromGlobals();
$factory = new Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory();

// PSR-7 request object
$request = $factory->createRequest($request);

// Get the request path
$path = $path = $request->getUri()->getPath();

// Remove leading slash
$username = str_replace('/users/', '', $path);

// Setup HTTP client
$guzzle = new GuzzleHttp\Client();

// Adapter
$adapter = new Http\Adapter\Guzzle6HttpAdapter($guzzle);

// Client
$client = new Http\Adapter\Client($adapter);

$github = new Github\Client($client);

$user = $github->user($username);

echo $user->getUsername();
echo '<br>';
echo "<img src={$user->getAvatar()}>";
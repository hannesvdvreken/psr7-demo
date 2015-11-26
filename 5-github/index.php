<?php

require '../vendor/autoload.php';

// PSR request object
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals();

// Get the request path
$path = $path = $request->getUri()->getPath();

// Remove leading slash
$username = str_replace('/users/', '', $path);

// Wrapping a Guzzle client in an adapter
$github = new Github\Client(
    new Http\Adapter\Guzzle6HttpAdapter(
        new GuzzleHttp\Client()
    )
);

$user = $github->user($username);

echo $user->getUsername();
echo '<br>';
echo "<img src={$user->getAvatar()}>";
<?php

use Psr\Http\Message\ResponseInterface;

require '../vendor/autoload.php';

// List of profiles to retrieve
$usernames = ['toonketels', 'dirkbonhomme', 'hannesvdvreken', 'brammm', 'andreascreten'];
$promises = [];

// Guzzle client
$client = new GuzzleHttp\Client();

// Send all requests.
foreach ($usernames as $username) {
    $url = 'https://api.github.com/users/'.$username;
    $request = (new \GuzzleHttp\Psr7\Request('GET', $url))
        ->withHeader('Authorization', 'token '.getenv('GITHUB_TOKEN'));
    $promises[] = $client->sendAsync($request);
}

// Declare variable.
$profiles = [];

// Wait till all the requests are finished.
$promise = GuzzleHttp\Promise\all($promises)->then(function (array $responses) use (&$profiles) {
    $profiles = array_map(function (ResponseInterface $response) {
        return json_decode($response->getBody(), true);
    }, $responses);
})->wait();

$response = new Zend\Diactoros\Response();

$response->getBody()->write(json_encode($profiles));

$response = $response->withHeader('Content-type', 'application/json');

(new \Zend\Diactoros\Response\SapiEmitter())->emit($response);

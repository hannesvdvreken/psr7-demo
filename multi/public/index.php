<?php

use Psr\Http\Message\ResponseInterface;

require '../vendor/autoload.php';

// List of profiles to retrieve
$usernames = ['tijsverkoyen', 'woutersioen', 'dhaemer', 'jenssegers', 'hannesvdvreken'];
$promises = [];

// Guzzle client
$client = new GuzzleHttp\Client();

// Send all requests.
foreach ($usernames as $username) {
    $url = 'https://api.github.com/users/'.$username;
    $headers = ['Authorization' => 'token '.getenv('GITHUB_TOKEN')];
    $promises[] = $client->requestAsync('GET', $url, ['headers' => $headers]);
}


// Wait till all the requests are finished.
$promise = GuzzleHttp\Promise\all($promises)->then(function (array $responses) {
    $profiles = array_map(function (ResponseInterface $response) {
        return json_decode($response->getBody(), true);
    }, $responses);

    var_dump($profiles);
})->wait();

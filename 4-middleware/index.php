<?php

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

require '../vendor/autoload.php';

// Setup DebugBar
$debugbar = new DebugBar\StandardDebugBar();

/** @var \DebugBar\DataCollector\TimeDataCollector $timeline */
$timeline = $debugbar->getCollector('time');

// Setting up the stack off middlewares
$stack = GuzzleHttp\HandlerStack::create();

$profiler = new GuzzleHttp\Profiling\Debugbar\Profiler($timeline);
$middleware = new GuzzleHttp\Profiling\Middleware($profiler);

$stack->unshift($middleware);

// List of profiles to retrieve
$usernames = ['dieterve', 'anahkiasen', 'andreascreten', 'hannesvdvreken', 'tonysm'];
$promises = [];

// Guzzle client
$client = new GuzzleHttp\Client(['handler' => $stack]);

// Send all requests.
foreach ($usernames as $username) {
    $url = 'https://api.github.com/users/'.$username;
    $request = new \GuzzleHttp\Psr7\Request('GET', $url);
    $request = $request->withHeader('Authorization', 'token '.getenv('GITHUB_TOKEN'));
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

// Setup base url
$debugbar->getJavascriptRenderer()->setBaseUrl('http://192.168.10.10:8001/');

$encodedProfiles = nl2br(json_encode($profiles, JSON_PRETTY_PRINT));

// Output HTML
echo
"<html>
<head>
    {$debugbar->getJavascriptRenderer()->renderHead()}
</head>
<body>
    {$encodedProfiles}
    {$debugbar->getJavascriptRenderer()->render()}
</body>
</html>";
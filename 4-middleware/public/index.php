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

$stack->unshift(function (callable $handler) use ($timeline) {
    return function (RequestInterface $request, array $options) use ($handler, $timeline) {
        // Before
        $start = microtime(true);

        return $handler($request, $options)
            ->then(function (ResponseInterface $response) use ($request, $timeline, $start) {
                // After
                $label = $request->getMethod().' '.$request->getUri();
                $timeline->addMeasure($label, $start, microtime(true));

                return $response;
            });
    };
});

// List of profiles to retrieve
$usernames = ['dieterve', 'anahkiasen', 'andreascreten', 'hannesvdvreken', 'tonysm'];
$promises = [];

// Guzzle client
$client = new GuzzleHttp\Client(['handler' => $stack]);

// Send all requests.
foreach ($usernames as $username) {
    $url = 'https://api.github.com/users/'.$username;
    $promises[] = $client->requestAsync('GET', $url);
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
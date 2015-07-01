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
$usernames = ['tijsverkoyen', 'woutersioen', 'dhaemer', 'jenssegers', 'hannesvdvreken'];
$promises = [];

// Guzzle client
$client = new GuzzleHttp\Client(['handler' => $stack]);

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

// Setup base url
$debugbar->getJavascriptRenderer()->setBaseUrl('http://192.168.10.10:8001/');

// Output HTML
echo
"<html>
<head>
    {$debugbar->getJavascriptRenderer()->renderHead()}
</head>
<body>
    {$debugbar->getJavascriptRenderer()->render()}
</body>
</html>";
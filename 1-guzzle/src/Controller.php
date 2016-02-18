<?php
namespace Demo1;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function index(ServerRequestInterface $request)
    {
        // Modify the request object's target host.
        $uri = $request->getUri();
        $uri = $uri->withHost('api.github.com')
            ->withScheme('https')->withPort(443);
        $request = $request->withUri($uri);

        // Do the request.
        $response = $this->client->send($request);

        // Remove response headers, Guzzle will auto unzip/deflate.
        return $response
            ->withoutHeader('Transfer-Encoding') // chunked
            ->withoutHeader('Content-Encoding'); // deflate
    }
}

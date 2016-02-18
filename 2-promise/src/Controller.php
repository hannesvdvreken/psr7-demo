<?php
namespace Demo2;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller
{
    private $client;
    private $response;

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

        // Execute the request.
        $promise = $this->client->sendAsync($request);

        $promise->then(function (ResponseInterface $response) {
            // Remove response headers, Guzzle will auto unzip/deflate.
            $this->response = $response
                ->withoutHeader('Transfer-Encoding') // chunked
                ->withoutHeader('Content-Encoding'); // deflate
        });

        // Very important.
        $promise->wait();

        return $this->response;
    }
}

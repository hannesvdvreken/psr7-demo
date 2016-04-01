<?php
namespace Demo3;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

class Controller
{
    private $client;
    private $usernames = ['andreascreten', 'derickr', 'geeh'];
    private $profiles = [];

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return static
     */
    public function index()
    {
        $promises = array_map(function ($username) {
            $url = 'https://api.github.com/users/'.$username;

            return $this->client->sendAsync(new Request('GET', $url));
            // return $this->client->requestAsync('GET', $url);
        }, $this->usernames);

        // Wait till all the requests are finished.
        \GuzzleHttp\Promise\all($promises)->then(function (array $responses) {
            $this->profiles = array_map(function ($response) {
                return json_decode($response->getBody(), true);
            }, $responses);
        })->wait();

        // Return JSON response
        $response = new \Zend\Diactoros\Response();

        // StreamInterface objects are not immutable!
        $response->getBody()->write(json_encode($this->profiles));

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}

<?php
namespace Github;

use Github\Entities\User;
use Http\Client\HttpClient;

class Client implements ClientInterface
{
    /**
     * @var string
     */
    private $base = 'https://api.github.com';

    /**
     * @var \Http\Client\HttpClient
     */
    private $client;

    /**
     * @param \Http\Client\HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $username
     *
     * @return \Github\Entities\User
     */
    public function user($username)
    {
        $url = $this->base.'/users/'.$username;

        $headers = ['Authorization' => 'token '.getenv('GITHUB_TOKEN')];

        $response = $this->client->get($url, $headers);

        $body = (string) $response->getBody();

        return User::create(json_decode($body, true));
    }
}

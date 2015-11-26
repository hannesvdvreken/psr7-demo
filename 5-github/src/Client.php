<?php
namespace Github;

use Github\Entities\User;
use GuzzleHttp\Psr7\Request;
use Http\Adapter\HttpAdapter;

class Client implements ClientInterface
{
    /**
     * @var string
     */
    private $base = 'https://api.github.com';

    /**
     * @var HttpAdapter
     */
    private $adapter;

    /**
     * Client constructor.
     *
     * @param HttpAdapter $adapter
     */
    public function __construct(HttpAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $username
     *
     * @return \Github\Entities\User
     */
    public function user($username)
    {
        $url = $this->base.'/users/'.$username;

        $response = $this->adapter->sendRequest(new Request('GET', $url));

        $body = (string) $response->getBody();

        return User::create(json_decode($body, true));
    }
}

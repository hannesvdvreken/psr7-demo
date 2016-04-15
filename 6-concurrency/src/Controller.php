<?php
namespace Demo6;

use DebugBar\JavascriptRenderer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\EachPromise;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class Controller
{
    private $client;
    private $jsRenderer;
    private $usernames = ['andreascreten', 'xavierbarbosa', 'sambego', '4xel', 'sdemoffarts', 'Anne-Julie', 'sjimi', 'tonysm', 'hannesvdvreken', 'anahkiasen', 'bramdevries', 'mogetutu', 'dieterve', 'eliedevlieger', 'jdrieghe', 'jonasvanschoote', 'kaiobrito', 'maartenscholz', 'mangamaui', 'miggynascimento', 'yannickdepauw', 'vanbrabantf'];

    public function __construct(ClientInterface $client, JavascriptRenderer $jsRenderer)
    {
        $this->client = $client;
        $this->jsRenderer = $jsRenderer;
    }

    public function index()
    {
        $promises = $this->getProfiles();
        $profiles = [];

        // Wait till all the requests are finished.
        (new EachPromise($promises, [
            'concurrency' => 4,
            'fulfilled' => function ($profile) use (&$profiles) {
                $profiles[] = $profile;
            },
        ]))->promise()->wait();

        // Return JSON response
        $response = new Response();

        // StreamInterface objects are not immutable!
        $response->getBody()->write($this->html($profiles));

        return $response
            ->withHeader('Content-type', 'text/html');
    }

    private function getProfiles()
    {
        foreach ($this->usernames as $username) {
            yield $this->client->requestAsync('GET', 'https://api.github.com/users/'.$username)
                ->then(function (ResponseInterface $response) {
                    return json_decode($response->getBody(), true);
                });
        }
    }

    private function html(array $profiles)
    {
        $head = "<html><head>{$this->jsRenderer->renderHead()}</head>";
        $body = join('', array_map(function (array $profile) {
            return "<img src='{$profile['avatar_url']}' width='100px'><br>";
        }, $profiles));
        $footer = "</html>";

        return $head."<body>".$body."{$this->jsRenderer->render()}</body>".$footer;
    }
}

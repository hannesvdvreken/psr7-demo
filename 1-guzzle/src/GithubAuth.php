<?php
namespace Demo1;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

class GithubAuth
{
    public static function create()
    {
        return Middleware::mapRequest(function (RequestInterface $request) {
            if ($request->getUri()->getHost() !== 'api.github.com') {
                return $request;
            }

            $authorization = 'token '.getenv('GITHUB_TOKEN');

            return $request->withHeader('Authorization', $authorization);
        });
    }
}
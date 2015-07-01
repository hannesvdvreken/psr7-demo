<?php
namespace Github;

interface ClientInterface
{
    /**
     * @param string $username
     *
     * @return \Github\Entities\User
     */
    public function user($username);
}

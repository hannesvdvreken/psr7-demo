<?php
namespace Github\Entities;

class User
{
    /**
     * The username as seen on GitHub.
     *
     * @var string
     */
    private $username;

    /**
     * Public avatar url.
     *
     * @var string
     */
    private $avatar;

    /**
     * @param array $data
     *
     * @return static
     */
    public static function create(array $data)
    {
        $user = new static();

        $user->username = $data['login'];
        $user->avatar = $data['avatar_url'];

        return $user;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}

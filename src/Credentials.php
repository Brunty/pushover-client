<?php

namespace Brunty\Pushover;

class Credentials
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $token;

    public function __construct(string $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}

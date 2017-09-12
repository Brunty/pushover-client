<?php

namespace Brunty\Pushover;

class Message
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var null|string
     */
    private $title;

    public function __construct(string $body, ?string $title = null)
    {
        $this->body = $body;
        $this->title = $title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}

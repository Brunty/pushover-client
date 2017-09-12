<?php

namespace Brunty\Pushover\Spec\Stubs;

use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class StubClient implements HttpClient
{

    /**
     * @var bool
     */
    private $invalidToken;

    public function __construct($invalidToken = false)
    {
        $this->invalidToken = $invalidToken;
    }

    /**
     * Sends a PSR-7 request.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Http\Client\Exception If an error happens during processing the request.
     * @throws \Exception             If processing the request is impossible (eg. bad configuration).
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $body = <<<BODY
{
  "user": "invalid",
  "errors": [
    "user identifier is not a valid user, group, or subscribed user key"
  ],
  "status": 0,
  "request": "a79555b7-4458-49de-be09-5493dd3bef63"
}
BODY;

        if ($this->invalidToken) {
            $body = <<<BODY
{
  "token": "invalid",
  "errors": [
    "application token is invalid"
  ],
  "status": 0,
  "request": "request_id_goes_here"
}
BODY;
        }

        throw new HttpException('Message here', $request, (new GuzzleMessageFactory)->createResponse(400, null, [], $body));
    }
}

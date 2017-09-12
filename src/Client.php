<?php

namespace Brunty\Pushover;

use Brunty\Pushover\Exception\InvalidToken;
use Brunty\Pushover\Exception\InvalidUser;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\RequestFactory;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const PUSHOVER_MESSAGES = 'https://api.pushover.net/1/messages.json';

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * @var ResponseInterface|null
     */
    private $lastResponse;

    /**
     * @var ResponseInterface[]
     */
    private $responses = [];

    public function __construct(HttpClient $client, Credentials $credentials, RequestFactory $requestFactory = null)
    {
        $this->client = $client;
        $this->credentials = $credentials;
        $this->requestFactory = $requestFactory ?? new GuzzleMessageFactory;
    }

    /**
     * @param string|Message $message
     * @param null|string    $device
     */
    public function pushMessage($message, ?string $device = null): void
    {
        if (is_string($message)) {
            $message = new Message($message);
        }

        $body = [
            'token' => $this->credentials->getToken(),
            'user' => $this->credentials->getUser(),
            'device' => $device,
            'title' => $message->getTitle(),
            'message' => $message->getBody()
        ];

        $request = $this->requestFactory->createRequest('POST', self::PUSHOVER_MESSAGES, [], http_build_query($body));

        try {
            $response = $this->client->sendRequest($request);
            $statusCode = $response->getStatusCode();

            $this->responses[] = $response;
            $this->lastResponse = $response;

            // we perform this check here as clients might not throw a HttpException, they might just return the response
            if ($this->isClientError($statusCode)) {
                $this->handleRequestError($response);
            }
        } catch (HttpException $e) {
            $this->handleRequestError($e->getResponse(), $e);
            $this->responses[] = $e->getResponse();
            $this->lastResponse = $e->getResponse();

            throw $e;
        }
    }

    public static function guzzle(string $user, string $token): Client
    {
        return new self(new GuzzleClient, new Credentials($user, $token));
    }

    public function getLastResponse(): ResponseInterface
    {
        return $this->lastResponse;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    private function handleRequestError(ResponseInterface $response, HttpException $e = null): void
    {
        $responseBody = json_decode($response->getBody()->getContents(), true);
        $errors = $responseBody['errors'] ?? [];

        if (isset($responseBody['user']) && $responseBody['user'] === 'invalid') {
            throw new InvalidUser('', $errors, $e);
        }

        if (isset($responseBody['token']) && $responseBody['token'] === 'invalid') {
            throw new InvalidToken('', $errors, $e);
        }
    }

    public function isClientError(int $statusCode): bool
    {
        return $statusCode >= Response::HTTP_BAD_REQUEST && $statusCode < Response::HTTP_SERVER_ERROR;
    }
}

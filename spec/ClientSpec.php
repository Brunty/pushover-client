<?php

use Brunty\Pushover\Client;
use Brunty\Pushover\Credentials;
use Brunty\Pushover\Message;
use Brunty\Pushover\Spec\Stubs\StubClient;
use Http\Mock\Client as MockClient;
use Psr\Http\Message\ResponseInterface;

describe(Client::class, function () {
    it('sends requests', function () {
        $mockClient = new MockClient;
        $client = new Client($mockClient, new Credentials('user', 'token'));
        $client->pushMessage(new Message('Hi there'));

        $sentRequests = $mockClient->getRequests();
        expect($sentRequests)->toHaveLength(1);
        expect($sentRequests[0]->getBody()->getContents())->toBe('token=token&user=user&message=Hi+there');
    });

    it('stores the responses from calls it has made', function () {
        $client = new Client(new MockClient, new Credentials('user', 'token'));
        $client->pushMessage(new Message('Hi there'));

        expect($client->getResponses())->toHaveLength(1);
        expect($client->getLastResponse())->toBeAnInstanceOf(ResponseInterface::class);
    });

    it('handles errors on a bad user', function () {
        $fn = function() {
            $mockClient = new StubClient;
            $client = new Client($mockClient, new Credentials('user', 'token'));
            $client->pushMessage(new Message('Hi there'));
        };

        expect($fn)->toThrow();
    });

    it('handles errors on a bad token', function () {
        $fn = function() {
            $mockClient = new StubClient(true);
            $client = new Client($mockClient, new Credentials('user', 'token'));
            $client->pushMessage(new Message('Hi there'));
        };

        expect($fn)->toThrow();
    });
});

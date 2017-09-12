<?php

use Brunty\Pushover\Exception\InvalidCredentials;
use Http\Client\Exception\HttpException;
use Http\Message\MessageFactory\GuzzleMessageFactory;

describe(InvalidCredentials::class, function () {
    it('stores errors about the credentials', function () {
        $exception = new InvalidCredentials("message", ['token invalid', 'user invalid']);
        expect($exception->getErrors())->toBe(['token invalid', 'user invalid']);
    });
    it('stores the HTTP exception', function () {
        $factory = new GuzzleMessageFactory;
        $request = $factory->createRequest('GET', 'http://site.com');
        $response = $factory->createResponse();
        $clientException = new HttpException("message", $request, $response);
        $exception = new InvalidCredentials("message", [], $clientException);

        expect($exception->getHttpException())->toBe($clientException);
    });
});

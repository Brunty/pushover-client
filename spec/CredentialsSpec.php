<?php

use Brunty\Pushover\Credentials;

describe(Credentials::class, function () {
    it('has a user id and a token', function () {
        $credentials = new Credentials('user_id', 'token');

        expect($credentials->getUser())->toBe('user_id');
        expect($credentials->getToken())->toBe('token');
    });
});

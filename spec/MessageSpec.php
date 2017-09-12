<?php

use Brunty\Pushover\Message;

describe(Message::class, function () {
    it('has a message body', function () {
        $message = new Message('body here');

        expect($message->getBody())->toBe('body here');
    });

    it('does\'t require a title', function () {
        $message = new Message('body here');

        expect($message->getTitle())->toBeNull();
    });

    it('can be given a title', function () {
        $message = new Message('body here', 'title here');

        expect($message->getTitle())->toBe('title here');
    });
});

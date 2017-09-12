# Pushover Client

[![Build Status](https://travis-ci.org/Brunty/pushover-client.svg)](https://travis-ci.org/Brunty/pushover-client)

❗️ Provides functionality to send messages via [Pushover](https://pushover.net)

## Requirements

* PHP >= 7.1

## Installation

`composer require brunty/pushover`

You will also need to require your adapter of choice http://docs.php-http.org/en/latest/clients.html

## Usage

As per the [Pushover API Documentation](https://pushover.net/api) - `user` and `token` form your credentials.

Messages are made up of a message body and a title in a `Brunty\Pushover\Message` object.

Optionally you can specify a device to send the message to as well, if you don't specify this, it'll send to all devices.

```php
<?php
use Brunty\Pushover\Client;
use Brunty\Pushover\Credentials;
use Brunty\Pushover\Message;
use Http\Mock\Client as MockClient;

// Mock client here wouldn't send a request, it's used for testing
// substitute with your own real client from the adapters above
$client = new Client(new MockClient, new Credentials('user', 'token'));
$client->pushMessage(new Message('message', 'optional title'), 'optional device');
```

The following are not (yet) supported, but will be in the near future:

* `url`
* `url_title`
* `priority`
* `timestamp`
* `sound`

## Contributing

This started as a small personal project.

Although this project is small, openness and inclusivity are taken seriously. To that end a code of conduct (listed in the contributing guide) has been adopted.

[Contributor Guide](CONTRIBUTING.md)


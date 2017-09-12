<?php

namespace Brunty\Pushover\Exception;

use Http\Client\Exception\HttpException;

class InvalidCredentials extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @var HttpException|null
     */
    private $httpException;

    public function __construct($message, array $errors = [], ?HttpException $httpException = null)
    {
        parent::__construct($message);
        $this->errors = $errors;
        $this->httpException = $httpException;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getHttpException(): ?HttpException
    {
        return $this->httpException;
    }
}

<?php

namespace Src\Domain\Exception;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\HttpFoundation\Response;

class NotificationException extends Exception
{
    public static function serviceUnavailable(BadResponseException $exception): self
    {
        return new self(
            sprintf('Unexpected error: %s ', $exception->getMessage()),
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}

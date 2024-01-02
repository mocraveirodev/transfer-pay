<?php

namespace Src\Domain\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserException extends Exception
{
    public static function notFound(): self
    {
        return new self('User not found', Response::HTTP_NOT_FOUND);
    }
}

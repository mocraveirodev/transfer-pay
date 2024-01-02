<?php

namespace Src\Domain\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class WalletException extends Exception
{
    public static function notFound(): self
    {
        return new self('Wallet not found', Response::HTTP_NOT_FOUND);
    }
}

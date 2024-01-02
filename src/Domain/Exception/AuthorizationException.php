<?php

namespace Src\Domain\Exception;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Log;
use Src\Application\DTO\TransactionDTO;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationException extends Exception
{
    public static function unauthorizedTransaction(TransactionDTO $dto): self
    {
        Log::info(sprintf(
            "User with %s not be authorized transfer to user with id %s",
            $dto->payer['id'], $dto->payee['id'])
        );
        return new self('Unauthorized transaction', Response::HTTP_UNAUTHORIZED);
    }

    public static function serviceUnavailable(BadResponseException $exception): self
    {
        return new self(
            sprintf('Unexpected error: %s ', $exception->getMessage()),
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}

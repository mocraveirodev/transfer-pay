<?php

namespace Src\Domain\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TransactionException extends Exception
{
    public static function insufficientFunds(): self
    {
        return new self(
            'The payer does not have sufficient funds in the wallet.',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public static function merchantCannotBePayer(): self
    {
        return new self(
            'The merchant cannot be a payer.',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public static function userCannotBeTheSame(): self
    {
        return new self(
            'It is not allowed to send money to yourself',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

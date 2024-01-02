<?php

namespace Src\Application\DTO;

readonly class TransactionDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $payer_id,
        public readonly string $payee_id,
    )
    {
    }

    public static function makeDto(array $data): self
    {
        return new self(
            amount: $data['amount'],
            payer_id: $data['payer_id'],
            payee_id: $data['payee_id'],
        );
    }
}

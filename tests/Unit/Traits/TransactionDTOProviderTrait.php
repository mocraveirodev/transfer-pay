<?php

namespace Tests\Unit\Traits;

use Src\Application\DTO\TransactionDTO;

trait TransactionDTOProviderTrait
{
    public function validTransactionDTOPayload(array $fields = []): array
    {
        return [
            'amount' => 100,
            ...$fields,
        ];
    }

    public function validTransactionDTO(array $fields = []): TransactionDTO
    {
        return TransactionDTO::makeDto($this->validTransactionDTOPayload($fields));
    }
}

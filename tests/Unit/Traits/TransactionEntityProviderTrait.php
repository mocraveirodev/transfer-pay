<?php

namespace Tests\Unit\Traits;

use Src\Domain\Entities\TransactionEntity;

trait TransactionEntityProviderTrait
{
    public function validTransactionPayload(array $fields = []): array
    {
        return [
            'id' => 'Id-teste-123',
            'amount' => 100,
            'payer_id' => 'id-payer-123',
            'payee_id' => 'id-payee-1123',
            ...$fields,
        ];
    }

    public function validTransactionEntity(array $fields = []): TransactionEntity
    {
        return TransactionEntity::makeEntity($this->validTransactionPayload($fields));
    }
}

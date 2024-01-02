<?php

namespace Src\Application\Factory;

use Src\Domain\Interfaces\TransactionServiceInterface;
use Src\Domain\Enum\DocumentTypeEnum;
use Src\Domain\Service\ToMerchantTransactionService;
use Src\Domain\Service\ToUserTransactionService;

class TransactionServiceFactory
{
    public function __construct(
        private readonly ToUserTransactionService $toUserTransactionService,
        private readonly ToMerchantTransactionService $toMerchantTransactionService,
    ) {
    }

    public function createTransactionObject(string $documentType): TransactionServiceInterface
    {
        return match ($documentType) {
            DocumentTypeEnum::CPF->value => $this->toUserTransactionService,
            DocumentTypeEnum::CNPJ->value  => $this->toMerchantTransactionService,
        };
    }
}

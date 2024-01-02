<?php

namespace Src\Domain\Service;

use Illuminate\Support\Facades\Log;
use Src\Domain\Enum\DocumentTypeEnum;
use Src\Domain\Exception\TransactionException;
use Src\Infrastructure\Models\Wallet;

class TransactionRulesService
{
    /**
     * @throws TransactionException
     */
    public static function isPayerMerhant(string $documentType): void
    {
        if ($documentType === DocumentTypeEnum::CNPJ->value) {
            Log::info(sprintf("Payer cannot be a merchant"));
            throw TransactionException::merchantCannotBePayer();
        }
    }

    /**
     * @throws TransactionException
     */
    public static function isSameUser(string $payerId, string $payeeId): void
    {
        if ($payerId === $payeeId) {
            Log::info(sprintf("It is not allowed to send money to yourself"));
            throw TransactionException::userCannotBeTheSame();
        }
    }

    /**
     * @throws TransactionException
     */
    public static function haveSufficientMoneyToTransfer(Wallet $payerWallet, float $amount): void
    {
        if ((float)$payerWallet->balance < $amount) {
            Log::info(sprintf("User %s does not have sufficient money to transfer", $payerWallet->user->id));
            throw TransactionException::insufficientFunds();
        }
    }
}

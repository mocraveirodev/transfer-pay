<?php

namespace Src\Infrastructure\Repositories;

use Src\Domain\Repositories\WalletRepositoryInterface;
use Src\Infrastructure\Models\Wallet;

class WalletRepository implements WalletRepositoryInterface
{
    public function __construct(
        private readonly Wallet $wallet
    ) {
    }

    public function getWaletByUserId(string $userId): ?Wallet
    {
        return $this->wallet->lockForUpdate()->where('owner_id', $userId)->first();
    }

    public function updatePayerWallet(Wallet $payer, float $amount): void
    {
        $payer->update(['balance' => $payer->balance - $amount]);
    }

    public function updatePayeeWallet(Wallet $payee, float $amount): void
    {
        $payee->update(['balance' => $payee->balance + $amount]);
    }
}

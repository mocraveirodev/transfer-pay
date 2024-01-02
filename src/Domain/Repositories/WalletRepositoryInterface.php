<?php

namespace Src\Domain\Repositories;

use Src\Infrastructure\Models\Wallet;

interface WalletRepositoryInterface
{
    public function getWaletByUserId(string $userId);
    public function updatePayerWallet(Wallet $payer, float $amount);
    public function updatePayeeWallet(Wallet $payee, float $amount);
}

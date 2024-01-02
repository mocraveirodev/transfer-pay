<?php

namespace Src\Domain\Service;

use Src\Domain\Exception\WalletException;
use Src\Domain\Repositories\WalletRepositoryInterface;

class WalletService
{
    public function __construct(
        private readonly WalletRepositoryInterface $walletRepository
    )
    {
    }

    /**
     * @throws WalletException
     */
    public function getAndUpdateWallets(
        string $payerId,
        string $payeeId,
        float $amount
    ): void
    {
        $payerWallet = $this->walletRepository->getWaletByUserId($payerId);
        $payeeWallet = $this->walletRepository->getWaletByUserId($payeeId);

        if (!($payerWallet && $payeeWallet)) {
            throw WalletException::notFound();
        }

        $this->walletRepository->updatePayerWallet($payerWallet, $amount);
        $this->walletRepository->updatePayeeWallet($payeeWallet, $amount);
    }
}

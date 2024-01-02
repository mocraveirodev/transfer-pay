<?php

namespace Src\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Application\DTO\TransactionDTO;
use Src\Domain\Entities\TransactionEntity;
use Src\Infrastructure\Jobs\NotificationJob;
use Src\Domain\Repositories\TransactionRepositoryInterface;
use Src\Domain\Service\WalletService;
use Src\Infrastructure\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        private readonly WalletService $walletService,
        private readonly Transaction $transactionModel,
        private readonly NotificationJob $notificationJob,
    ) {
    }

    public function create(TransactionDTO $dto): TransactionEntity
    {
        return DB::transaction(function () use ($dto) {
            $this->walletService->getAndUpdateWallets(
                $dto->payer_id,
                $dto->payee_id,
                $dto->amount
            );

            $transaction = $this->transactionModel->create([
                'payer_id' => $dto->payer_id,
                'payee_id' => $dto->payee_id,
                'amount' => $dto->amount,
            ]);

            $transactionEntity = TransactionEntity::makeEntity($transaction->toArray());

            $this->notificationJob::dispatch();

            Log::info(sprintf("Transaction %d created with success", $transactionEntity->id));

            return $transactionEntity;
        });
    }
}

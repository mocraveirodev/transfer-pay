<?php

namespace Src\Domain\Service;

use Src\Application\DTO\TransactionDTO;
use Src\Domain\Interfaces\AuthorizationClientInterface;
use Src\Domain\Interfaces\TransactionServiceInterface;
use Src\Domain\Entities\TransactionEntity;
use Src\Domain\Exception\AuthorizationException;
use Src\Domain\Repositories\TransactionRepositoryInterface;

class ToMerchantTransactionService implements TransactionServiceInterface
{
    public function __construct(
        private readonly AuthorizationClientInterface $authorizationClient,
        private readonly TransactionRepositoryInterface $transactionRepository,
    ) {

    }

    /**
     * @throws AuthorizationException
     */
    public function createTransaction(TransactionDTO $dto): TransactionEntity
    {
        if (!$this->authorizationClient::dispatch()) {
            throw AuthorizationException::unauthorizedTransaction($dto);
        }

        return $this->transactionRepository->create($dto);
    }
}

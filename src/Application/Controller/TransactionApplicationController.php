<?php

namespace Src\Application\Controller;

use Src\Application\DTO\TransactionDTO;
use Src\Application\Factory\TransactionServiceFactory;
use Src\Domain\Entities\TransactionEntity;
use Src\Domain\Exception\TransactionException;
use Src\Domain\Exception\UserException;
use Src\Domain\Service\TransactionRulesService;
use Src\Domain\Service\UserService;

class TransactionApplicationController
{
    public function __construct(
        private readonly TransactionServiceFactory $transactionServiceFactory,
        private readonly UserService $userService
    ) {
    }

    /**
     * @throws TransactionException
     * @throws UserException
     */
    public function transaction(
        TransactionDTO $dto,
        $payer = null
    ): TransactionEntity
    {
        $payer = $payer ?? request()->user();

        TransactionRulesService::isPayerMerhant($payer->document_type);
        TransactionRulesService::isSameUser($payer->id, $dto->payee_id);

        $payee = $this->userService->getUser($dto->payee_id);

        TransactionRulesService::haveSufficientMoneyToTransfer($payer->wallet, $dto->amount);

        $transactionService = $this->transactionServiceFactory->createTransactionObject($payee->document_type);

        return $transactionService->createTransaction($dto);
    }
}

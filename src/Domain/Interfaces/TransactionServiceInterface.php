<?php

namespace Src\Domain\Interfaces;

use Src\Application\DTO\TransactionDTO;
use Src\Domain\Entities\TransactionEntity;

interface TransactionServiceInterface
{
    public function createTransaction(TransactionDTO $dto): TransactionEntity;
}

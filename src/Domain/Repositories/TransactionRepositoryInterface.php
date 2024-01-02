<?php

namespace Src\Domain\Repositories;

use Src\Application\DTO\TransactionDTO;
use Src\Domain\Entities\TransactionEntity;

interface TransactionRepositoryInterface
{
    public function create(TransactionDTO $dto): TransactionEntity;
}

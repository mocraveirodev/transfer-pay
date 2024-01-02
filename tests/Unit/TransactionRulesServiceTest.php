<?php

namespace Tests\Unit;

use Src\Domain\Enum\DocumentTypeEnum;
use Src\Domain\Exception\TransactionException;
use Src\Domain\Service\TransactionRulesService;
use Src\Infrastructure\Models\User;
use Src\Infrastructure\Models\Wallet;
use Tests\TestCase;

class TransactionRulesServiceTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->has(Wallet::factory()->state(['balance' => 500]))->create();
    }

    public function testTransactionRulesServiceCaseCheckPayerIsNotMerchantException(): void
    {
        $this->expectException(TransactionException::class);

        TransactionRulesService::isPayerMerhant(DocumentTypeEnum::CNPJ->value);
    }

    public function testTransactionRulesServiceCaseCheckIsNotSameUserException(): void
    {
        $this->expectException(TransactionException::class);

        TransactionRulesService::isSameUser($this->user->id, $this->user->id);
    }

    public function testTransactionRulesServiceCaseCheckUserHaveSufficientBalanceException(): void
    {
        $this->expectException(TransactionException::class);

        TransactionRulesService::haveSufficientMoneyToTransfer($this->user->wallet, 1000);
    }
}

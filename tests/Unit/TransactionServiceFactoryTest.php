<?php

namespace Tests\Unit;

use Mockery;
use Src\Application\Factory\TransactionServiceFactory;
use Src\Domain\Enum\DocumentTypeEnum;
use Src\Domain\Service\ToMerchantTransactionService;
use Src\Domain\Service\ToUserTransactionService;
use Tests\TestCase;

class TransactionServiceFactoryTest extends TestCase
{
    private ToUserTransactionService $toUserTransactionService;
    private ToMerchantTransactionService $toMerchantTransactionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->toUserTransactionService = Mockery::mock(ToUserTransactionService::class);
        $this->toMerchantTransactionService = Mockery::mock(ToMerchantTransactionService::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testTransactionServiceFactoryWhenIsToAnUser(): void
    {
        $transactionServiceFactory = new TransactionServiceFactory(
            $this->toUserTransactionService,
            $this->toMerchantTransactionService
        );

        $response = $transactionServiceFactory->createTransactionObject(DocumentTypeEnum::CPF->value);

        $this->assertSame($response, $this->toUserTransactionService);
    }

    public function testTransactionServiceFactoryWhenIsToAMerchant(): void
    {
        $transactionServiceFactory = new TransactionServiceFactory(
            $this->toUserTransactionService,
            $this->toMerchantTransactionService
        );

        $response = $transactionServiceFactory->createTransactionObject(DocumentTypeEnum::CNPJ->value);

        $this->assertSame($response, $this->toMerchantTransactionService);
    }
}

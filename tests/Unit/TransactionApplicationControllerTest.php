<?php

namespace Tests\Unit;

use Mockery;
use Src\Application\Controller\TransactionApplicationController;
use Src\Application\DTO\TransactionDTO;
use Src\Application\Factory\TransactionServiceFactory;
use Src\Domain\Entities\TransactionEntity;
use Src\Domain\Service\ToUserTransactionService;
use Src\Domain\Service\UserService;
use Src\Infrastructure\Models\User;
use Src\Infrastructure\Models\Wallet;
use Tests\TestCase;
use Tests\Unit\Traits\TransactionEntityProviderTrait;
use Tests\Unit\Traits\TransactionDTOProviderTrait;

class TransactionApplicationControllerTest extends TestCase
{
    use TransactionEntityProviderTrait;
    use TransactionDTOProviderTrait;

    private User $user;
    private User $userWithoutBalance;
    private TransactionServiceFactory $transactionServiceFactory;
    private ToUserTransactionService $ToUserTransactionService;
    private UserService $userService;
    private TransactionDTO $transactionDTO;
    private TransactionEntity $transactionEntity;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->has(Wallet::factory()->state(['balance' => 500]))->create();
        $this->userWithoutBalance = User::factory()
            ->has(Wallet::factory()->state(['balance' => 0]))
            ->create();

        $this->transactionServiceFactory = Mockery::mock(TransactionServiceFactory::class);
        $this->ToUserTransactionService = Mockery::mock(ToUserTransactionService::class);
        $this->userService = Mockery::mock(UserService::class);

        $this->transactionEntity = $this->validTransactionEntity();
        $this->transactionDTO = $this->validTransactionDTO([
            'payer_id' => $this->user->id,
            'payee_id' => $this->userWithoutBalance->id,
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testApplicationControllerSuccessfuly(): void
    {
        $this->actingAs($this->user);

        $this->transactionServiceFactory
            ->shouldReceive('createTransactionObject')
            ->with($this->userWithoutBalance->document_type)
            ->once()
            ->andReturn($this->ToUserTransactionService);

        $this->ToUserTransactionService
            ->shouldReceive('createTransaction')
            ->with($this->transactionDTO)
            ->once()
            ->andReturn($this->transactionEntity);

        $this->userService
            ->shouldReceive('getUser')
            ->with($this->userWithoutBalance->id)
            ->once()
            ->andReturn($this->userWithoutBalance);


        $transactionApplicationController =
            new TransactionApplicationController($this->transactionServiceFactory, $this->userService);

        $response = $transactionApplicationController->transaction($this->transactionDTO, $this->user);

        $this->assertSame($response, $this->transactionEntity);
    }
}

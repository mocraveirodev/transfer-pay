<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Src\Domain\Exception\TransactionException;
use Src\Domain\Exception\UserException;
use Src\Domain\Exception\WalletException;
use Src\Infrastructure\Models\User;
use Src\Infrastructure\Models\Wallet;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
class TransactionTest extends TestCase
{
    private User $user;
    private User $userWithoutBalance;
    private User $merchant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->has(Wallet::factory()->state(['balance' => 500]))->create();
        $this->userWithoutBalance = User::factory()
            ->has(Wallet::factory()->state(['balance' => 0]))
            ->create();
        $this->merchant = User::factory()
            ->has(Wallet::factory()->state(['balance' => 0]))
            ->create(['document_type' => 'cnpj']);
    }

    public function fakeAuthorizedResponse(): void
    {
        Http::fake([
            config("authorize.url") => Http::response([
                "message" => "Autorizado"
            ], Response::HTTP_OK)
        ]);
    }

    public function testTransactionBetweenUsers(): void
    {
        $this->actingAs($this->user);
        $this->fakeAuthorizedResponse();

        $response = $this->post(route('transaction'), [
            'amount' => 100,
            'payee_id' => $this->userWithoutBalance->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('transactions', [
            'payer_id' => $this->user->id,
            'payee_id' => $this->userWithoutBalance->id,
            'amount' => 100
        ]);
        $this->assertDatabaseHas('wallets', [
            'owner_id' => $this->user->id,
            'balance' => 400
        ]);

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $this->userWithoutBalance->id,
            'balance' => 100
        ]);

        $response->assertJsonStructure([
            'id',
            'amount',
            'payerId',
            'payeeId',
        ]);
    }

    public function testTransactionBetweenUserAndMerchant(): void
    {
        $this->actingAs($this->user);
        $this->fakeAuthorizedResponse();

        $response = $this->post(route('transaction'), [
            'payee_id' => $this->merchant->id,
            'amount' => 100,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('transactions', [
            'payer_id' => $this->user->id,
            'payee_id' => $this->merchant->id,
            'amount' => 100
        ]);

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $this->user->id,
            'balance' => 400
        ]);

        $this->assertDatabaseHas('wallets', [
            'owner_id' => $this->merchant->id,
            'balance' => 100
        ]);

        $response->assertJsonStructure([
            'id',
            'amount',
            'payerId',
            'payeeId',
        ]);
    }

    public function testExceptionUserShouldDoATransferWithoutMoney(): void
    {
        $this->actingAs($this->userWithoutBalance);

        $response = $this->post(route('transaction'), [
            'payee_id' => $this->merchant->id,
            'amount' => 100,
        ]);

        $exception = TransactionException::insufficientFunds();

        $response->assertSee($exception->getMessage());
    }

    public function testExceptionMerchantCannotBePayer(): void
    {
        $this->actingAs($this->merchant);

        $response = $this->post(route('transaction'), [
            'payee_id' => $this->user->id,
            'amount' => 100,
        ]);

        $exception = TransactionException::merchantCannotBePayer();

        $response->assertSee($exception->getMessage());
    }

    public function testPayerCannotBePayee(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('transaction'), [
            'payee_id' => $this->user->id,
            'amount' => 100,
        ]);

        $exception = TransactionException::userCannotBeTheSame();

        $response->assertSee($exception->getMessage());
    }

    public function testWithoutExistentUser(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('transaction'), [
            'payee_id' => '37d93e5a-acc9-335f-a5b3-f8c7087ce84d',
            'amount' => 100,
        ]);

        $exception = UserException::notFound();

        $response->assertSee($exception->getMessage());
    }

    public function testWithoutExistentWallet(): void
    {
        $this->actingAs($this->user);

        $userWithoutBalance = User::factory()->create();

        $response = $this->post(route('transaction'), [
            'payee_id' => $userWithoutBalance->id,
            'amount' => 100,
        ]);

        $exception = WalletException::notFound();

        $response->assertSee($exception->getMessage());
    }
}

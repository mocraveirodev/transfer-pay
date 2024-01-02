<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Src\Domain\Interfaces\AuthorizationClientInterface;
use Src\Domain\Repositories\OutboxRepositoryInterface;
use Src\Domain\Repositories\TransactionRepositoryInterface;
use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Domain\Repositories\WalletRepositoryInterface;
use Src\Infrastructure\Clients\AuthorizationClient;
use Src\Infrastructure\Repositories\Eloquent\Transaction\OutboxRepository;
use Src\Infrastructure\Repositories\TransactionRepository;
use Src\Infrastructure\Repositories\UserRepository;
use Src\Infrastructure\Repositories\WalletRepository;

class TransactionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        // Repositories
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(OutboxRepositoryInterface::class, OutboxRepository::class);

        // Clients
        $this->app->bind(AuthorizationClientInterface::class, AuthorizationClient::class);
    }

    public function provides(): array
    {
        return [
            TransactionRepositoryInterface::class,
            UserRepositoryInterface::class,
            WalletRepositoryInterface::class
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Infrastructure\Models\User;
use Src\Infrastructure\Models\Wallet;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'balance' => 400,
        ];
    }
}

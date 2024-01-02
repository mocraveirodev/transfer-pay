<?php

namespace Src\Infrastructure\Repositories;

use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Infrastructure\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $user)
    {
    }

    public function find(string $id): ?User
    {
        return $this->user::where('id', $id)->lockForUpdate()->first();
    }
}

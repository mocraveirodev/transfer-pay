<?php

namespace Src\Domain\Repositories;

use Src\Infrastructure\Models\User;

interface UserRepositoryInterface
{
    public function find(string $id): ?User;
}

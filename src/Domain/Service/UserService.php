<?php

namespace Src\Domain\Service;

use Src\Domain\Exception\UserException;
use Src\Domain\Repositories\UserRepositoryInterface;
use Src\Infrastructure\Models\User;

class UserService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws UserException
     */
    public function getUser(string $id): User
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw UserException::notFound();
        }

        return $user;
    }
}

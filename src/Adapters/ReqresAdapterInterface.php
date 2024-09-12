<?php

declare(strict_types=1);

namespace ChrisLoftus\Reqres\Adapters;

use ChrisLoftus\Reqres\DataTransferObjects\User;
use ChrisLoftus\Reqres\DataTransferObjects\UserCreated;
use ChrisLoftus\Reqres\DataTransferObjects\UsersPaginated;

interface ReqresAdapterInterface
{
    public function getUser(int $id): User;

    public function createUser(string $name, string $job): UserCreated;

    public function getUsersPaginated(int $page): UsersPaginated;
}

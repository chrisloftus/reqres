<?php

declare (strict_types=1);

namespace ChrisLoftus\Reqres\Adapters;

use Exception;
use GuzzleHttp\Client;
use ChrisLoftus\Reqres\Logger;
use ChrisLoftus\Reqres\DataTransferObjects\User;
use ChrisLoftus\Reqres\DataTransferObjects\UserCreated;
use ChrisLoftus\Reqres\DataTransferObjects\UsersPaginated;

class ReqresAdapter
{
    private const BASE_URL = 'https://reqres.in/api';

    public function __construct(
        private Client $client
    ) {
    }

    public function getUser(int $id): User
    {
        $response = $this->client->get(self::BASE_URL."/users/{$id}");

        $json = json_decode($response->getBody()->getContents(), true)['data'];

        return User::fromArray($json);
    }
    
    public function createUser(string $name, string $job): UserCreated
    {
        if (empty(trim($name)) || empty(trim($job))) {
            throw new Exception("User's name and job must be provided when creating a user");
        }

        $response = $this->client->post(self::BASE_URL.'/users', [
            'json' => [
                'name' => $name,
                'job' => $job
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return UserCreated::fromArray($json);
    }

    public function getUsersPaginated(int $page): UsersPaginated
    {
        $response = $this->client->get(self::BASE_URL."/users?page={$page}");

        $json = json_decode($response->getBody()->getContents(), true);

        return UsersPaginated::fromArray($json);
    }
}

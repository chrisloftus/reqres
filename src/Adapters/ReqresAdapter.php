<?php

declare (strict_types=1);

namespace ChrisLoftus\Reqres\Adapters;

use Exception;
use GuzzleHttp\Client;
use ChrisLoftus\Reqres\DataTransferObjects\User;
use ChrisLoftus\Reqres\DataTransferObjects\UserCreated;
use ChrisLoftus\Reqres\DataTransferObjects\UsersPaginated;

class ReqresAdapter implements ReqresAdapterInterface
{
    public const BASE_URL = 'https://reqres.in/api';

    private $client;

    public function __construct(
        Client $client = null
    ) {
        $this->client = $client ?: new Client([
            'base_uri' => self::BASE_URL
        ]);
    }

    public function getUser(int $id): User
    {
        $response = $this->client->get("users/{$id}");

        $json = json_decode($response->getBody()->getContents(), true);

        return User::fromArray($json['data']);
    }
    
    public function createUser(string $name, string $job): UserCreated
    {
        if (empty(trim($name)) || empty(trim($job))) {
            throw new Exception("User's name and job must be provided when creating a user");
        }

        $response = $this->client->post('users', [
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
        $response = $this->client->get("users?page={$page}");

        $json = json_decode($response->getBody()->getContents(), true);

        return UsersPaginated::fromArray($json);
    }
}

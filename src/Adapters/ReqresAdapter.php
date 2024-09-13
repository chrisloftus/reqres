<?php

declare (strict_types=1);

namespace ChrisLoftus\Reqres\Adapters;

use Exception;
use GuzzleHttp\Client;
use ChrisLoftus\Reqres\DataTransferObjects\{User, UserCreated, UsersPaginated};
use ChrisLoftus\Reqres\Exceptions\{CouldNotGetUser, CreateUserValidationFailed, CouldNotGetUsersPaginated};

class ReqresAdapter implements ReqresAdapterInterface
{
    public const BASE_URL = 'https://reqres.in/api/';

    private Client $client;

    public function __construct(
        $client = null
    ) {
        $this->client = $client ?: new Client([
            'base_uri' => self::BASE_URL
        ]);
    }

    public function getUser(int $id): User
    {
        try {
            $response = $this->client->get("users/{$id}");
    
            $json = json_decode($response->getBody()->getContents(), true);
    
            return User::fromArray($json['data']);
        } catch (Exception $e) {
            throw new CouldNotGetUser("Failed to get user from Reqres", 1, $e);
        }
    }
    
    public function createUser(string $name, string $job): UserCreated
    {
        if (empty(trim($name)) || empty(trim($job))) {
            throw new CreateUserValidationFailed("User's name and job must be provided when creating a user on Reqres");
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
        try {
            $response = $this->client->get("users?page={$page}");
    
            $json = json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            throw new CouldNotGetUsersPaginated("Failed to get users (paginated) from Reqres", 1, $e);
        }

        return UsersPaginated::fromArray($json);
    }
}
